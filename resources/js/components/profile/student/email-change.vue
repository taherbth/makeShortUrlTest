<template>
    <div class="page-wrapper">
        <div class="text-center">
            <h3>Change Email</h3>
            <p class="text-secondary" v-if="Step === 1">You can manage your email settings from this section</p>
            <p class="text-secondary" v-else-if="Step === 2">We’ve sent you an email with a verification code. Please Insert the six digit code below.</p>
            <p class="text-secondary" v-else-if="Step === 3">Enter a new email address for your account</p>

            <form style="margin-top:60px" v-if="Step === 1">
                <table class="text-left">
                    <tr>
                        <td>
                            <div class="form-group">
                                <label>Email address <tooltip content="This is your current Email Address" /></label>
                                <input type="text" class="form-control" :value="User.email" disabled />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button type="button" class="btn btn-primary-text col-12 mr-1" @click="SendOtp">Change Email</button>
                        </td>
                    </tr>
                </table>
            </form>
            <form @submit.prevent="CheckOtp" style="margin-top:60px" v-else-if="Step === 2">
                <table>
                    <tr>
                        <td>
                            <div class="form-group">
                                <label>Insert the 6 digit code below</label>
                                <input name="otp" type="text" class="form-control form-control-big text-center" placeholder="XXXXXX"/>
                                <small class="form-text text-danger text-left" v-if="errors.otp"> {{ errors.otp[0] }} </small>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button type="submit" class="btn btn-primary-text col-12 mr-1">Verify</button>
                        </td>
                    </tr>
                </table>
            </form>
            <form @submit.prevent="ChangeEmailAddress" style="margin-top:60px" v-else-if="Step === 3">
                <table class="text-left">
                    <tr>
                        <td>
                            <div class="form-group">
                                <label>your new email</label>
                                <input name="email" type="text" class="form-control" placeholder="example@domain.com" />
                                <small class="form-text text-danger text-left" v-if="errors.email"> {{ errors.email[0] }} </small>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button type="submit" class="btn btn-primary-text col-12 mr-1">Submit</button>
                        </td>
                    </tr>
                </table>
            </form>

        </div>
    </div>
</template>
<script>
    import Tooltip from '../../wam-partial/tooltip'
    export default {
        data() {
            return {
                Step: 1,
                errors: {}
            }
        },
        components: {
            Tooltip,
        },
        methods: {
            SendOtp: function (el){
                this.Step = 1
                axios.post('api/student/email_reset_request').then(res => {
                    this.Step = 2
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            CheckOtp: function (el){
                this.errors = {}
                let data = this.FilterForm(el.target, 'formdata')
                axios.post('api/student/email_reset_otp_verify', data).then(res => {
                    this.Step = 3
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            ChangeEmailAddress: function (el){
                this.errors = {}
                let data = this.FilterForm(el.target, 'formdata')
                axios.post('api/student/email_reset', data).then(res => {
                    this.ChangeLocalStorageData({email: data.get('email')}, ["email"])

                    this.$swal.fire({
                        icon: 'success',
                        text: res.data.message,
                    }).then(() => {
                        this.$router.go(-1)
                    })
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            setAvatar: function (){
                $('#avatar').click()
            }
        },
    }
</script>
<style scoped>
    .page-wrapper{
        display: flex;
        justify-content: center;
        background: #fff;
        margin-bottom: 10px;
        padding: 20px;
        padding-bottom: 30px;
    }
    .page-wrapper h3{
        margin-top: 30px;
        font-weight: bold;
    }
    .page-wrapper table{
        margin: 0 auto;
    }
    .page-wrapper table p{
        margin: 0;
    }
    .page-wrapper form label{
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 600;
    }
    .page-wrapper form input{
        border: 1px solid #E8EBED;
        box-shadow: 0px 1px 3px #38B6FF1A;
    }
    .r-alert-warning{
        background: #FFFCF8;
        color: #FFAA38;
        font-size: 12px;
        border-radius: 5px;
        margin: 25px 0;
        padding: 20px;
    }
    .form-control-big{
        height: 64px;
        font-size: 24px;
    }
</style>
