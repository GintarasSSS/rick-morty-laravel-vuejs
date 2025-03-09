import './bootstrap';
import { createApp } from 'vue';
import { createBootstrap } from "bootstrap-vue-next";
import App from './App.vue';

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue-next/dist/bootstrap-vue-next.css';

import router from './router/index.js';
import store from './store/index.js';

createApp(App)
    .use(router)
    .use(store)
    .use(createBootstrap())
    .mount('#app');
