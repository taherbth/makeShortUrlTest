import Vue from 'vue'
import axios from 'axios'

axios.defaults.baseURL = process.env.MIX_API_URL

axios.defaults.headers.common['Content-Type'] = 'multipart/form-data'
axios.defaults.headers.common['manifest'] = 'manifest.json'
if(JSON.parse(localStorage.getItem('auth_info')) && JSON.parse(localStorage.getItem('auth_info')).token && JSON.parse(localStorage.getItem('auth_info')).token != '' && JSON.parse(localStorage.getItem('auth_info')).token != null){
    axios.defaults.headers.common['Authorization'] = "Bearer " + JSON.parse(localStorage.getItem('auth_info')).token
}else{
    axios.defaults.headers.common['Authorization'] = null
}
// axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';

