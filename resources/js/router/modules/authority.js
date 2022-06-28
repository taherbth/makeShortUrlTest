import '../middleware'
import Institution from '../../components/aut-institution/index';
import InstitutionDetails from '../../components/aut-institution/ins-details';
import Pricing from '../../components/pricing/index';
import EditPricing from '../../components/pricing/edit-pricing';
import Profile from '../../components/profile/authority/index'
import Frontend from '../../components/frontend/authority/index'
import UploadVideo from '../../components/video-upload/authority/upload-video'
import UploadVideoInfo from '../../components/video-upload/authority/upload-video-info'
import PlayVideo from '../../components/video-play/authority/play-video'


export default [
    {
        path: '/authority/institution',
        component: Institution,
        beforeEnter: AuthorityAuth,
    },
    {
        path: '/authority/institution/:id',
        component: InstitutionDetails,
        beforeEnter: AuthorityAuth,
    },
    {
        path: '/authority/pricing',
        component: Pricing,
        beforeEnter: AuthorityAuth,
    },
    {
        path: '/authority/pricing/:id',
        component: EditPricing,
        beforeEnter: AuthorityAuth,
    },
    {
        path: '/authority/frontend',
        component: Frontend,
        beforeEnter: AuthorityAuth,
    },
    {
        path: '/authority/profile',
        component: Profile,
        beforeEnter: AuthorityAuth,
    },
    {
        path: '/authority/video/upload',
        component: UploadVideo,
        beforeEnter: AuthorityAuth,
    },
    {
        path: '/authority/video/upload/info',
        component: UploadVideoInfo,
        beforeEnter: AuthorityAuth,
    },
    {
        path: '/authority/watch/play/:id',
        component: PlayVideo,
        beforeEnter: AuthorityAuth,
    },
]
