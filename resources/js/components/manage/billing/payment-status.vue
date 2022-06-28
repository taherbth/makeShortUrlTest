<template>
    <div>
        <Layout>
            <div class="payment-status">
                <div class="center" v-if="status === 1">
                    <font-awesome icon="circle" size="2x" class="mb-3"/>
                    <h3 class="text-primary mb-3">Congratulations! Your payment was successful</h3>
                    <h5 class="text-secondary">Hold on, we are taking you to your account</h5>
                </div>
                <div class="center" v-if="status === 2">
                    <h3 class="text-danger mb-3">Sorry! Seems like you don’t have enough balance</h3>
                    <h5 class="text-secondary mb-4">Your payment method is added. Please recharge balance to unlock your account</h5>
                    <router-link to="#" class="btn btn-primary text-white">Go Back</router-link>
                </div>
                <div class="center" v-if="status === 3">
                    <h3 class="text-danger mb-3">Sorry! Seems like your card is expired</h3>
                    <h5 class="text-secondary mb-4">Please provide a valid payment method</h5>
                    <router-link to="#" class="btn btn-primary text-white">Go Back</router-link>
                </div>
            </div>
        </Layout>
    </div>
</template>
<script>
    import Layout from '../../layouts/layout'
    export default {
        data(){
            return {
                status: 1
            }
        },
        components: {
            Layout,
        },
    }
</script>
<style scoped>
    .payment-status{
        width: 100%;
        height: 85vh;
        position: relative;
        background: #fff;
        margin-bottom: 1rem;
    }
    .payment-status .center {
        width: 100%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
</style>
