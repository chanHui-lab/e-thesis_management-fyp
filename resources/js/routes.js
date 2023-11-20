import {createRouter, createWebHistory} from 'vue-router';
import Dashboard from './components/Dashboardtest.vue'
import Errore from './components/NotFound.vue'

const routes = [
    {
        path:'/testvue',
        // name: 'admin.dashboard',
        component: Dashboard,
        // props: (route) => {
        //     const data = route.query.data ? JSON.parse(route.query.data)[0] : [];
        //     console.log('Received data in route:', data);
        //       console.log('Route Query:', route.query);

        //     return { data };
        // },
        // props: (route) => ({ data: route.params.data }),
        props: true

    },
    {
        // path:'/admin/dashboard/:pathMatch(.*)*',
        path:'/admin/dashboard',
        // name: 'admin.error',
        component: Errore,
    }
]

const router = createRouter({
    routes,
    history: createWebHistory(),
})

export default router