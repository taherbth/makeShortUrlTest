import '../middleware'

import Watch from '../../components/video-play/student/watch'
import PlayVideo from '../../components/video-play/student/play-video'
import Responses from '../../components/responses/student/responses'
import Profile from '../../components/profile/student/index'

export default [
    {
        path: '/watch',
        component: Watch,
        beforeEnter: StudentAuth,
    },
    {
        path: '/watch/play/:id',
        component: PlayVideo,
        beforeEnter: StudentAuth,
    },
    {
        path: '/responses',
        component: Responses,
        beforeEnter: StudentAuth,
    },
    {
        path: '/profile',
        component: Profile,
        beforeEnter: StudentAuth,
    },

]
