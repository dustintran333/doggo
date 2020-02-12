import Vue from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';

Vue.config.productionTip = false;

const body = document.getElementsByTagName('body')[0];
Vue.prototype.$appSettings = JSON.parse(body.getAttribute('data-app-settings'));

new Vue({
  el: '#app',
  render: h => h(App),
  router,
  store,
});
