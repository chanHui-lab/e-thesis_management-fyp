import './bootstrap';

import {createApp} from 'vue';
import router from './routes.js';
import app from './components/app.vue'


// const app = createApp({});

// const router = createRouter({
//     routes: Routes,
//     history: createWebHistory(),
// })

// app.use(router);

// app.mount('#app');

createApp(app).use(router).mount("#app")