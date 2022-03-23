import API from '../../base/'

export default {
 namespaced: true,
 state: {
  drivers: [],
  archivedDrivers: []
 },
 getters: {
 },
 mutations: {
  ADD_DRIVER(state, payload) {
   state.drivers.unshift(payload)
  },
  UPDATE_DRIVER(state, payload) {
   state.drivers.map((driver, i) => {
    if(driver.id == payload.id) {
     state.drivers[i].first_name = payload.first_name
     state.drivers[i].last_name = payload.last_name
     state.drivers[i].gender = payload.gender
     state.drivers[i].address = payload.address
    }
   })
  },
  SET_BRANDS(state, payload) {
   state.drivers = payload
  },
  SET_ARCHIVED_DRIVERS(state, payload) {
   state.archivedDrivers = payload
  },
  PERMANENT_REMOVE_DRIVER(state, payload) {
   state.archivedDrivers.map((driver, i) => {
    if (driver.id == payload.id) {
     state.archivedDrivers.splice(i, 1)
    }
   })
  },
  REMOVE_DRIVER(state, payload) {
   state.archivedDrivers.unshift(payload)

   state.drivers.map((driver, i) => {
    if (driver.id == payload.id) {
     state.drivers.splice(i, 1)
    }
   })
  },
  RESTORE_BRAND(state, payload) {
   state.drivers.unshift(payload)

   state.archivedDrivers.map((brand, i) => {
    if (brand.id == payload.id) {
     state.archivedDrivers.splice(i, 1)
    }
   })
  }
 },
 actions: {
  async newDriver({ commit }, payload) {
   const res = await API.post(`drivers?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('ADD_DRIVER', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async updateDriver({ commit }, payload) {
   const res = await API.put(`drivers/${payload.id}`, payload).then(res => {
    commit('UPDATE_DRIVER', payload)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async getDrivers({ commit }, payload) {
   const res = await API.get(`drivers?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('SET_BRANDS', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async getArchivedDrivers({ commit }, payload) {
   const res = await API.get(`archived-drivers?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('SET_ARCHIVED_DRIVERS', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async archiveDriver({ commit }, payload) {
   const res = await API.delete(`drivers/${ payload.id }`, payload).then(res => {
    commit('REMOVE_DRIVER', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async deleteDriver({ commit }, payload) {
   const res = await API.delete(`archived-drivers/${ payload.id }`, payload).then(res => {
    commit('PERMANENT_REMOVE_DRIVER', payload)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async restoreDriver({ commit }, payload) {
   const res = await API.put(`archived-drivers/${ payload.id }`).then(res => {
    commit('RESTORE_DRIVER', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
 },

}