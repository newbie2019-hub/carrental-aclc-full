import API from '../../base/'

export default {
 namespaced: true,
 state: {
  cars: [],
  archivedCars: [],
  brand: [],
  archivedBrand: []
 },
 getters: {
  GET_USERS(state) {
   return state.users;
  }
 },
 mutations: {
  ADD_CAR(state, payload) {
   state.cars.unshift(payload)
  },
  UPDATE_CAR(state, payload) {
   state.cars.map((car, i) => {
    if(car.id == payload.id) {
     state.car[i].model = payload.model
     state.car[i].plate_number = payload.plate_number
     state.car[i].vehicle_identification_number = payload.vehicle_identification_number,
     state.car[i].description = payload.description,
     state.car[i].remarks = payload.remarks,
     state.car[i].mileage = payload.mileage,
     state.car[i].model = payload.model,
     state.car[i].year = payload.year,
     state.car[i].transmission = payload.transmission
     state.car[i].quantity = payload.quantity
    }
   })
  },
  SET_CARS(state, payload) {
   state.cars = payload
  },
  SET_ARCHIVED_CARS(state, payload) {
   state.archivedCars = payload
  },
  PERMANENT_REMOVE_CAR(state, payload) {
   state.archivedCars.map((car, i) => {
    if (car.id == payload.id) {
     state.archivedCars.splice(i, 1)
    }
   })
  },
  REMOVE_CAR(state, payload) {
   state.archivedCars.unshift(payload)

   state.cars.map((car, i) => {
    if (car.id == payload.id) {
     state.cars.splice(i, 1)
    }
   })
  },
  RESTORE_CAR(state, payload) {
   state.cars.unshift(payload)

   state.archivedCars.map((car, i) => {
    if (car.id == payload.id) {
     state.archivedCars.splice(i, 1)
    }
   })
  }
 },
 actions: {
  async newCar({ commit }, payload) {
   const res = await API.post(`cars?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('ADD_CAR', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async updateCar({ commit }, payload) {
   const res = await API.put(`cars/${payload.id}`, payload).then(res => {
    commit('UPDATE_CAR', payload)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async getCars({ commit }, payload) {
   const res = await API.get(`cars?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('SET_CARS', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async getArchivedCars({ commit }, payload) {
   const res = await API.get(`archived-cars?token=${ localStorage.getItem('auth') }`, payload).then(res => {
    commit('SET_ARCHIVED_CARS', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async archiveCar({ commit }, payload) {
   const res = await API.delete(`cars/${ payload.id }`, payload).then(res => {
    commit('REMOVE_CAR', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async deleteCar({ commit }, payload) {
   const res = await API.delete(`archived-cars/${ payload.id }`, payload).then(res => {
    commit('PERMANENT_REMOVE_CAR', payload)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
  async restoreCar({ commit }, payload) {
   const res = await API.put(`archived-cars/${ payload.id }`).then(res => {
    commit('RESTORE_CAR', res.data.data)
    return res;
   }).catch(err => {
    return err.response;
   })

   return res;
  },
 },

}