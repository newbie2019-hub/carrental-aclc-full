import API from '../../base/'

export default {
 namespaced: true,
 state: {
  inquiries: [],
  rentals: [],
  archivedRentals: [],
 },
 getters: {
 },
 mutations: {
  SET_RENTALS(state, payload) {
   state.rentals = payload
  },
  SET_ARCHIVED_RENTALS(state, payload) {
   state.archivedRentals = payload
  },
  REMOVE_RENTAL(state, payload) {
   state.archivedRentals.unshift(payload)

   state.rentals.map((rental, i) => {
    if (rental.id == payload.id) {
     state.rentals.splice(i, 1)
    }
   })
  },
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
  async getArchivedRentals({ commit }, payload) {
   const res = await API.get('archived-rentals', payload).then(res => {
    commit('SET_ARCHIVED_RENTALS', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async archiveRental({ commit }, payload) {
   const res = await API.delete(`rentals/${ payload.id }`, payload).then(res => {
    commit('REMOVE_RENTAL', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async createPayment({ commit }, payload) {
   const res = await API.post(`payment`, payload).then(res => {
    // commit('CREATE_PAYMENT', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
 },

}