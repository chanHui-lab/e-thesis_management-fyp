import './bootstrap';

import {createApp} from 'vue';
import router from './routes.js';
// import app from './components/app.vue'
// import Vuetify from 'vuetify';
// import 'vuetify/dist/vuetify.min.css';
import Dashboardtest from './components/Dashboardtest.vue';

import { createVuetify } from 'vuetify';
import '@mdi/font/css/materialdesignicons.css'; // Ensure you are using css-loader
import 'vuetify/dist/vuetify.css';
import Dashboard from './components/Dashboardtest.vue'

const vuetify = createVuetify({
  })

// createApp(app)
//   .use(router)
//   .use(vuetify)
//   .mount('#app');

  const appi = createApp({
  components: {
    'component-a': Dashboard,
    // 'component-b': ComponentB,
    // 'component-c': ComponentC,
  },
});

// appi.use(router);
appi.use(vuetify);
appi.mount('#app');

// ++++++
// const app = createApp({});

// const router = createRouter({
//     routes: Routes,
//     history: createWebHistory(),
// })

// app.use(router);

// app.mount('#app');

// const app = createApp({
//   data() {
//       return {
//           data: [],
//       };
//   },
//   beforeCreate() {
//       console.log("Fetching data before creating the app");
//       axios.get('/testvue')
//           .then(response => {
//               this.data = response.data;
//               // Continue with creating the app after data is fetched
//               app.use(router).component('dashboard', Dashboardtest).mount("#app");
//           });
//   },
// });

// ...

// export default app;