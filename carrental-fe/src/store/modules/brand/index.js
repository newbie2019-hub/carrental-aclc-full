import API from '../../base/'

export default {
 namespaced: true,
 state: {
  brands: [],
  archivedBrands: []
 },
 getters: {
  GET_USERS(state) {
   return state.users;
  }
 },
 mutations: {
  ADD_BRAND(state, payload) {
   state.brands.unshift(payload)
  },
  UPDATE_BRAND(state, payload) {
   state.brands.map((brand, i) => {
    if(brand.id == payload.id) {
     state.brands[i].brand = payload.brand
     state.brands[i].logo = payload.logo
    }
   })
  },
  SET_BRANDS(state, payload) {
   state.brands = payload
  },
  SET_ARCHIVED_BRANDS(state, payload) {
   state.archivedBrands = payload
  },
  PERMANENT_REMOVE_BRAND(state, payload) {
   state.archivedBrands.map((brand, i) => {
    if (brand.id == payload.id) {
     state.archivedBrands.splice(i, 1)
    }
   })
  },
  REMOVE_BRAND(state, payload) {
   state.archivedBrands.unshift(payload)

   state.brands.map((brand, i) => {
    if (brand.id == payload.id) {
     state.brands.splice(i, 1)
    }
   })
  },
  RESTORE_BRAND(state, payload) {
   state.brands.unshift(payload)

   state.archivedBrands.map((brand, i) => {
    if (brand.id == payload.id) {
     state.archivedBrands.splice(i, 1)
    }
   })
  }
 },
 actions: {
  async newBrand({ commit }, payload) {
   const res = await API.post(`brands?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('ADD_BRAND', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async updateBrand({ commit }, payload) {
   const res = await API.put(`brands/${payload.id}`, payload).then(res => {
    commit('UPDATE_BRAND', payload)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async getBrands({ commit }, payload) {
   const res = await API.get(`brands?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('SET_BRANDS', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async getArchivedBrands({ commit }, payload) {
   const res = await API.get(`archived-brands?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('SET_ARCHIVED_BRANDS', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async archiveBrand({ commit }, payload) {
   const res = await API.delete(`brands/${ payload.id }`, payload).then(res => {
    commit('REMOVE_BRAND', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async deleteBrand({ commit }, payload) {
   const res = await API.delete(`archived-brands/${ payload.id }`, payload).then(res => {
    commit('PERMANENT_REMOVE_BRAND', payload)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async restoreBrand({ commit }, payload) {
   const res = await API.put(`archived-brands/${ payload.id }`).then(res => {
    commit('RESTORE_BRAND', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
 },

}