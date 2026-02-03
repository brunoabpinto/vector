<?php

namespace Vector;

class Vector
{
    /**
     * Render a vector component with script setup support.
     */
    public static function render(string $id, string $content): string
    {
        // Extract script setup content
        $scriptContent = '';
        if (preg_match('/<script\s+setup[^>]*>(.*?)<\/script>/s', $content, $matches)) {
            $scriptContent = trim($matches[1]);
            // Remove import statements - they'll be provided globally
            $scriptContent = preg_replace('/import\s*\{[^}]+\}\s*from\s*[\'"]vue[\'"];?\s*/s', '', $scriptContent);
        }

        // Extract variable names from const/let/var declarations
        preg_match_all('/(?:const|let|var)\s+(\w+)\s*=/', $scriptContent, $varMatches);
        $variables = $varMatches[1] ?? [];
        $returnObject = implode(', ', $variables);

        return <<<HTML
        <script data-vector="{$id}">
        (function(__script) {
            function __mount() {
                const { createApp, ref, reactive, computed, watch, watchEffect, onMounted, onUnmounted, nextTick } = window.Vue;

                {$scriptContent}

                const __vectorEl = __script.nextElementSibling;
                if (__vectorEl) {
                    createApp({
                        setup() {
                            return { {$returnObject} };
                        }
                    }).mount(__vectorEl);
                }
            }

            function __waitForVue() {
                if (window.Vue) {
                    __mount();
                } else {
                    requestAnimationFrame(__waitForVue);
                }
            }

            __waitForVue();
        })(document.currentScript);
        </script>
        HTML;
    }
}
