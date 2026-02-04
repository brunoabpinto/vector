import * as Vue from 'vue';

window.Vue = Vue;

const {
    createApp,
    ref,
    reactive,
    computed,
    watch,
    watchEffect,
    onMounted,
    onUnmounted,
    nextTick,
    toRef,
    toRefs,
    shallowRef,
    triggerRef,
    customRef,
    readonly,
    shallowReactive,
    shallowReadonly,
    toRaw,
    markRaw,
    provide,
    inject,
} = Vue;

document.querySelectorAll('template[data-vector]').forEach((template) => {
    const scriptContent = atob(template.dataset.vectorScript);
    const vars = JSON.parse(template.dataset.vectorVars || '[]');
    const mountEl = template.nextElementSibling;

    if (mountEl) {
        const setupFn = new Function(
            'createApp',
            'ref',
            'reactive',
            'computed',
            'watch',
            'watchEffect',
            'onMounted',
            'onUnmounted',
            'nextTick',
            'toRef',
            'toRefs',
            'shallowRef',
            'triggerRef',
            'customRef',
            'readonly',
            'shallowReactive',
            'shallowReadonly',
            'toRaw',
            'markRaw',
            'provide',
            'inject',
            `${scriptContent}; return { ${vars.join(', ')} };`
        );

        const setupResult = setupFn(
            createApp,
            ref,
            reactive,
            computed,
            watch,
            watchEffect,
            onMounted,
            onUnmounted,
            nextTick,
            toRef,
            toRefs,
            shallowRef,
            triggerRef,
            customRef,
            readonly,
            shallowReactive,
            shallowReadonly,
            toRaw,
            markRaw,
            provide,
            inject
        );

        createApp({
            setup() {
                return setupResult;
            },
        }).mount(mountEl);
    }

    template.remove();
});
