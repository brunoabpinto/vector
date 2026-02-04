<?php

namespace Vector;

class Vector
{
    /**
     * Transform all <script setup> blocks into template markers.
     */
    public static function transform(string $content): string
    {
        return preg_replace_callback(
            '/<script\s+setup[^>]*>(.*?)<\/script>/s',
            function ($matches) {
                $id = 'vector-'.uniqid();
                $scriptContent = trim($matches[1]);

                // Remove import statements - they'll be provided globally
                $scriptContent = preg_replace('/import\s*\{[^}]+\}\s*from\s*[\'"]vue[\'"];?\s*/s', '', $scriptContent);
                $scriptContent = trim($scriptContent);

                // Extract variable names from const/let/var declarations
                preg_match_all('/(?:const|let|var)\s+(\w+)\s*=/', $scriptContent, $varMatches);
                $variables = $varMatches[1] ?? [];
                $varsJson = json_encode($variables);

                // Encode script content for safe embedding
                $encodedScript = htmlspecialchars($scriptContent, ENT_QUOTES, 'UTF-8');

                return "<template data-vector=\"{$id}\" data-vector-vars='{$varsJson}'>{$encodedScript}</template>";
            },
            $content
        );
    }

    /**
     * Get the Vector runtime script.
     */
    public static function runtime(): string
    {
        return \Illuminate\Support\Facades\Vite::__invoke(['resources/js/vendor/vector.js'])->toHtml();
    }
}
