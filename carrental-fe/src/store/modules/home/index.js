import API from '../../base/'

export default {
 namespaced: true,
 state: {
  branch: [],
  cars: [],
  brands: []
 },
 getters: {
 },
 mutations: {
  SET_DATA(state, payload) {
   state.branch = payload.branch
   state.brands = payload.brands
   state.cars = payload.cars
  }
 },
 actions: {
  async getData({ commit }, payload) {
   const res = await API.get('home', payload).then(res => {
    commit('SET_DATA', res.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
 },

}