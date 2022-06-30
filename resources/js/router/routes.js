import PageNotFound from '../components/layouts/page-not-found.vue';
import Common from './modules/common';

const RouteList = (...route) => {
    return route.flat()
}

const PageNotFound404 = [{ path: "*", component: PageNotFound }]
export default RouteList(Common, PageNotFound404)
