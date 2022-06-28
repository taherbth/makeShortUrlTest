import PageNotFound from '../components/layouts/page-not-found.vue';
import Authorization from './modules/authorization';
import Student from './modules/student';
import Facilitator from './modules/facilitator';
import Institution from './modules/institution';
import Authority from './modules/authority';
import Common from './modules/common';

const RouteList = (...route) => {
    return route.flat()
}

const PageNotFound404 = [{ path: "*", component: PageNotFound }]
export default RouteList(Authorization, Student, Facilitator, Institution, Authority, Common, PageNotFound404)
