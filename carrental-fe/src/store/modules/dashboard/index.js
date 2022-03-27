import API from '../../base/'

export default {
 namespaced: true,
 state: {
  rentals: 0,
  users: 0,
  branch: 0,
  inquiry: 0,
  brands: 0,
  cars: 0,
  latest_transactions: [],
  archivedDrivers: []
 },
 getters: {
 },
 mutations: {
  SET_DATA(state, payload) {
   state.rentals = payload.rents
   state.brands = payload.brand
   state.users = payload.users
   state.branch = payload.branch
   state.inquiry = payload.inquiry
   state.cars = payload.cars
   state.latest_transactions = payload.latest_transactions
  },
 },
 actions: {
  async getData({ commit }) {
   const res = await API.get(`dashboard?token=${ localStorage.getItem('auth') }`).then(res => {
    commit('SET_DATA', res.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },

 },

}