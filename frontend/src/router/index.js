import { createRouter, createWebHistory } from 'vue-router'

import MainPage from '../pages/MainPage.vue'
import CatalogPage from '../pages/CatalogPage.vue'
import CartPage from '../pages/CartPage.vue'
import LoginPage from '../pages/LoginPage.vue'
import RegisterPage from '../pages/RegisterPage.vue'

const routes = [
    {
        path: '/',
        name: 'main',
        component: MainPage,
    },
    {
        path: '/catalog',
        name: 'catalog',
        component: CatalogPage,
    },
    {
        path: '/cart',
        name: 'cart',
        component: CartPage,
    },
    {
        path: '/login',
        name: 'login',
        component: LoginPage,
    },
    {
        path: '/register',
        name: 'register',
        component: RegisterPage,
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router