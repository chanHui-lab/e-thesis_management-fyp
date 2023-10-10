import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
// add the beginning of your app entry
// import 'vite/modulepreload-polyfill'


export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue()
    ],
    build: {
        manifest: true,
      },
    // build: {
    //     // generate manifest.json in outDir
    //     manifest: true,
    //     rollupOptions: {
    //       // overwrite default .html entry
    //       input: '/path/to/main.js',
    //     },
    //   },
});
