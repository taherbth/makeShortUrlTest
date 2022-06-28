<template>
    <div class="page-wrapper">
        <div class="text-center">
            <h3 class="text-uppercase">Change Password</h3>
            <p class="text-secondary">Please set a strong password for your account. Weak password may lead to security issues</p>

            <form @submit.prevent="ChangePassword" style="margin-top:60px">
                <table class="text-left">
                    <tr>
                        <td colspan="2">
                            <div class="form-group position-relative">
                                <label class="text-uppercase">Enter old password <tooltip content="Enter your current password" /> </label>
                                <input id="old_password" class="form-control" type="password" v-model="old_password" name="old_password" required minlength="8">
                                <span toggle="#old_password" class="fa fa-fw fa-eye field_icon toggle-password"></span>
                                <small class="form-text text-danger text-left" v-if="errors.old_password"> {{ errors.old_password[0] }} </small>
                                <div class="feedback text-secondary mt-2">
                                    <p class="text-xs mb-0">* Password must be at least 8 characters long.</p>
                                    <p class="text-xs mb-0">* Should contain 1 uppercase, 1 lowercase, 1 numeric value, and 1 special character.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="form-group position-relative">
                                <label class="text-uppercase">Enter a password <tooltip content="Enter your new password" /> </label>
                                <input id="password" class="form-control" type="password" v-model="password" name="password" @keyup="checkPasswordStrength" required minlength="8">
                                <span toggle="#password" class="fa fa-fw fa-eye field_icon toggle-password"></span>
                                <small class="form-text text-danger text-left" v-if="errors.password"> {{ errors.password[0] }} </small>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="form-group position-relative">
                                <label class="text-uppercase">Re-enter password</label>
                                <input id="password_confirmation" class="form-control" type="password" v-model="password_confirmation" name="password_confirmation" @keyup="checkPassword" required minlength="8">
                                <span toggle="#password_confirmation" class="fa fa-fw fa-eye field_icon toggle-password"></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button type="submit" id="finalSubmit" class="btn btn-primary-text col-12 mr-1">Update Password</button>
                        </td>
                        <td>
                            <a href="#/profile" class="btn btn-border-primary text-default col-12 ml-1">Cancel</a>
                        </td>
                    </tr>
                </table>
            </form>

            <div class="r-alert-warning"><tooltip content="Read it carefully" iconColor="#FFAA38" /> Changing the password will log you out of WAM from all your other devices and browsers.</div>
        </div>
    </div>
</template>
<script>
    import Tooltip from '../../wam-partial/tooltip'
    export default {
        data() {
            return {
                old_password: null,
                password: null,
                password_confirmation: null,
                passwordStatus: false,
                passwordStrength: 0,
                errors: {},
            }
        },
        components: {
            Tooltip,
        },
        methods: {
            ChangePassword : function (el){
                this.errors = {}
                let data = {
                    old_password: this.old_password,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                }
                axios.post('api/facilitator/password_reset', data).then(res => {
                    this.$swal.fire({
                        icon: 'success',
                        text: res.data.message,
                    }).then(() => {
                        this.LocalLogout()
                    })
                }).catch(error => {
                    this.ValidtaeForm(error)
                });

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
                if(this.passwordStatus && this.passwordStrength === 5){
                    document.getElementById('finalSubmit').removeAttribute('disabled')
                }else{
                    document.getElementById('finalSubmit').setAttribute('disabled', true)
                }
            },
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
    .page-wrapper .ins-img-wrapper{
        display: inline-block;
        position: relative;
    }
    .page-wrapper .ins-img-wrapper>svg{
        position: absolute;
        padding: 7px;
        background: #38B6FF;
        color: #fff;
        border-radius: 50%;
        border: 1px solid #fff;
        font-size: 31px;
        right: 10px;
        bottom: 31px;
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
</style>
