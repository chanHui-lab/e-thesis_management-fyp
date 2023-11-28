import './bootstrap';

import {createApp} from 'vue';
import router from './routes.js';
import Dashboardtest from './components/Dashboardtest.vue';
import { createVuetify } from 'vuetify';
import '@mdi/font/css/materialdesignicons.css'; // Ensure you are using css-loader
import 'vuetify/dist/vuetify.css';
import Dashboard from './components/Dashboardtest.vue'
import AdminDashboard from './components/AdminDash.vue'
import '@fortawesome/fontawesome-free/css/all.css'

const vuetify = createVuetify({
  })

  const appi = createApp({
  components: {
    'component-a': Dashboard,
    'component-b': AdminDashboard,
  },
});

appi.use(vuetify);
appi.mount('#app');
