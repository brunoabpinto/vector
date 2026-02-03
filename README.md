# Vector

Vue reactivity in Blade templates using a familiar `<script setup>` syntax.

## Installation

Add the package to your `composer.json` repositories:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "packages/vector"
        }
    ]
}
```

Then require the package:

```bash
composer require brunoabpinto/vector
```

### Frontend Setup

Install Vue:

```bash
npm install vue
```

Update your `vite.config.js` to use Vue's runtime compiler:

```js
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        vue(),
        // ... other plugins
    ],
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js",
        },
    },
});
```

Expose Vue globally in your `resources/js/app.js`:

```js
import * as Vue from "vue";

window.Vue = Vue;
```

## Usage

Use the `@vector` directive to add Vue reactivity to your Blade templates:

```blade
@vector
    <script setup>
        import { ref } from 'vue';
        const count = ref(0);
    </script>
@endvector

<div>
    <button @click="count++">Click me</button>
    <p>Count: <span v-text="count"></span></p>
</div>
```

### How it works

1. The `@vector` directive captures your `<script setup>` block
2. It extracts variable declarations and auto-returns them to the template
3. Vue mounts on the next sibling element after the directive
4. All Vue Composition API functions are available: `ref`, `reactive`, `computed`, `watch`, `onMounted`, etc.

### Multiple components

You can use multiple `@vector` blocks on the same page:

```blade
@vector
    <script setup>
        import { ref } from 'vue';
        const name = ref('World');
    </script>
@endvector

<div>
    <input v-model="name" />
    <p>Hello, <span v-text="name"></span>!</p>
</div>

@vector
    <script setup>
        import { ref, computed } from 'vue';
        const items = ref(['Apple', 'Banana', 'Cherry']);
        const count = computed(() => items.value.length);
    </script>
@endvector

<ul>
    <li v-for="item in items" v-text="item"></li>
    <p>Total: <span v-text="count"></span> items</p>
</ul>
```

## License

MIT
