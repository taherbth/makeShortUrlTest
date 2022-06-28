<template>
    <div>
        <Layout>
            <div class="page-header">
                <div><h3 class="font-weight-bold">Payment Methods</h3></div>
                <div><router-link to="/institution/manage/add-payment-method" class="btn btn-primary text-white">Add Payment Method</router-link></div>
            </div>
            <div class="row">
                <div class="col-md-6 col-xl-4" v-for="(item, key) in AllPaymentMethods" :key="key">
                    <div class="payment-method-container">
                        <div class="payment-method-info">
                            <vise v-if="item.card.brand === 'visa'" />
                            <mastercard v-else-if="item.card.brand === 'mastercard'" />
                            <paypal v-else-if="item.card.brand === 'paypal'" />
                            <mastercard v-else />
                            <div>
                                <div class="text-neutral text-sm">
                                    <span class="card-hide-no">
                                        <font-awesome icon="circle"/>
                                        <font-awesome icon="circle"/>
                                        <font-awesome icon="circle"/>
                                        <font-awesome icon="circle" class=" mr-1"/>
                                        <font-awesome icon="circle"/>
                                        <font-awesome icon="circle"/>
                                        <font-awesome icon="circle"/>
                                    </span>
                                    <span>{{ item.card.last4 }}</span>
                                </div>
                                <p>{{ User.name }}</p>
                                <p class="text-neutral text-xs">Expires on {{ `${item.card.exp_month}/${item.card.exp_year}` }}</p>
                                <span class="r-badge" v-if="item.id===PrimaryPayment">Primary</span>
                            </div>
                            <a href="#" class="edit-icon">
                                <font-awesome icon="pen" />
                            </a>
                        </div>
                        <div class="btn-container">
                            <button type="button" class="btn btn-light" :disabled="item.id===PrimaryPayment" @click="MakePrimary(item.id)">Make Primary</button>
                        </div>
                    </div>
                </div>

            </div>
        </Layout>
    </div>
</template>
<script>
    import Layout from '../../layouts/layout'
    import Mastercard from '../../wam-partial/icons/mastercard'
    import Paypal from '../../wam-partial/icons/paypal'
    import Vise from '../../wam-partial/icons/vise'

    export default {
        data() {
            return {
                AllPaymentMethods: [],
                PrimaryPayment: null
            }
        },
        components: {
            Layout,
            Mastercard,
            Vise,
            Paypal,
        },
        methods: {
            getAllPaymentMethods: function (){
                axios.get('api/institution/get_all_payment_methods').then(res => {
                    this.AllPaymentMethods = res.data.all_payment.data
                    this.PrimaryPayment = res.data.primary_payment
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            MakePrimary: function (id){
                axios.post(`api/institution/post_change_default_payment_method?id=${id}`).then(res => {
                    this.$swal.fire({
                        icon: 'success',
                        text: res.data.message,
                    })
                    this.getAllPaymentMethods()
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
        },
        mounted() {
            this.getAllPaymentMethods()
        },
    }
</script>
<style scoped>
    .page-header{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        margin-bottom: 20px;
        margin-top: 10px;
    }
    .payment-method-container{
        background: #fff;
        padding: 20px 0 25px 20px;
        border: 1px solid #E8EBED;
        border-radius: 5px;
        box-shadow: 0px 1px 3px #38B6FF1A;
        margin-bottom: 8px;
    }
    .payment-method-info{
        position: relative;
        display: flex;
        padding-right: 55px;
    }
    .payment-method-info p{
        margin: 0;
    }
    .payment-method-info .r-badge{
        background: #234153;
        color: #fff;
        padding: 3px 7px;
        border-radius: 5px;
        font-size: 12px;
    }
    .payment-method-info .edit-icon{
        position: absolute;
        right: 25px;
    }
    .payment-method-info .edit-icon svg{
        color: #38B6FF !important;
    }
    .payment-method-info .svg-icon{
        margin-right: 1.5rem;
        margin-top: 18px;
    }
    .payment-method-container .btn-container{
        text-align: right;
        padding-right: 20px;
    }
    .card-hide-no{
        margin-right: 1px;
    }
    .card-hide-no svg{
        font-size: 8px;
    }
</style>
