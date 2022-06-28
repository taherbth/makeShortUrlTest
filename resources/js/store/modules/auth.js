export default {
    state: {
        guest: {
            name: null,
            email: null,
            institution_url: null,
            number_of_student: null,
            number_of_facilitor: null,
        }
    },
    getters: {
        guestInfo: (state) => {
            return state.guest
        }
    },
    mutations: {
        setGestInfo: (state, data) => {
            state.guest = data
        },
        removeGestInfo: (state) => {
            state.guest = {}
        }
    },
    actions: {
        setGestInfo: ({commit}, data) => {
            commit('setGestInfo', data)
        },
        deleteGestInfo: ({commit}) => {
            commit('removeGestInfo')
        }
    }
}
