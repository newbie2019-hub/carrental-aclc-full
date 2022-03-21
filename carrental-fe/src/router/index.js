import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'

import Dashboard from '../views/user/Dashboard.vue'
import Settings from '../views/user/Settings.vue'
import Inquiries from '../views/user/Inquiries.vue'
import Cars from '../views/user/Cars.vue'
import Brand from '../views/user/Brands.vue'
import Driver from '../views/user/Driver.vue'
import Branch from '../views/user/Branch.vue'
import Rentals from '../views/user/Rentals.vue'
import Users from '../views/user/Users.vue'

import store from '../store'
Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
    meta: { title: 'Renta Car - Welcome Back' }
  },
  {
    path: '/login',
    name: 'Login',
    meta: { isAuth: true, title: 'Login Account', auth: true },
    component: () => import(/* webpackChunkName: "login" */ '../views/auth/Login.vue')
  },
  {
    path: '/signup',
    name: 'Signup',
    meta: { isAuth: true, title: 'Create Account', auth: true },
    component: () => import(/* webpackChunkName: "signup" */ '../views/auth/Signup.vue')
  },
  {
    path: '/user',
    name: 'Index',
    meta: { requiresLogin: true },
    component: () => import(/* webpackChunkName: "about" */ '../views/user/Index.vue'),
    children: [
      {
        path: 'dashboard',
        name: 'dashboard',
        meta: {title: 'User Dashboard'},
        components: {
          dashboard: Dashboard
        }
      },
      {
        path: 'users',
        name: 'users',
        meta: {title: 'Renta Car Users'},
        components: {
          users: Users
        }
      },
      {
        path: 'brands',
        name: 'brands',
        meta: {title: 'Brands - Renta Car'},
        components: {
          brands: Brand
        }
      },
      {
        path: 'driver',
        name: 'driver',
        meta: {title: 'Drivers - Renta Car'},
        components: {
          driver: Driver
        }
      },
      {
        path: 'cars',
        name: 'cars',
        meta: {title: 'Cars - Renta Car'},
        components: {
          cars: Cars
        }
      },
      {
        path: 'settings',
        name: 'settings',
        meta: {title: 'Account Settings'},
        components: {
          settings: Settings
        }
      },
      {
        path: 'branch',
        name: 'branch',
        meta: {title: 'Branch Settings'},
        components: {
          branch: Branch
        }
      },
      {
        path: 'rentals',
        name: 'rentals',
        meta: {title: 'Rentals - Renta Car'},
        components: {
          rentals: Rentals
        }
      },
      {
        path: 'inquiries',
        name: 'inquiries',
        meta: {title: 'Inquiries - Renta Car'},
        components: {
          inquiries: Inquiries
        }
      },
      {
        path: '/',
        redirect: 'rentals'
      }
    ]
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})


router.beforeEach(async (to, from, next) => {
  document.title = to.meta.title
  if (to.matched.some((record) => record.meta.requiresLogin) && !localStorage.getItem('auth')) {
    next({ name: 'Login' })
  }
  else if (to.matched.some((record) => record.meta.isAuth) && localStorage.getItem('auth')) {
    next({ name: 'Home' })
  }
  else {
    next();
  }
});

export default router
