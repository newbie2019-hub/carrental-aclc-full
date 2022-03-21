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
  async sendMessage({ commit }, payload) {
   const res = await API.post('inquiry', payload).then(res => {
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async deleteInquiry({ commit }, payload) {
   const res = await API.delete(`inquiry/${payload.id}`, payload).then(res => {
    commit('DELETE_INQUIRY', payload)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async getInquiries({ commit }, payload) {
   const res = await API.get('inquiry', payload).then(res => {
    commit('SET_INQUIRIES', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
 },

}