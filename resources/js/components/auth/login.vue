<template lang="">
    <div class="wam-signup-header">
        <h2 class="text-uppercase font-weight-bold">Login to <br> Get Started</h2>
        <p class="text-neutral">Enter your email address and password below</p>

        <form class="mb-5" @submit.prevent="tryToLogin">

            <vInput name="email" :required="true" placeHolder="Enter Email" title="Your email address" :errorMsg="errors.email" :toolTipOptions="{content:'Please write your valid email address..' }"/>

            <div class="form-group position-relative">
                <label class="text-uppercase">Your Password <span class="text-danger">*</span></label>
                <input class="form-control" id="password" type="password" name="password" required minlength="8" placeHolder="Enter Password">
                <span toggle="#password" class="fa fa-fw fa-eye field_icon toggle-password"></span>
                <small class="text-danger" v-if="errors.pasword">{{ errors.pasword[0] }}</small>
            </div>

            <button class="btn btn-primary-text rounded btn-block" type="submit">Log In</button>
            <div class="row">
                <div class="col-6">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember-me" name="remember_me" @change="rememberMe = !rememberMe" :value="rememberMe"/>
                        <label class="form-check-label" for="remember-me">Remember me</label>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <router-link to="forget-password" class="text-secondary border-bottom" >Forget Password?</router-link>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
    import {vInput} from "../wam-partial/form-lib"
    export default {
        data() {
            return {
                rememberMe: false,
                errors: {},
            }
        },
        components:{
            vInput
        },
        methods:{
            tryToLogin : function(el){
                let data = this.FilterForm(el.target, 'formdata')

                axios.post('/api/login', data).then(response => {
                    if(response.data.facilitator){ var data = response.data.facilitator; var role = 1 }
                    else if (response.data.student){ var data = response.data.student; var role = 2 }
                    else if (response.data.institution){ var data = response.data.institution; var role = 3 }
                    else if(response.data.Authority){ var data = response.data.Authority; var role = 4}

                    // Start Store it into localstorage
                    // Preparing Data
                    let basicInfo = {id: data.id, email: data.email, role}
                    if (role === 3) { basicInfo.institution_name = data.institution_name || null }
                    else if (role === 1 || role === 2) { basicInfo.institution_name = response.data.institution_name || null }

                    if(role === 3){
                        basicInfo.name = data.admin_name || ""
                        basicInfo.profile_picture = data.admin_profile_picture
                    }else if(role === 1 || role === 2 || role === 4){
                        basicInfo.name = data.name || ""
                        basicInfo.profile_picture = data.profile_picture
                    }
                    // Storing
                    localStorage.setItem('auth_info', JSON.stringify({
                        basicInfo,
                        token: response.data.token
                    }))
                    // End Store it into localstorage

                    axios.defaults.headers.common['Authorization'] = "Bearer " + response.data.token
                    this.$router.push('/dashboard')
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
        },
        mounted() {
            this.$emit('setLayout', {
                signTitle : 'Not a member yet? Register now',
                signText : 'Sign Up',
                signAddr : '/auth/registation'
            })
        },
    }
</script>
<style scoped>

</style>
