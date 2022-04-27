import {createWebHistory, createRouter} from 'vue-router';

import Login from './Vue/pages/Login.vue'
import Register from './Vue/pages/Register.vue'
import EditUser from './Vue/pages/EditUser.vue'
import Home from './Vue/pages/Home.vue'

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home

    },{
        name: 'login',
        path: '/login',
        component: Login

    },{
        name: 'userdata',
        path: '/userdata',
        component: EditUser

    },{
        name: 'register',
        path: '/register',
        component: Register
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;
