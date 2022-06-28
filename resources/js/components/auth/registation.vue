<template lang="">
    <div class="text-center mt-5">
        <div v-if="facilitatorStep === 4 || studentStep === 4 || institutionStep === 4">
            <h3 class="text-uppercase font-weight-bold">SET UP A PASSWORD</h3>
            <p class="text-secondary text-sm mt-4">Please set a strong password for your account. Weak password may lead to security issues</p>
        </div>
        <div v-else-if="facilitatorStep === 3 || studentStep === 3 || institutionStep === 3">
            <h3 class="text-uppercase font-weight-bold">Verify your email</h3>
            <p class="text-secondary text-sm mt-4">We’ve sent you an email with a verification code. Please Insert the code below.</p>
        </div>
        <div v-else-if="facilitatorStep <= 2 || studentStep <= 2 || institutionStep <= 2">
            <h3 class="text-uppercase font-weight-bold">Welcome to Wam academy!</h3>
            <p class="text-secondary text-sm mt-4">Please select an option to continue to sign up</p>
        </div>

        <section class="mt-5">
            <a href="javascript:void(0)" class="btn btn-square mb-2" :class="{ active: ins_submitted}" @click="registerInstitution()">
                <institution-logo />
                <p>Institution</p>
            </a>
            <a href="javascript:void(0)" class="btn btn-square mb-2" :class="{ active: fac_submitted}" @click="registerFacilitator()">
                <facilitator-logo />
                <p>Facilitator</p>
            </a>
            <a href="javascript:void(0)" class="btn btn-square mb-2" :class="{ active: std_submitted}" @click="registerStudent()">
                <student-logo />
                <p>Student</p>
            </a>
        </section>
        <div class="wam-signup-header mb-5 mt-5">
            <section v-if="facilitatorStep === 2 || studentStep === 2">
                <form class="text-left" @submit.prevent="takeRegister">

                    <input type="hidden" name="role" :value="role">
                    <vInput name="name" :required="true" placeHolder="Schulist Avery" title="full name" :errorMsg="errors.name"/>

                    <div class="form-group">
                        <label class="text-uppercase">YOUR EMAIL <span class="text-danger">*</span></label>
                        <input class="form-control" type="email" name="email" placeholder="example@domain.com" v-model="email" required :disabled="is_invide?true:false">
                        <small class="form-text text-danger text-left" v-if="errors.email"> {{ errors.email[0] }} </small>
                    </div>

                    <div class="form-group">
                        <label class="text-uppercase">YOUR INSTITUTION CODE <tooltip content="To get this link contact with your Facilitator/Institution" /> <span class="text-danger">*</span></label>
                        <input class="form-control" name="institution_url" placeholder="e.g. https://abc.wam.academy" v-model="ins_id_no" required :disabled="is_invide?true:false">
                        <small class="form-text text-danger text-left" v-if="errors.institution_url"> {{ errors.institution_url[0] }} </small>
                    </div>

                    <vInput type="hidden" />
                    <button class="btn btn-primary-text rounded btn-block" type="submit">Sign Up</button>
                </form>
            </section>

            <section v-if="institutionStep === 2">
                <form class="text-left" @submit.prevent="takeRegister">

                    <input type="hidden" name="role" :value="role">

                    <vInput name="name" :required="true" placeHolder="Schulist Avery" title="Name of your institution" :errorMsg="errors.name"/>
                    <vInput type="email" name="email" :required="true" placeHolder="example@domain.com" title="ADMIN EMAIL" :errorMsg="errors.email" />
                    <div class="form-group position-relative">
                        <label class="text-uppercase">number of students <span class="text-danger">*</span></label>
                        <select class="form-control option-has-icon" name="institution_primary_student_quantity" required>
                            <option value="">Please Select</option>
                            <option value="10">0-10</option>
                            <option value="50">10-50</option>
                            <option value="100">50-100</option>
                            <option value="100">100-500</option>
                            <option value="500">500-1000</option>
                            <option value="1000">1000+</option>
                        </select>
                        <i class="fa fa-chevron-down"></i>
                    </div>
                    <div class="form-group position-relative">
                        <label class="text-uppercase">number of facilitators <span class="text-danger">*</span></label>
                        <select class="form-control option-has-icon" name="institution_primary_facilitator_quantity" required>
                            <option value="">Please Select</option>
                            <option value="50">10-50</option>
                            <option value="100">50-100</option>
                            <option value="100">100-500</option>
                            <option value="500">500-1000</option>
                            <option value="1000">1000+</option>
                        </select>
                        <i class="fa fa-chevron-down"></i>
                    </div>
                    <vInput type="hidden" />

                    <button class="btn btn-primary-text rounded btn-block" type="submit">Sign Up</button>
                </form>
            </section>

            <section v-if="facilitatorStep === 3 || studentStep === 3 || institutionStep === 3">
                <form @submit.prevent="verifyOTP">
                    <vInput name="otp" className="form-control-lg text-center" :required="true" title="Insert the 6 digit code below" :errorMsg="errors.otp" placeHolder="XXXXXX" />
                    <button class="btn btn-primary-text rounded btn-block" type="submit">Verify</button>
                </form>
            </section>

            <section v-if="facilitatorStep === 4 || studentStep === 4 || institutionStep === 4">
                <form class="text-left" @submit.prevent="finalRegistation">

                    <div class="form-group position-relative">
                        <label class="text-uppercase">Enter A PASSWORD <i class="text-danger">*</i></label>
                        <input id="password" class="form-control" type="password" v-model="password" @keyup="checkPasswordStrength" name="password" required minlength="8">
                        <span toggle="#password" class="fa fa-fw fa-eye field_icon toggle-password"></span>
                        <small class="form-text text-danger text-left" v-if="errors.password"> {{ errors.password[0] }} </small>
                        <div class="feedback text-secondary mt-2">
                            <p class="text-xs mb-0">* Password must be at least 8 characters long.</p>
                            <p class="text-xs mb-0">* Should contain 1 uppercase, 1 lowercase, 1 numeric value, and 1 special character.</p>
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

                    <button class="btn btn-primary-text rounded btn-block" type="submit" id="finalSubmit" disabled>Finish</button>
                </form>
            </section>

        </div>
        <notifyTopRight :content="notifyMsg" />
    </div>
