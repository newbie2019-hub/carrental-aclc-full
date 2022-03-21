import Vue from 'vue'
import Vuex from 'vuex'

import alert from './modules/alert'
import auth from './modules/auth'
import users from './modules/users'
import home from './modules/home'
import brands from './modules/brand'
import dashboard from './modules/dashboard'
import cars from './modules/cars'
import branch from './modules/branch'
import inquiry from './modules/inquiry'
import rentals from './modules/rentals'

Vue.use(Vuex);

export default new Vuex.Store({
 modules: {
  alert, auth, cars, dashboard, inquiry, rentals, branch, users, home, brands
 }
})

