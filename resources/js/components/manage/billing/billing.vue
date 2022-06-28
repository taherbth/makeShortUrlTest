<template>
    <div>
        <Layout>
            <div class="row">
                <div class="col-xl-8">
                    <div class="r-card">
                        <h5 class="r-card-header">Bill Estimates</h5>
                        <div class="r-card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless r-table">
                                    <thead>
                                        <tr>
                                            <th>Items</th>
                                            <th class="text-right">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ BillEstimates.total_user_count || 0 }} Users until this month</td>
                                            <td class="text-right font-weight-bold">{{`$${BillEstimates.pricing/100 || 0} X ${BillEstimates.total_user_count || 0}`}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ `Tax/ VAT(${BillEstimates.tax || 0})%` }})</td>
                                            <td class="text-right font-weight-bold">${{ ((((BillEstimates.pricing/100)*BillEstimates.total_user_count)/100)*BillEstimates.tax) || 0}}</td>
                                        </tr>
                                        <tr>
                                            <td>Total</td>
                                            <td class="text-right text-primary font-weight-bold h3">${{ (((BillEstimates.pricing/100)*BillEstimates.total_user_count)+((((BillEstimates.pricing/100)*BillEstimates.total_user_count)/100)*BillEstimates.tax)) || 0 }}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <p class="text-right text-sm text-secondary">Next Billing Date: {{ NextBillingDate() }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="r-card">
                        <h5 class="r-card-header">Payment Method</h5>
                        <div class="r-card-body">
                            <div class="billing-contact" v-for="(item, key) in AllPaymentMethods" :key="key" v-if="item.id===PrimaryPayment">
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
                                    <p class="">{{ User.name }}</p>
                                    <p class="text-neutral text-xs">Expires on {{ `${item.card.exp_month}/${item.card.exp_year}` }}</p>
                                    <span class="r-badge">Primary</span>
                                </div>
                                <a href="#" class="edit-icon">
                                    <font-awesome icon="pen" />
                                </a>
                            </div>
                            <div v-if="AllPaymentMethods.length<1" class="r-card-empty-payment-method">
                                <empty-progress class="mb-4"/>
                                <h6 class="font-weight-bold">No payment method to show</h6>
                                <p class="text-secondary">We'll show payment method once you get <br> started!</p>
                            </div>
                            <router-link to="/institution/manage/payment-method" class="btn btn-info bg-primary mt-2 mb-2">Show all</router-link>
                            <router-link to="/institution/manage/add-payment-method" class="btn btn-border-primary mt-2 mb-2">Add Payment Method</router-link>
                            <p class="text-secondary">Learn more about our <a href="#" class="text-primary text-decoration-underline">payment policy</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8" v-if="!isLock">
                    <div class="r-card">
                        <h5 class="r-card-header">Invoice History</h5>
                        <div class="r-card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless r-table">
                                    <thead>
                                        <tr>
                                            <th>Invoice ID</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th class="text-right">Invoice</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, key) in InvoiceHistory" :key="key">
                                            <td>{{ item.invoice }}</td>
                                            <td class="text-uppercase">{{ `${item.currency} ${item.amount/100}` }}</td>
                                            <td class="text-success" v-if="item.paid">Paid</td>
                                            <td class="text-success" v-else>Due</td>
                                            <td class="text-right"> <a :href="item.receipt_url" target="_blank" ><font-awesome icon="eye" /></a> </td>
                                        </tr>
                                        <tr v-if="InvoiceHistory.length<1">
                                            <td class="r-card-empty-invoice-history" colspan="4">
                                                <empty-recent-responses class="mb-4"/>
                                                <h6 class="font-weight-bold">No invoice history to show</h6>
                                                <p class="text-secondary mb-0">We'll show invoice history once you get started!</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <router-link to="/institution/manage/invoice-history" class="btn btn-border-primary">View Full History</router-link>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4" v-if="!isLock">
                    <div class="r-card">
                        <h5 class="r-card-header">Billing Contacts</h5>
                        <div class="r-card-body" style="max-height:290px; overflow-y: auto">
                            <div class="billing-contact" v-for="(item, key) in BillingContacts" :key="key">
                                <font-awesome icon="envelope" class="mail-logo mr-3"/>
                                <div>
                                    <p class="">{{ User.name }}</p>
                                    <p class="text-neutral">{{ item.email }}</p>
                                    <span class="r-badge" v-if="item.is_default">Primary</span>
                                    <button v-else type="button" class="btn btn-sm btn-light" @click="MakePrimary(item.id)">Make Primary</button>
                                </div>
                                <router-link :to="`/institution/manage/billing-contact/${item.id}/edit`" class="edit-icon"><font-awesome icon="pen" /></router-link>
                            </div>
                            <div v-if="BillingContacts.length<1" class="r-card-empty-billing-contacts">
                                <empty-top-responses class="mb-4"/>
                                <h6 class="font-weight-bold">No billing contacts to show</h6>
                                <p class="text-secondary">We'll show billing contacts once you get <br> started!</p>
                            </div>
                            <router-link to="/institution/manage/billing-contact" class="btn btn-border-primary">Add Billing Contact</router-link>
                        </div>
                    </div>
                </div>
            </div>

            <div class="billing-contact bg-white" v-if="isLock">
                <img src="/assets/icons/ionic-ios-lock.svg" style="margin-top:-20px" class="mr-2">
                <div>
                    <h5>Your account is locked</h5>
                    <p>To continue using WAM, please add a payment method</p>
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
    import EmptyProgress from '../../wam-partial/icons/empty-progress'
    import EmptyRecentResponses from '../../wam-partial/icons/empty-recent-responses'
    import EmptyTopResponses from '../../wam-partial/icons/empty-top-responses'

    export default {
        data() {
            return {
                BillingContacts: [],
                InvoiceHistory: [],
                BillEstimates: {},
                AllPaymentMethods: [],
                PrimaryPayment: null,
                isLock: true
            }
        },
        components: {
            Layout,
            Mastercard,
            Vise,
            Paypal,
            EmptyProgress,
            EmptyRecentResponses,
            EmptyTopResponses,
        },
        methods: {
            getBillingContacts: function (){
                axios.get('api/institution/get_all_billing_addresses').then(res => {
                    this.BillingContacts = res.data
                    this.isLock = false
                })
            },
            getInvoiceHistory: function (){
                axios.get('api/institution/get_all_invoice_history').then(res => {
                    this.InvoiceHistory = res.data.data.slice(0,4)
                    this.isLock = false
                })
            },
            getBillEstimates: function (){
                axios.get('api/institution/get_billing_estimation').then(res => {
                    this.BillEstimates = res.data
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            getAllPaymentMethods: function (){
                axios.get('api/institution/get_all_payment_methods').then(res => {
                    this.AllPaymentMethods = res.data.all_payment.data
                    this.PrimaryPayment = res.data.primary_payment
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            NextBillingDate: function (){
                if (this.BillEstimates.canceled_at) {
                    return this.timeConverter(this.BillEstimates.canceled_at)
                } else {
                    let a = new Date(new Date().setDate(new Date(this.BillEstimates.trial_end_at).getMonth() + 1))
                    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                    var year = a.getFullYear();
                    var month = months[a.getMonth()];
                    var date = a.getDate();
                    var hour = a.getHours();
                    var min = a.getMinutes();
                    var sec = a.getSeconds();
                    var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
                    return time;
                }
            },
            timeConverter: function (UNIX_timestamp){
                var a = new Date(UNIX_timestamp * 1000);
                var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                var year = a.getFullYear();
                var month = months[a.getMonth()];
                var date = a.getDate();
                var hour = a.getHours();
                var min = a.getMinutes();
                var sec = a.getSeconds();
                var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
                return time;
            },
            MakePrimary: function (id){
                axios.post(`api/institution/post_set_default_billing_address?id=${id}`).then(res => {
                    this.$swal.fire({
                        icon: 'success',
                        text: res.data.message,
                    })
                    this.getBillingContacts()
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
        },
        mounted() {
            this.getBillingContacts()
            this.getInvoiceHistory()
            this.getBillEstimates()
            this.getAllPaymentMethods()
        },
    }
</script>
<style scoped>
    .r-card{
        width: 100%;
        height: auto;
        background: #fff;
        border-radius: 5px;
        margin-bottom: 20px;
        padding-bottom: 20px;
    }
    .r-card .r-card-header, .r-card .r-card-body{
        padding: 30px 0;
        margin: 0 30px;
    }
    .r-card .r-card-header{
        font-weight: bold;
    }
    .r-card .r-card-body{
        padding: 0;
    }
    .r-table th {
        color: #909FA8;
        font-weight: normal;
        padding-top: 0;
    }
    .r-table tr th, .r-table tr td{
        border-bottom: 1px solid #E8EBED;
    }
    .r-table tr:last-child td{
        border-bottom: 0;
    }
    .billing-contact{
        position: relative;
        display: flex;
        padding: 20px 35px 25px 20px;
        border: 1px solid #E8EBED;
        border-radius: 5px;
        box-shadow: 0px 1px 3px #38B6FF1A;
        margin-bottom: 8px;
    }
    .billing-contact p{
        margin: 0;
    }
    .billing-contact .mail-logo{
        background: #E7EBED;
        padding: 10px;
        font-size: 36px;
        color: #617686;
        margin-top: 6px;
    }
    .billing-contact .r-badge{
        background: #234153;
        color: #fff;
        padding: 2px 7px;
        border-radius: 5px;
        font-size: 12px;
    }
    .billing-contact .edit-icon{
        position: absolute;
        right: 30px;
    }
    .billing-contact .edit-icon svg{
        color: #38B6FF !important;
    }
    .billing-contact .svg-icon{
        margin-right: 1rem;
        margin-top: 10px;
    }
    .card-hide-no{
        margin-right: 1px;
    }
    .card-hide-no svg{
        font-size: 8px;
    }

    /* empty case */
    .r-card-empty-payment-method, .r-card-empty-invoice-history, .r-card-empty-billing-contacts{
        text-align: center;
        padding: 6px 0px;
    }
    .r-card-empty-billing-contacts{
        padding: 36px 0px;
    }
    .r-card-empty-invoice-history svg{
        margin-top: 50px;
    }
</style>
