<template>
    <div>
        <Layout>
            <div class="page-header">
                <h3 class="font-weight-bold"><font-awesome icon="angle-left" class="text-default cursor-pointer" @click="$router.go(-1)" /> Edit Pricing </h3>
            </div>

            <div class="r-card">
                <p class="r-card-header">Pricing</p>
                <form @submit.prevent="set_package_pricing">
                    <input type="hidden" name="is_taxed" :value="DisableStatus">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="text-uppercase">amount per user (usd)</label>
                                <input type="number" class="form-control" name="unit_amount" required v-model="unit_amount" />
                                <small class="form-text text-danger" v-if="errors.unit_amount">{{ errors.unit_amount[0] }}</small>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="text-uppercase">VAT/TAX (%)</label>
                                <input type="number" min="1" max="100" class="form-control" name="tax_rate" required v-model="tax_rate" :disabled="DisableStatus?false:true"/>
                                <small class="form-text text-danger" v-if="errors.tax_rate">{{ errors.tax_rate[0] }}</small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <button type="button" class="col-12 btn btn-primary-text" style="margin-top:30px" @click="ChangeDisableStatus">{{ DisableStatus?'Disable tax':'Enable tax' }}</button>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary text-white">Edit Package</button>
                            <button type="button" class="btn btn-border-primary text-default">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </Layout>

    </div>
</template>
<script>
    import Layout from '../layouts/layout'
    import Pagination from 'laravel-vue-pagination'
    export default {
        data() {
            return {
                DisableStatus: 0,
                unit_amount: 0,
                tax_rate: 0,
                errors: {}
            }
        },
        components: {
            Layout,
            Pagination,
        },
        methods: {
            get_package_pricing: function (){
                axios.get(`api/authority/get_package_pricing`).then(res => {
                    this.unit_amount = res.data.unit_amount/100
                    this.tax_rate = res.data.tax_rate
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            set_package_pricing: function (el){
                this.errors = {}
                let data = this.FilterForm(el.target, 'formdata')
                axios.post(`api/authority/post_set_package_pricing`, data).then(res => {
                    $('#ActiveDeactiveModal').modal('hide')
                    this.$swal.fire({
                        icon: 'success',
                        text: res.data.message,
                    })
                    this.get_package_pricing()
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            ChangeDisableStatus: function (){
                this.DisableStatus = this.DisableStatus?0:1
            }
        },
        mounted() {
            this.get_package_pricing()
        },
    }
</script>
<style scoped>
    .r-card{
        background: #fff;
        padding: 30px;
        box-shadow: 0px 1px 3px #38B6FF1A;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    .r-card-header{
        font-size: 20px;
        font-weight: bold;
        border-bottom: 1px solid #E8EBED;
        padding-bottom: 1rem;
    }
    .page-header{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        margin-bottom: 20px;
        margin-top: 10px;
    }
    .text-title{
        color: #909FA8;
        margin-bottom: 6px;
    }
    .r-card form label{
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 600;
    }
    .r-card form input{
        border: 1px solid #E8EBED;
        box-shadow: 0px 1px 3px #38B6FF1A;
    }
</style>