</template>
<script>
    import {vInput} from "../wam-partial/form-lib"
    import {notifyTopRight} from "../wam-partial/notification"
    import Tooltip from "../wam-partial/tooltip"
    import InstitutionLogo from "../wam-partial/icons/institution-logo"
    import FacilitatorLogo from "../wam-partial/icons/facilitator-logo"
    import StudentLogo from "../wam-partial/icons/student-logo"
    export default {
        data() {
            return {
                role: null,
                institutionStep: 1,
                facilitatorStep: 1,
                studentStep: 1,
                ins_submitted: false,
                fac_submitted: false,
                std_submitted: false,
                password:null,
                password_confirmation:null,
                notifyMsg: null,
                termsStatus: false,
                passwordStatus: false,
                passwordStrength: 0,
                ins_id_no: null,
                email: null,
                is_invide: false,
                errors: {},
            }
        },
        components:{
            vInput,
            Tooltip,
            notifyTopRight,
            InstitutionLogo,
            FacilitatorLogo,
            StudentLogo,
        },
        methods:{
            registerInstitution : function(){
                this.is_invide = false
                this.ins_id_no = null
                this.email = null
                this.role = 3
                this.facilitatorStep = 1
                this.studentStep = 1
                this.fac_submitted = false
                this.std_submitted = false
                this.ins_submitted = true
                this.institutionStep = 2
                this.ClearAllForm()
            },

            registerFacilitator : function(){
                this.is_invide = false
                this.ins_id_no = null
                this.email = null
                this.role = 1
                this.institutionStep = 1
                this.studentStep = 1
                this.fac_submitted = true
                this.std_submitted = false
                this.ins_submitted = false
                this.facilitatorStep = 2
                this.ClearAllForm()
            },

            registerStudent : function (){
                this.is_invide = false
                this.ins_id_no = null
                this.email = null
                this.role = 2
                this.institutionStep = 1
                this.facilitatorStep = 1
                this.fac_submitted = false
                this.std_submitted = true
                this.ins_submitted = false
                this.studentStep = 2
                this.ClearAllForm()
            },

            nextStep : function (){
                if(this.role === 3){
                    this.institutionStep++
                    this.ins_submitted = true
                }else if (this.role === 2){
                    this.studentStep++
                    this.std_submitted = true
                }else if (this.role === 1){
                    this.facilitatorStep++
                    this.fac_submitted = true
                }else{
                    this.$swal.fire({
                        icon: 'error',
                        title: 'Something went wrong.',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            },

            takeRegister : function (el){
                this.errors = {}
                let data = this.FilterForm(el.target, 'formdata')
                if (this.email) {data.append('email', this.email)}
                if (this.ins_id_no) {data.append('institution_url', this.ins_id_no)}

                axios.post('api/email_verification_request', data).then(response => {
                    let fetchData = this.FilterForm(el.target)
                    this.notifyMsg = response.data.message
                    $('.toast').toast('show')

                    this.$store.dispatch('setGestInfo', fetchData)
                    this.nextStep()
                }).catch(error => {
                    this.ValidtaeForm(error)
                });

            },

            verifyOTP: function (el){
                let data = this.FilterForm(el.target, 'formdata')
                this.errors = {}

                axios.post('api/email_verify', data).then(response => {
                    this.notifyMsg = response.data.message
                    $('.toast').toast('show')

                    this.nextStep()
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },

            finalRegistation: function (el){
                this.errors = {}

                let data = {
                    name: this.$store.getters.guestInfo['name'],
                    email: this.$store.getters.guestInfo['email'],
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                    role: this.$store.getters.guestInfo['role'],
                    institution_url: this.$store.getters.guestInfo['institution_url'] || '',
                    institution_primary_student_quantity: this.$store.getters.guestInfo['institution_primary_student_quantity'] || '',
                    institution_primary_facilitator_quantity: this.$store.getters.guestInfo['institution_primary_facilitator_quantity'] || '',
                }
                axios.post('api/register', data).then(response => {
                    this.notifyMsg = response.data.message
                    $('.toast').toast('show')

                    this.$store.dispatch('deleteGestInfo')
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
            },
            CheckUserInvitation: function (){
                if (this.$route.query.role && this.$route.query.ins_id && this.$route.query.email) {
                    this.is_invide = true
                    this.role = parseInt(this.$route.query.role) || null
                    this.ins_id_no = this.$route.query.ins_id || null
                    this.email = this.$route.query.email || null
                    this.nextStep()
                }
            }
        },
        mounted() {
            this.$emit('setLayout', {
                signTitle : 'Already have an account?',
                signText : 'Sign In',
                signAddr : '/auth/login'
            })

            this.CheckUserInvitation()
        },
    }
</script>
<style scoped>
    .btn-square{
        width: 187px !important;
        height: 160px !important;
        background: var(--borer-default);
        box-shadow: 0px 3px 10px #41B7FC26;
        color: #888888;
        border: 1px solid #E2F4FE;
        border-radius: 10px;
    }
    .btn-square p{
        color: #888888;
        font-size: 14px;
        text-transform: uppercase;
    }
    .btn-square svg{
        margin: 50px 0px 18px 0px;
    }
    .btn-square.active{
        width: 211px !important;
        height: 180px !important;
        border: 2px solid var(--primary);
        color: var(--primary);
    }
    .btn-square.active p{
        color: var(--primary);
    }
</style>
<style>
    .btn-square.active svg.svg-icon path{
        fill: var(--primary) !important;
    }
</style>
