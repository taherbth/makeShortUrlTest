import '../middleware'

import Watch from '../../components/video-play/facilitator/watch'
import PlayVideo from '../../components/video-play/facilitator/play-video'
import Responses from '../../components/responses/facilitator/responses'
import ResponseList from '../../components/responses/facilitator/responses-list'
import UploadVideo from '../../components/video-upload/facilitator/upload-video'
import UploadVideoInfo from '../../components/video-upload/facilitator/upload-video-info'
import Profile from '../../components/profile/facilitator/index'

export default [
    {
        path: '/facilitator/watch',
        component: Watch,
        beforeEnter: FacilitatorAuth,
    },
    {
        path: '/facilitator/watch/play/:id',
        component: PlayVideo,
        beforeEnter: FacilitatorAuth,
    },
    {
        path: '/facilitator/responses',
        component: Responses,
        beforeEnter: FacilitatorAuth,
    },
    {
        path: '/facilitator/responses/:id/:type',
        component: ResponseList,
        beforeEnter: FacilitatorAuth,
    },
    {
        path: '/facilitator/video/upload',
        component: UploadVideo,
        beforeEnter: FacilitatorAuth,
    },
    {
        path: '/facilitator/video/upload/info',
        component: UploadVideoInfo,
        beforeEnter: FacilitatorAuth,
    },
    {
        path: '/facilitator/profile',
        component: Profile,
        beforeEnter: FacilitatorAuth,
    },


]
