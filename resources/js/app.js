import './bootstrap';
import {createApp} from 'vue/dist/vue.esm-bundler.js';
import Index from "./components/Index.vue";

const app = createApp({});

app.component('index', Index)

app.mount('#app')
