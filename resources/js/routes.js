import {createRouter, createWebHistory} from 'vue-router';
import Dashboard from './components/Dashboardtest.vue'
import Errore from './components/NotFound.vue'

const routes = [
    {
        path:'/',
        // name: 'admin.dashboard',
        component: Dashboard,
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