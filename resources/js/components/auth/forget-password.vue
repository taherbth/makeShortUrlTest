<template lang="">
    <div class="wam-signup-header">

        <section v-if="forgetPasswordStep === 1">
            <h1 class="font-weight-bold">Forgot <br> Password?</h1>
            <p class="text-neutral">Please verify your email before reseting password.</p>

            <form class="mb-5 mt-5" @submit.prevent="tryToSendOTP">
                <vInput name="email" :required="true" title="Your email address" placeHolder="Enter your valid email" :errorMsg="errors.email"/>
                <button class="btn btn-primary-text rounded btn-block" type="submit">Submit</button>
                <div class="text-center mt-5">
                    <router-link to="login" class="text-secondary" >Back to login</router-link>
                </div>
            </form>
        </section>

        <section v-if="forgetPasswordStep === 2">
            <h1 class="font-weight-bold">Forgot <br> Password?</h1>
            <p class="text-neutral">We’ve sent you a 6 digit code via email.</p>

            <form class="mb-5 mt-5" @submit.prevent="verifyOTP">
                <vInput type="hidden" />
                <vInput name="otp" className="form-control-lg place-holder-center text-center" :required="true" title="Insert the 6 digit code below" :errorMsg="errors.otp" placeHolder="XXXXXX" />
                <button class="btn btn-primary-text rounded btn-block" type="submit">Verify</button>
                <div class="text-center mt-5">
                    <p class="text-secondary" v-if="countDownStatus === true">You can resend code in <span>{{ countDown }}</span> Minutes</p>
                    <p class="text-secondary" v-if="countDownStatus === false">Didn’t get the code? <a href="javascript:void(0)" @click="tryToReSendOTP" class="text-primary">Resend</a> code</p>
                    <router-link to="login" class="text-secondary" >Back to login</router-link>
                </div>
            </form>
        </section>

        <section v-if="forgetPasswordStep === 3">
            <h1 class="font-weight-bold">Reset your password</h1>
            <p class="text-neutral">Please set a strong password for your account. Weak password may lead to security issues</p>

            <form class="mb-5 mt-5" @submit.prevent="resetPassword">

                <div class="form-group position-relative">
                    <label class="text-uppercase">Enter A PASSWORD <i class="text-danger">*</i></label>
                    <input id="password" class="form-control" type="password" v-model="password" @keyup="checkPasswordStrength" name="password" required minlength="8">
                    <span toggle="#password" class="fa fa-fw fa-eye field_icon toggle-password"></span>
                    <small class="form-text text-danger text-left" v-if="errors.password"> {{ errors.password[0] }} </small>
                    <div class="feedback text-secondary mt-2">
                        <p class="text-xs mb-0">* Password must be at least 8 characters long.</p>
                        <p class="text-xs mb-0">* Should contain 1 uppercase, 1 lowercase, 1 numeric value and 1 special character.</p>
                    </div>
                </div>

                <div class="form-group position-relative">
                    <label class="text-uppercase">RE-Enter A PASSWORD <i class="text-danger">*</i></label>
                    <input id="password_confirmation" class="form-control" type="password" v-model="password_confirmation" name="password_confirmation" @keyup="checkPassword" required minlength="8">
                    <span toggle="#password_confirmation" class="fa fa-fw fa-eye field_icon toggle-password"></span>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="terms-agree" @change="TermsCondition">
                            <label class="form-check-label" for="terms-agree">
                                I’ve read the <a href="/tnc" target="_blank" class="text-info border-bottom border-info">terms and conditions</a> and hereby I agree to receive this service.
                            </label>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary-text rounded btn-block" type="submit" id="finalSubmit" disabled >Finish</button>
            </form>
        </section>
        <notifyTopRight :content="notifyMsg" />
    </div>
</template>
<script>
    import {vInput} from "../wam-partial/form-lib"
    import tooltip from "../wam-partial/tooltip"
    import {notifyTopRight} from "../wam-partial/notification"

    export default {
        data() {
            return {
                email: null,
                password: null,
                password_confirmation: null,
                forgetPasswordStep: 1,
                countDown: 30,
                countDownStatus: true,
                notifyMsg: null,
                termsStatus: false,
                passwordStatus: false,
                passwordStrength: 0,
                errors: {},
            }
        },
        components:{
            vInput,
            tooltip,
            notifyTopRight
        },
        methods:{
            tryToSendOTP : function(el){
                let data = this.FilterForm(el.target, 'formdata')

                axios.post('api/forget_password_otp_request', data).then(response => {
                    let formData = this.FilterForm(el.target)
                    this.notifyMsg = response.data.message
                    $('.toast').toast('show')

                    this.email = formData['email']
                    this.forgetPasswordStep++

                    this.startTimer()
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            tryToReSendOTP : function(el){
                axios.post('api/forget_password_otp_request', {email: this.email}).then(response => {
                    this.notifyMsg = "OTP resent successfully"
                    $('.toast').toast('show')
                }).catch(error => {
                    window.location.reload()
                });
            },
            verifyOTP : function(el){
                let data = this.FilterForm(el.target, 'formdata')

                axios.post('api/forget_password_otp_verify', data).then(response => {
                    this.notifyMsg = response.data.message
                    $('.toast').toast('show')

                    this.forgetPasswordStep++
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            resetPassword : function(el){
                let data = {
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                }
                axios.post('api/forget_password_change_password', data).then(response => {
                    this.notifyMsg = response.data.message
                    $('.toast').toast('show')

                    this.$router.push('/auth/login')
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            TermsCondition: function (el){
                if(el.target.checked){
                    this.termsStatus = true
                }else{
                    this.termsStatus = false
                }
                this.formSubmittAble()
            },
            checkPassword: function (){
                this.errors = {}

                if (this.password !== this.password_confirmation){
                    this.errors.password = []
                    this.errors.password[0] = "The password confirmation does not match."
                    this.passwordStatus = false
                }else{
                    this.passwordStatus = true
                }
                this.formSubmittAble()
            },
            checkPasswordStrength: function(el) {
                let password = el.target.value
                this.passwordStrength = 0

                if (password.length >= 8) {
                    this.passwordStrength += 1
                }
                if (password.match(/[a-z]+/)) {
                    this.passwordStrength += 1
                }
                if (password.match(/[A-Z]+/)) {
                    this.passwordStrength += 1
                }
                if (password.match(/[0-9]+/)) {
                    this.passwordStrength += 1
                }
                if (password.match(/[$@#&!*]+/)) {
                    this.passwordStrength += 1
                }
            },
            formSubmittAble: function (){
                if(this.termsStatus && this.passwordStatus && this.passwordStrength === 5){
                    document.getElementById('finalSubmit').removeAttribute('disabled')
                }else{
                    document.getElementById('finalSubmit').setAttribute('disabled', true)
                }
            }
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
    .place-holder-center::-webkit-input-placeholder {
        text-align: center;
    }
    .place-holder-center:-moz-placeholder { /* Firefox 18- */
        text-align: center;
    }
    .place-holder-center::-moz-placeholder {  /* Firefox 19+ */
        text-align: center;
    }
    .place-holder-center:-ms-input-placeholder {
        text-align: center;
    }
</style>
