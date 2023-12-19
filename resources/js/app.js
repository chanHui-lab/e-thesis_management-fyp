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
import AdminDashboard from './components/AdminDash.vue'
import AdminEvents from './components/AdminEvents.vue'

import '@fortawesome/fontawesome-free/css/all.css'
import { aliases, mdi } from 'vuetify/iconsets/mdi'

const vuetify = createVuetify({
  icons: {
    defaultSet: 'mdi',
    aliases,
    sets: {
      mdi,
    },
  },
})

  const appi = createApp({
  components: {
    'component-a': Dashboard,
    'component-b': AdminDashboard,
    'component-c': AdminEvents,
  },
});

// appi.use(router);
appi.use(vuetify);
appi.mount('#app');


// createApp(app)
//   .use(router)
//   .use(vuetify)
//   .mount('#app');

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