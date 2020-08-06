/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router';
import vuetify from './vuetify'
import routes from './modules/routes';
import App from './App.vue';

import { filterFormatDate } from './core/utils/filters';

Vue.filter('filterFormatDate', filterFormatDate);

Vue.use(VueRouter);

// Routes
const router = new VueRouter({
	routes,
});


const app = new Vue({
  render: h => h(App),
  vuetify,
  router,
}).$mount("#app");

console.log(app);
