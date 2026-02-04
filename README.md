<!-- @format -->

# Vector

Vue reactivity in Blade templates using a simple `<script setup>` tag.

## Installation

```bash
composer require brunoabpinto/vector
```

### Frontend Setup

Install Vue:

```bash
npm install vue
```

Add Vector to your Vite entry points in `vite.config.js`:

```js
export default defineConfig({
  plugins: [
    laravel({
      input: [
        "resources/css/app.css",
        "resources/js/app.js",
        "resources/js/vendor/vector.js",
      ],
      refresh: true,
    }),
  ],
  resolve: {
    alias: {
      vue: "vue/dist/vue.esm-bundler.js",
    },
  },
});
```

Add `@vectorJs` before your closing `</body>` tag in your layout:

```blade
<body>
    {{ $slot }}

    @vectorJs
</body>
```

## Usage

Use the `<script setup>` tag to add Vue reactivity to your Blade templates:

```blade
<<<<<<< HEAD
<script setup>
    const i = ref(0);
</script>

<div>
    <button @click="i++">Click Me</button>
    <div>
        Count: @{{ i }}
    </div>
    <div v-if="i > 5">Success!</div>
=======
@vector
    <script setup>
        const count = ref(0);
    </script>
@endvector

<div>
    <button @click="count++">Click me</button>
    <p>Count: @{{ count }}</p>
>>>>>>> 14fae173e732bbd7e3b13e6ee1cb90e45e3764df
</div>
```

### How it works

1. The `<script setup>` tag is transformed at compile time
2. It extracts variable declarations and auto-returns them to the template
3. Vue mounts on the **next sibling element** after the script—anything outside that element is not parsed by Vector
4. All Vue Composition API functions are available: `ref`, `reactive`, `computed`, `watch`, `onMounted`, etc.

### Escaping Blade Syntax

Since Blade also uses `{{ }}`, prefix Vue's mustache syntax with `@` to prevent Blade from processing it:

```blade
<<<<<<< HEAD
{{-- Blade --}}
{{ $phpVariable }}
=======
@vector
    <script setup>
        const name = ref('World');
    </script>
@endvector
>>>>>>> 14fae173e732bbd7e3b13e6ee1cb90e45e3764df

{{-- Vue (note the @) --}}
@{{ vueVariable }}
```

<<<<<<< HEAD
Or use Vue directives like `v-text`:

```blade
<span v-text="count"></span>
=======
@vector
    <script setup>
        const items = ref(['Apple', 'Banana', 'Cherry']);
        const count = computed(() => items.value.length);
    </script>
@endvector

<ul>
    <li v-for="item in items" v-text="item"></li>
    <p>Total: @{{ count }} items</p>
</ul>
>>>>>>> 14fae173e732bbd7e3b13e6ee1cb90e45e3764df
```

## License

MIT
