import API from '../../base/'

export default {
 namespaced: true,
 state: {
  inquiries: []
 },
 getters: {
  GET_INQUIRY(state) {
   return state.inquiries;
  }
 },
 mutations: {
  DELETE_INQUIRY(state, payload) {
   state.inquiries.map((inquiry, i) => {
    if(inquiry.id == payload.id) {
     state.inquiries.splice(i, 1)
    }
   })
  },
  SET_INQUIRIES(state, payload) {
   state.inquiries = payload
  }
 },
 actions: {
  async create({ commit }, payload) {
   const res = await API.post('rentals', payload).then(res => {
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async getRentals({ commit }, payload) {
   const res = await API.get('rentals', payload).then(res => {
    commit('SET_RENTALS', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
 },

}