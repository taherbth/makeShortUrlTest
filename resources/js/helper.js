import Vue from 'vue'

Vue.mixin({
    methods: {
        ValidtaeForm: function (err) {
            if (err.response.status === 422 && err.response.data.s_status) {
                this.errors = err.response.data
            }else if (err.response.status === 422) {
                this.errors = err.response.data.errors
            }else if (err.response.status === 401){
                axios.defaults.headers.common['Authorization'] = null
                this.$router.push('/auth/login')
            }else if (err.response.status === 410){
                this.$router.push('/profile-lock')
            }else if (err.response.status === 404){
                this.$swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Page not found !!',
                })
            }else{
                this.$swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: err.response.data.message,
                })
            }
        },
        startTimer: function () {
            var self = this
            var duration = self.countDown
            var timer = duration, minutes, seconds
            var startCountDown = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                self.countDown = minutes + ":" + seconds;
                if (--timer < 0) {
                    clearInterval(startCountDown)
                    self.countDownStatus = false
                }
            }, 1000);
        },
        FilterForm: function (data, type = 'object') {

            if (type == 'formdata') {
                return new FormData(data)
            }else if (type == 'object') {
                var response = []
                for (let index = 0; index < data.length; index++) {
                    if (data[index].getAttribute('name') != null && data[index].getAttribute('name') != '') {
                        response[data[index].getAttribute('name')] = data[index].value
                    }
                }
                return response
            }
        },
        getRandomInt: function (min, max) {
            min = Math.ceil(min);
            max = Math.floor(max);
            return Math.floor(Math.random() * (max - min) + min); //The maximum is exclusive and the minimum is inclusive
        },
        ClearAllForm: function () {
            for (let index = 0; index < document.getElementsByTagName('form').length; index++) {
                document.getElementsByTagName('form')[index].reset()
            }
        },
        LocalLogout: function (){
            axios.defaults.headers.common['Authorization'] = null
            localStorage.removeItem('auth_info')
            this.$router.push('/auth/login')
        },
        labelTextRes: function(txt){
            if(window.innerWidth <= 500){
                return txt.length <= 13 ? txt : `${txt.slice(0, 11)}...`
            }else if(window.innerWidth >= 1140 && window.innerWidth <= 1320){
                return txt.length <= 18 ? txt : `${txt.slice(0, 15)}...`
            }else{
                return txt
            }
        },
        GetNotificationStatus: function (){
            if(this.User.role === 1){
                var api_url = '/api/facilitator/get_notifications_status'
            }else if(this.User.role === 2){
                var api_url = '/api/student/get_notifications_status'
            }else if(this.User.role === 3){
                var api_url = '/api/institution/get_notifications_status'
            }else if(this.User.role === 4){
                var api_url = '/api/authority/get_notifications_status'
            }

            axios.get(api_url).then((res) => {
                this.notificationStatus = res.data
            }).catch((err) => {
                this.ValidtaeForm(err)
            })
        },
        ConfigureUserData: function (){
            if(localStorage.getItem('auth_info') && JSON.parse(localStorage.getItem('auth_info')).basicInfo && JSON.parse(localStorage.getItem('auth_info')).basicInfo.id != null ){
                this.User = JSON.parse(localStorage.getItem('auth_info')).basicInfo
                if(window.innerWidth < 600){
                    return ''
                }else if(window.innerWidth >= 1140 && window.innerWidth <= 1300){
                    this.User.dynamicName = this.User.name.length <= 13 ? this.User.name : `${this.User.name.slice(0, 10)}...`
                }
                else{
                    this.User.dynamicName = this.User.name
                }
            }
        },
        ChangeLocalStorageData: function(data, props){
            let existing_data = JSON.parse(localStorage.getItem('auth_info'))
            for (let index = 0; index < props.length; index++) {
                if (props[index] === "name") {
                    existing_data.basicInfo.name = data.name
                } else if(props[index] === "email") {
                    existing_data.basicInfo.email = data.email
                } else if(props[index] === "profile_picture") {
                    existing_data.basicInfo.profile_picture = data.profile_picture
                } else if(props[index] === "institution_name") {
                    existing_data.basicInfo.institution_name = data.institution_name
                }
            }
            localStorage.setItem('auth_info', JSON.stringify(existing_data))
            this.ConfigureUserData()
        }
    },
    data() {
        return {
            User: {},
            notificationStatus: null
        }
    },
    mounted() {
        this.ConfigureUserData()
    },
})
