import API from '../../base/'

export default {
 namespaced: true,
 state: {
  users: [],
  archivedUsers: [],
 },
 getters: {
  GET_USERS(state) {
   return state.users;
  }
 },
 mutations: {
  SET_USERS(state, payload) {
   state.users = payload
  },
  SET_ARCHIVED_USERS(state, payload) {
   state.archivedUsers = payload
  },
  PERMANENT_REMOVE_USER(state, payload) {
   state.archivedUsers.map((user, i) => {
    if (user.id == payload.id) {
     state.archivedUsers.splice(i, 1)
    }
   })
  },
  REMOVE_USER(state, payload) {
   state.archivedUsers.push(payload)

   state.users.map((user, i) => {
    if (user.id == payload.id) {
     state.users.splice(i, 1)
    }
   })
  },
  RESTORE_USER(state, payload) {
   state.users.push(payload)

   state.archivedUsers.map((user, i) => {
    if (user.id == payload.id) {
     state.archivedUsers.splice(i, 1)
    }
   })
  }
 },
 actions: {
  async getUsers({ commit }, payload) {
   const res = await API.get(`users?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('SET_USERS', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async getArchivedUsers({ commit }, payload) {
   const res = await API.get(`archived-users?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('SET_ARCHIVED_USERS', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async deleteUser({ commit }, payload) {
   const res = await API.delete(`archived-users/${ payload.id }`, payload).then(res => {
    commit('PERMANENT_REMOVE_USER', payload)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async archiveUser({ commit }, payload) {
   const res = await API.delete(`users/${ payload.id }`, payload).then(res => {
    commit('REMOVE_USER', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async restoreUser({ commit }, payload) {
   const res = await API.put(`archived-users/${ payload.id }`).then(res => {
    commit('RESTORE_USER', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
 },

}