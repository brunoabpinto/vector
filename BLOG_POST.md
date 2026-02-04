---
title: "Vector: The easiest way to plug Vue in Blade"
slug: vector
publishDate: 3 Feb 2026
description: Laravel package that lets you write Vue directly in Blade templates using a simple <script setup> tag. Is it cursed? Maybe. Does it work? Absolutely
---

<!-- @format -->

You know that feeling when you're building a Laravel app and you just need a tiny bit of reactivity? A counter. A toggle. Something that feels overkill for a full Vue component but too annoying for vanilla JavaScript?

I kept reaching for Alpine.js, which is great, but I wanted Vue's Composition API. The `ref()`, the `computed()`, the familiar syntax I already know. So I built Vector.

## What Even Is This?

Vector is a Laravel package that lets you write Vue directly in your Blade templates with zero ceremony:

```blade
<script setup>
    const i = ref(0);
</script>

<div>
    <button @click="i++">Click Me</button>
    <div>
        Count: @{{ i }}
    </div>
    <div v-if="i > 5">Success!</div>
</div>
```

That's it. No build step for your components. No separate `.vue` files. No special directives wrapping your code. Just a `<script setup>` tag and you're done.

## Why Though?

I was tired of the mental gymnastics:

1. "This needs reactivity"
2. "Should I make a Vue component?"
3. "But it's just a counter..."
4. "Fine, I'll use Alpine"
5. "Wait, how do I do computed properties in Alpine again?"

With Vector, the answer is always: write it like you would in Vue, because it _is_ Vue.

## How It Works

The `<script setup>` tag gets transformed at compile time. Vector treats the **element immediately after** the script tag as your Vue template—everything inside that element becomes reactive, and anything outside it remains regular Blade.

1. Blade's precompiler finds your `<script setup>` blocks
2. Extracts your variable declarations
3. Mounts Vue on the next sibling element

The magic is in the variable extraction. It parses `const`, `let`, and `var` declarations and auto-returns them to the template. You write normal code, it figures out the rest.

### Escaping Blade Syntax

Since Blade also uses `{{ }}` for output, you need to prefix Vue's mustache syntax with `@` to prevent Blade from processing it:

```blade
{{-- This is Blade --}}
{{ $phpVariable }}

{{-- This is Vue (note the @) --}}
@{{ vueVariable }}
```

Alternatively, use Vue directives like `v-text` which don't conflict with Blade:

```blade
<span v-text="count"></span>
```

## Installation

```bash
composer require brunoabpinto/vector
```

Add Vector to your Vite entry points in `vite.config.js`:

```javascript
plugins: [
    laravel({
        input: [
            "resources/css/app.css",
            "resources/js/app.js",
            "resources/js/vendor/vector.js",
        ],
        // ...
    }),
],
resolve: {
    alias: {
        'vue': 'vue/dist/vue.esm-bundler.js',
    },
},
```

Add `@vectorJs` before your closing `</body>` tag in your layout:

```blade
<body>
    {{ $slot }}

    @vectorJs
</body>
```

That's it. Vector auto-publishes its runtime, and `@vectorJs` loads it where you need it.

## The Trade-offs

Let's be real about what this is:

**Good for:**

- Quick interactive elements
- Prototyping
- When you want Vue's API without Vue's ceremony
- Laravel apps that are mostly server-rendered with islands of reactivity

**Not great for:**

- Complex component hierarchies
- When you need proper SFC features (scoped styles, etc.)
- Large-scale SPAs (just use Inertia at that point)

## Is This Cursed?

A little bit, yes. We're essentially doing runtime compilation of Vue templates, which goes against the "compile everything ahead of time" philosophy.

But sometimes the right tool is the one that gets out of your way. And for those moments when you just want to add a reactive counter to your Blade view without spinning up a whole component ecosystem, Vector is there.

## Try It

The package is available on GitHub. Star it, fork it, tell me it's an abomination—whatever feels right.

```bash
composer require brunoabpinto/vector
```
