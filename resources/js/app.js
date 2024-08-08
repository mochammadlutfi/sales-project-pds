import './bootstrap';

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './Router'
import '@/Plugins/axios';
import i18n from '@/Plugins/i18n';
const app = createApp(App)
app.use(createPinia())
app.use(router)
app.use(i18n)
app.mount('#app')