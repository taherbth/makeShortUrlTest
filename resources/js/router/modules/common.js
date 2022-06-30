
import Home from '../../components/dashboard/dashboard';
import RedirectUrl from '../../components/dashboard/RedirectUrl';

export default [
    {
        path: '/',
        component: Home,
    },
    {
        path: '/:short_code',
        component: RedirectUrl,
    },
    
]

//tinyurl.com/mr3zf7e9