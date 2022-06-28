export default {
    state: {
        video_info: {
            prepare_video: null
        }
    },
    getters: {
        preparedVideo: function (state){
            return state.video_info.prepare_video
        }
    },
    mutations: {
        setparedVideo: function (state, data){
            state.video_info.prepare_video = data
        },
        resetparedVideo: function (state){
            state.video_info.prepare_video = null
        }
    },
    actions: {
        setparedVideo: function ({commit}, data){
            commit('setparedVideo', data)
        },
        resetparedVideo: function ({commit}){
            commit('resetparedVideo')
        }
    }
}
