import API from '../../base/'

export default {
 namespaced: true,
 state: {
  branch: [],
  archivedBranch: [],
 },
 getters: {
  GET_USERS(state) {
   return state.users;
  }
 },
 mutations: {
  ADD_BRANCH(state, payload) {
   state.branch.unshift(payload)
  },
  UPDATE_BRANCH(state, payload) {
   state.branch.map((branch, i) => {
    if(branch.id == payload.id) {
     state.branch[i].branch = payload.branch
     state.branch[i].address = payload.address
     state.branch[i].description = payload.description
    }
   })
  },
  SET_BRANCH(state, payload) {
   state.branch = payload
  },
  SET_ARCHIVED_BRANCH(state, payload) {
   state.archivedBranch = payload
  },
  PERMANENT_REMOVE_BRANCH(state, payload) {
   state.archivedBranch.map((user, i) => {
    if (user.id == payload.id) {
     state.archivedBranch.splice(i, 1)
    }
   })
  },
  REMOVE_BRANCH(state, payload) {
   state.archivedBranch.push(payload)

   state.branch.map((user, i) => {
    if (user.id == payload.id) {
     state.branch.splice(i, 1)
    }
   })
  },
  RESTORE_BRANCH(state, payload) {
   state.branch.unshift(payload)

   state.archivedBranch.map((user, i) => {
    if (user.id == payload.id) {
     state.archivedBranch.splice(i, 1)
    }
   })
  }
 },
 actions: {
  async newBranch({ commit }, payload) {
   const res = await API.post(`branch?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('ADD_BRANCH', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async updateBranch({ commit }, payload) {
   const res = await API.put(`branch/${payload.id}`, payload).then(res => {
    commit('UPDATE_BRANCH', payload)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async getBranch({ commit }, payload) {
   const res = await API.get(`branch?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('SET_BRANCH', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async getArchivedBranch({ commit }, payload) {
   const res = await API.get(`archived-branch?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('SET_ARCHIVED_BRANCH', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async archiveBranch({ commit }, payload) {
   const res = await API.delete(`branch/${ payload.id }`, payload).then(res => {
    commit('REMOVE_BRANCH', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async deleteBranch({ commit }, payload) {
   const res = await API.delete(`archived-branch/${ payload.id }`, payload).then(res => {
    commit('PERMANENT_REMOVE_BRANCH', payload)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async restoreBranch({ commit }, payload) {
   const res = await API.put(`archived-branch/${ payload.id }`).then(res => {
    commit('RESTORE_BRANCH', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
 },

}