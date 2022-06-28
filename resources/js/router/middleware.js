Guest = function (to, from, next){
    if (!localStorage.getItem('auth_info') || !JSON.parse(localStorage.getItem('auth_info')) || !JSON.parse(localStorage.getItem('auth_info')).token || JSON.parse(localStorage.getItem('auth_info')).token == null || JSON.parse(localStorage.getItem('auth_info')).token == '' || axios.defaults.headers.common['Authorization'] == null || axios.defaults.headers.common['Authorization'] == ''){
        next()
    }else{
        next('dashboard')
    }
}

Auth = function (to, from, next){
    if (localStorage.getItem('auth_info') && JSON.parse(localStorage.getItem('auth_info')) && JSON.parse(localStorage.getItem('auth_info')).token && JSON.parse(localStorage.getItem('auth_info')).token != null && JSON.parse(localStorage.getItem('auth_info')).token != '' && axios.defaults.headers.common['Authorization'] != null && axios.defaults.headers.common['Authorization'] != ''){
        next()
    }else{
        axios.defaults.headers.common['Authorization'] = null
        localStorage.removeItem('auth_info')
        next('/auth/login')
    }
}

FacilitatorAuth = function (to, from, next){
    if (localStorage.getItem('auth_info') && JSON.parse(localStorage.getItem('auth_info')) && JSON.parse(localStorage.getItem('auth_info')).token && JSON.parse(localStorage.getItem('auth_info')).token != null && JSON.parse(localStorage.getItem('auth_info')).token != '' && axios.defaults.headers.common['Authorization'] != null && axios.defaults.headers.common['Authorization'] != '' && JSON.parse(localStorage.getItem('auth_info')).basicInfo.role === 1){
        next()
    }else{
        axios.defaults.headers.common['Authorization'] = null
        localStorage.removeItem('auth_info')
        next('/auth/login')
    }
}

StudentAuth = function (to, from, next){
    if (localStorage.getItem('auth_info') && JSON.parse(localStorage.getItem('auth_info')) && JSON.parse(localStorage.getItem('auth_info')).token && JSON.parse(localStorage.getItem('auth_info')).token != null && JSON.parse(localStorage.getItem('auth_info')).token != '' && axios.defaults.headers.common['Authorization'] != null && axios.defaults.headers.common['Authorization'] != '' && JSON.parse(localStorage.getItem('auth_info')).basicInfo.role === 2){
        next()
    }else{
        axios.defaults.headers.common['Authorization'] = null
        localStorage.removeItem('auth_info')
        next('/auth/login')
    }
}

InstitutionAuth = function (to, from, next){
    if (localStorage.getItem('auth_info') && JSON.parse(localStorage.getItem('auth_info')) && JSON.parse(localStorage.getItem('auth_info')).token && JSON.parse(localStorage.getItem('auth_info')).token != null && JSON.parse(localStorage.getItem('auth_info')).token != '' && axios.defaults.headers.common['Authorization'] != null && axios.defaults.headers.common['Authorization'] != '' && JSON.parse(localStorage.getItem('auth_info')).basicInfo.role === 3){
        next()
    }else{
        axios.defaults.headers.common['Authorization'] = null
        localStorage.removeItem('auth_info')
        next('/auth/login')
    }
}

AuthorityAuth = function (to, from, next){
    if (localStorage.getItem('auth_info') && JSON.parse(localStorage.getItem('auth_info')) && JSON.parse(localStorage.getItem('auth_info')).token && JSON.parse(localStorage.getItem('auth_info')).token != null && JSON.parse(localStorage.getItem('auth_info')).token != '' && axios.defaults.headers.common['Authorization'] != null && axios.defaults.headers.common['Authorization'] != '' && JSON.parse(localStorage.getItem('auth_info')).basicInfo.role === 4){
        next()
    }else{
        axios.defaults.headers.common['Authorization'] = null
        localStorage.removeItem('auth_info')
        next('/auth/login')
    }
}
