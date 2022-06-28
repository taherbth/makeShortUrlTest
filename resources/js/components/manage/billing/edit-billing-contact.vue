<template>
    <div>
        <Layout>
            <div class="form-container">
                <form @submit.prevent="EditBillingAddress_f" class="" style="width:500px">
                    <h3>Edit Billing Contact</h3>
                    <p class="payment-info">Please enter your billing information below</p>
                    <input type="hidden" name="id" :value="this.$route.params.id">


                    <div class="form-row">
                        <div class="col-md-6">
                            <label>Country</label>
                            <select name="country_id" :class="['form-control', 'option-has-icon', errors.country?'is-invalid':'']" required @change="get_states">
                                <option value="">Select one</option>
                                <option v-for="(country, key) in countries" :key="key" :selected="country.id === EditBillingAddress.country_id" :value="country.id" v-html="country.name"></option>
                            </select>
                            <i class="fa fa-chevron-down"></i>
                            <small class="form-text text-danger" v-if="errors.country">{{ errors.country[0] }}</small>
                        </div>
                        <div class="col-md-6">
                            <label>zip/postal</label>
                            <input type="text" name="zip_code" :class="['form-control', errors.zip_code?'is-invalid':'']" v-model="EditBillingAddress.zip_code" required>
                            <small class="form-text text-danger" v-if="errors.zip_code">{{ errors.zip_code[0] }}</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <label>state</label>
                            <select name="state_id" :class="['form-control', 'option-has-icon', errors.state?'is-invalid':'']" required @change="get_cities">
                                <option value="">Select one</option>
                                <option v-for="(state, key) in states" :key="key" :selected="state.id === EditBillingAddress.state_id" :value="state.id" v-html="state.name"></option>
                            </select>
                            <i class="fa fa-chevron-down"></i>
                            <small class="form-text text-danger" v-if="errors.state">{{ errors.state[0] }}</small>
                        </div>
                        <div class="col-md-6">
                            <label>city</label>
                            <select name="city_id" :class="['form-control', 'option-has-icon', errors.city?'is-invalid':'']" required>
                                <option value="">Select one</option>
                                <option v-for="(city, key) in cities" :key="key" :selected="city.id === EditBillingAddress.city_id" :value="city.id" v-html="city.name"></option>
                            </select>
                            <i class="fa fa-chevron-down"></i>
                            <small class="form-text text-danger" v-if="errors.city">{{ errors.city[0] }}</small>
                        </div>
                    </div>




                    <div class="form-row">
                        <div class="col-md-12">
                            <label>Address Line 1</label>
                            <input name="address_line_1" type="text" :class="['form-control', errors.address_line_1?'is-invalid':'']" required :value="EditBillingAddress.address_line_1">
                            <small class="form-text text-danger" v-if="errors.address_line_1">{{ errors.address_line_1[0] }}</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12">
                            <label>Address Line 2</label>
                            <input name="address_line_2" type="text" :class="['form-control', errors.address_line_2?'is-invalid':'']" :value="EditBillingAddress.address_line_2">
                            <small class="form-text text-danger" v-if="errors.address_line_2">{{ errors.address_line_2[0] }}</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <label>Email</label>
                            <input name="email" type="text" :class="['form-control', errors.email?'is-invalid':'']" required :value="EditBillingAddress.email">
                            <small class="form-text text-danger" v-if="errors.email">{{ errors.email[0] }}</small>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary text-white">Update</button>
                    <a class="btn btn-light" @click="$router.go(-1)">Cancel</a>
                </form>
            </div>
        </Layout>
    </div>
</template>
<script>
    import Layout from '../../layouts/layout'

    export default {
        data() {
            return {
                EditBillingAddress: {},
                countries: [],
                states: [],
                cities: [],
                is_init_time: true,
                errors: {},
            }
        },
        components: {
            Layout,
        },
        methods: {
            getEditBillingAddress: function (){
                axios.get(`api/institution/get_edit_billing_address/${this.$route.params.id}`).then(res => {
                    this.EditBillingAddress = res.data

                    // This is for Country
                    axios.get('api/institution/get_all_countries').then(res => {
                        this.countries = res.data
                    }).catch(error => {
                        self.ValidtaeForm(error)
                    });

                    // This is for State
                    axios.get(`api/institution/get_all_states_in_countries/${res.data.country_id}`).then(res => {
                        this.states = res.data
                    }).catch(error => {
                        self.ValidtaeForm(error)
                    });

                    // This is for City
                    axios.get(`api/institution/get_all_cities_in_states/${res.data.state_id}`).then(res => {
                        this.cities = res.data
                    }).catch(error => {
                        self.ValidtaeForm(error)
                    });
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            EditBillingAddress_f: function (el){
                var self = this
                let data = self.FilterForm(el.target, 'formdata')
                self.errors = {}
                axios.post('api/institution/post_edit_billing_address', data).then(res => {
                    self.$swal.fire({
                        icon: 'success',
                        text: res.data.message,
                    }).then(function() {
                        self.$router.go(-1);
                    })
                }).catch(error => {
                    self.ValidtaeForm(error)
                });
            },
            get_countries: function (el){
                axios.get('api/institution/get_all_countries').then(res => {
                    this.countries = res.data
                }).catch(error => {
                    self.ValidtaeForm(error)
                });
            },
            get_states: function (el){
                this.states = [];this.cities = []
                let country_id = parseInt(el.target.value)

                axios.get(`api/institution/get_all_states_in_countries/${country_id}`).then(res => {
                    this.states = res.data
                }).catch(error => {
                    self.ValidtaeForm(error)
                });
            },
            get_cities: function (el){
                this.cities = []
                let state_id = parseInt(el.target.value)

                axios.get(`api/institution/get_all_cities_in_states/${state_id}`).then(res => {
                    this.cities = res.data
                }).catch(error => {
                    self.ValidtaeForm(error)
                });
            },
        },
        mounted() {
            this.getEditBillingAddress()
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
</style>
