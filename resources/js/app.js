require('./bootstrap');
require('./axios')

import {createApp} from 'vue'
import App from './Vue/App.vue'
import router from './router'
import mitt from 'mitt'
const emitter = mitt()

const app = createApp(App)
app.config.globalProperties.emitter = emitter
app.use(router)
app.mount('#app')
