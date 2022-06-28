<template>
    <div>
        <Layout>
            <div class="form-container">
                <form @submit.prevent="AddPaymentMethod" style="width:500px">
                    <h3>Add Payment Method</h3>
                    <p class="payment-info">Please enter your payment method information below</p>

                    <!-- <div class="form-row">
                        <div class="col-md-6">
                            <label>payment method type</label>
                            <select name="type" class="form-control option-has-icon" required>
                                <option>American Express</option>
                            </select>
                            <i class="fa fa-chevron-down"></i>
                        </div>
                    </div> -->

                    <div class="form-row">
                        <div class="col-md-6">
                            <label>Card Number</label>
                            <input name="number" type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Name on Card</label>
                            <input name="name" type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <label>Expires On</label>
                            <input name="expiry" type="text" class="form-control" id="expiry" maxlength="7">
                        </div>
                        <div class="col-md-6">
                            <label>CVV</label>
                            <input name="cvc" type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="custom-control custom-checkbox text-disable mb-2">
                        <input name="is_primary" value="1" type="checkbox" class="custom-control-input" id="customCheck1">
                        <label class="custom-control-label custom-bg" for="customCheck1">Make Primary Payment Method</label>
                    </div>
                    <p class="text-secondary mb-4">Learn more about our <a href="#" class="text-primary text-decoration-underline" >payment policy</a></p>
                    <div class="r-alert-warning"><tooltip content="For more information checkout our payment policy" iconColor="#FFAA38" /> We don't store your card information.</div>
                    <button type="submit" class="btn btn-primary text-white">Save</button>
                    <a class="btn btn-light" @click="$router.go(-1)">Cancel</a>
                </form>
            </div>
        </Layout>
    </div>
</template>
<script>
    import Layout from '../../layouts/layout'
    import tooltip from '../../wam-partial/tooltip'

    export default {
        data() {
            return {
                errors: {},
            }
        },
        components: {
            Layout,
            tooltip
        },
        methods: {
            AddPaymentMethod: function (el){
                var self = this
                let data = self.FilterForm(el.target, 'formdata')
                self.errors = {}
                axios.post('api/institution/add_payment_method', data).then(res => {
                    self.$swal.fire({
                        icon: 'success',
                        text: res.data.message,
                    }).then(function() {
                        self.$router.go(-1);
                    })
                }).catch(error => {
                    self.ValidtaeForm(error)
                });
            }
        },
        mounted() {
            // this is for expiry validate
            let characterCount
            $('#expiry').on('input',function(e){
                if($(this).val().length == 2 && characterCount < $(this).val().length) {
                    $(this).val($(this).val()+'/');
                }
                characterCount = $(this).val().length
            });
        },
    }
</script>
<style scoped>
    .form-container{
        display: flex;
        justify-content: center;
        height: 85vh;
        min-height: 750px;
        background: #fff;
        margin-bottom: 10px;
    }
    .form-container form{
        margin-top: 85px;
    }
    .form-container form label{
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 600;
    }
    .form-container form .custom-control-label{
        color: #D3D9DC;
        font-size: 1rem;
    }
    .form-container h3{
        font-weight: bold;
        margin-bottom: 20px;
    }
    .form-container .payment-info{
        color: #909FA8;
        margin-bottom: 40px;
    }
    .form-container .form-row{
        margin-bottom: 20px;
    }
    .custom-checkbox .custom-control-input:checked ~ .custom-control-label.custom-bg::before{
        background: #D3D9DC;
        border-color: #D3D9DC;
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
