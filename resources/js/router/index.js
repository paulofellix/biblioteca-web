import { createWebHistory, createRouter } from 'vue-router'
import store from '@/store/index'

/* Guest Component */
const Login = () => import('@/pages/Login.vue')
const Register = () => import('@/pages/Register.vue')
/* Guest Component */

/* Layouts */
const MainLayout = () => import('@/pages/layouts/Default.vue')
/* Layouts */

/* Authenticated Component */
const Dashboard = () => import('@/pages/Dashboard.vue')
/* Authenticated Component */


const routes = [
    {
        name: "login",
        path: "/login",
        component: Login,
        meta: {
            middleware: "guest",
            title: `Login`
        }
    },
    {
        name: "register",
        path: "/register",
        component: Register,
        meta: {
            middleware: "guest",
            title: `Register`
        }
    },
    {
        path: "/",
        component: MainLayout,
        meta: {
            middleware: "auth"
        },
        children: [
            {
                name: "dashboard",
                path: '/',
                component: Dashboard,
                meta: {
                    title: `Dashboard`
                }
            },
            {
                name: "livros",
                path: '/livros',
                component: () => import('@/pages/livros/Livros.vue'),
                meta: {
                    title: `Livros`,
                    resource: 'livros'
                }
            },
            {
                name: "alunos",
                path: '/alunos',
                component: () => import('@/pages/pessoas/Pessoas.vue'),
                meta: {
                    title: `Alunos`,
                    resource: 'alunos'
                }
            },
            {
                name: "professores",
                path: '/professores',
                component: () => import('@/pages/pessoas/Pessoas.vue'),
                meta: {
                    title: `Professores`,
                    resource: 'professores'
                }
            },
            {
                name: "emprestimos",
                path: '/emprestimos',
                component: () => import('@/pages/emprestimos/Emprestimos.vue'),
                meta: {
                    title: `Livros`,
                    resource: 'emprestimos'
                }
            },
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes, // short for `routes: routes`
})

router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    if (to.meta.middleware == "guest") {
        if (store.state.auth.authenticated) {
            next({ name: "dashboard" })
        }
        next()
    } else {
        if (store.state.auth.authenticated) {
            next()
        } else {
            next({ name: "login" })
        }
    }
})

export default router