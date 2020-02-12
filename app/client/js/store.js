import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

console.log(process.env);


export default new Vuex.Store({
  state: {
    error: false,
    parks: [],
  },
  mutations: {
    clearError(state) {
      state.error = false;
    },
    setError(state, payload) {
      state.error = payload;
    },
    updateParks(state, payload) {
      state.parks = payload;
    },
  },
  actions: {
    fetchParks({ commit }) {
      return new Promise((resolve, reject) => {
        axios.get(`api/v1/Doggo-Model-Park.json`)
          .then((response) => {
            commit('updateParks', response.data.items);
            commit('clearError');
            resolve();
          })
          .catch((error) => {
            commit('updateParks', []);
            commit('setError', error.toString());
            reject();
          });
      });
    },
  },
});
