import '../middleware'

import Watch from '../../components/video-play/institution/watch'
import PlayVideo from '../../components/video-play/institution/play-video'
import Responses from '../../components/responses/institution/responses'
import ResponseList from '../../components/responses/institution/responses-list'
import UploadVideo from '../../components/video-upload/institution/upload-video'
import UploadVideoInfo from '../../components/video-upload/institution/upload-video-info'
import Manage from '../../components/manage/index'
import ManageBilling from '../../components/manage/billing/billing'
import PaymentMethod from '../../components/manage/billing/show-all-payment-method'
import AddPaymentMethod from '../../components/manage/billing/add-payment-method'
import PaymentStatus from '../../components/manage/billing/payment-status'
import BillingContact from '../../components/manage/billing/add-billing-contact'
import EditBillingContact from '../../components/manage/billing/edit-billing-contact'
import ActiveUsers from '../../components/manage/user-management/active-users'
import DeActiveUsers from '../../components/manage/user-management/deactive-users'
import InstitutionProfile from '../../components/manage/institution-profile/ins-profile'
import EditInstitutionProfile from '../../components/manage/institution-profile/edit-ins-profile'
import PrepareBill from '../../components/manage/user-list-estimated-bill/prepare-bill'
import Profile from '../../components/profile/institution/index'
import ViewInvoiceHistory from '../../components/manage/billing/view-invoice-history'

export default [
    {
        path: '/institution/watch',
        component: Watch,
        beforeEnter: InstitutionAuth,
    },
    {
        path: '/institution/watch/play/:id',
        component: PlayVideo,
        beforeEnter: InstitutionAuth,
    },
    {
        path: '/institution/responses',
        component: Responses,
        beforeEnter: InstitutionAuth,
    },
    {
        path: '/institution/responses/:id/:type',
        component: ResponseList,
        beforeEnter: InstitutionAuth,
    },
    {
        path: '/institution/video/upload',
        component: UploadVideo,
        beforeEnter: InstitutionAuth,
    },
    {
        path: '/institution/video/upload/info',
        component: UploadVideoInfo,
        beforeEnter: InstitutionAuth,
    },
    {
        path: '/institution/manage',
        component: Manage,
        beforeEnter: InstitutionAuth,
    },
    {
        path: '/institution/manage/billing',
        component: ManageBilling,
        beforeEnter: InstitutionAuth,
    },
    {
        path: '/institution/manage/payment-method',
        component: PaymentMethod,
        beforeEnter: InstitutionAuth,
    },
    {
        path: '/institution/manage/add-payment-method',
        component: AddPaymentMethod,
        beforeEnter: InstitutionAuth,
    },
    {
        path: '/institution/manage/payment-status',
        component: PaymentStatus,
        beforeEnter: InstitutionAuth,
    },
    {
        path: '/institution/manage/billing-contact',
        component: BillingContact,
        beforeEnter: InstitutionAuth,
    },
    {
        path: '/institution/manage/billing-contact/:id/edit',
        component: EditBillingContact,
        beforeEnter: InstitutionAuth,
    },
    {
        path: '/institution/manage/active-users',
        component: ActiveUsers,
        beforeEnter: InstitutionAuth,
    },
    {
        path: '/institution/manage/deactive-users',
        component: DeActiveUsers,
        beforeEnter: InstitutionAuth,
    },
    {
        path: '/institution/manage/institution-profile',
        component: InstitutionProfile,
        beforeEnter: InstitutionAuth,
    },
    {
        path: '/institution/manage/institution-profile/edit',
        component: EditInstitutionProfile,
        beforeEnter: InstitutionAuth,
    },
    {
        path: '/institution/manage/user-list-estimated-bill',
        component: PrepareBill,
        beforeEnter: InstitutionAuth,
    },
    {
        path: '/institution/profile',
        component: Profile,
        beforeEnter: InstitutionAuth,
    },
    {
        path: '/institution/manage/invoice-history',
        component: ViewInvoiceHistory,
        beforeEnter: InstitutionAuth,
    },
]
