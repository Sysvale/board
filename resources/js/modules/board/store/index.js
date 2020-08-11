import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import userStories from './userStories';

export default new Vuex.Store({
  namespaced: true,
  modules: {
    userStories,
  }
});
