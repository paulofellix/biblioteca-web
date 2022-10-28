import './bootstrap';
import '../sass/app.scss'
import Router from '@/router'
import store from '@/store/index'
import 'font-awesome/css/font-awesome.min.css'
import { vue3Debounce } from 'vue-debounce'

import { createApp } from 'vue/dist/vue.esm-bundler';
import emitter from './emitter';
import VueTheMask from 'vue-the-mask'


const app = createApp({})
app.directive('debounce', vue3Debounce({}))
app.use(VueTheMask)
app.use(Router)
app.use(store)
app.use(emitter)
app.mount('#app')