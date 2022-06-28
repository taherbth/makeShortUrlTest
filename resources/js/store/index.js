import Vue from 'vue'
import Vuex from 'vuex'

import Auth from "./modules/auth"
import VideoInfo from "./modules/video_info"

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        Auth,
        VideoInfo,
    }
})
