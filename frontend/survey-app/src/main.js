import { createApp } from 'vue'
import { createPinia } from 'pinia'
import './style.css'
import App from './App.vue'
import VueRouter from './router'

const pinia = createPinia()

const app = createApp(App)
app.use(pinia)
app.use(VueRouter)
app.mount('#app')
