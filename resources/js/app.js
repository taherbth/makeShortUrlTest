require('./bootstrap')
// packages
import Vue from 'vue'
import router from './router'
import store from './store'

// configration modules
import './config/axios.config'

// library modules
import './library/font-awesome'
import './library/vue-tippy'
import './library/vue-sweet-alert2'
// import './library/vue-core-video-player'
import './library/vue-plyr'
import './library/ck-editor'

// helper function
import './helper'


const app = new Vue({
    router,
    store,
    el: '#app'
});
