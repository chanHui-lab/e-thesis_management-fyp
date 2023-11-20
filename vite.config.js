import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
// import Vuetify from 'vuetify';

// add the beginning of your app entry
// import 'vite/modulepreload-polyfill'
// import Vuetify from '@vuetify/vite-plugin';

// import Vuetify from 'vuetify/lib/framework';

import vuetify from 'vite-plugin-vuetify';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
        vuetify({ autoImport: true }),
    ],
    build: {
        manifest: true,
        commonjsOptions: {
            esmExternals: true
          },
      },
      resolve: {
        alias: {
          vue: 'vue/dist/vue.esm-bundler.js'
        }
      }
    // build: {
    //     // generate manifest.json in outDir
    //     manifest: true,
    //     rollupOptions: {
    //       // overwrite default .html entry
    //       input: '/path/to/main.js',
    //     },
    //   },
});
