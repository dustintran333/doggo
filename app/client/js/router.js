import Vue from 'vue';
import Router from 'vue-router';
import Home from './views/Home.vue';
import Park from './views/Park.vue';

Vue.use(Router);

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home,
    },
    {
      path: '/park/:id',
      name: 'park',
      component: Park,
    },
  ],
});
