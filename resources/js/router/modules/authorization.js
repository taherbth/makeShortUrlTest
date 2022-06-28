import '../middleware'

import AuthLayout from '../../components/auth/auth-layout';
import Login from '../../components/auth/login';
import Registation from '../../components/auth/registation';
import ForgetPassword from '../../components/auth/forget-password';


export default [{
    path: '/auth',
    component: AuthLayout,
    beforeEnter: Guest,
    children: [{
            path: 'login',
            component: Login
        },
        {
            path: 'registation',
            component: Registation
        },
        {
            path: 'forget-password',
            component: ForgetPassword
        }
    ]
}]
