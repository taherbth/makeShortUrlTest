<template>
    <div>
        <Layout>
            <div class="form-container">
                <form @submit.prevent="CreateBillingAddress" style="width:500px">
                    <h3>Add Billing Contact</h3>
                    <p class="payment-info">Please enter your billing information below</p>

                    <!-- this alll hidden input field are required -->
                    <input name="country_id" type="hidden" id="country_id">
                    <input name="state_id" type="hidden" id="state_id">
                    <input name="city_id" type="hidden" id="city_id">

                    <div class="form-row">
                        <div class="col-md-6">
                            <label>Country</label>
                            <select name="country" :class="['form-control', 'option-has-icon', errors.country?'is-invalid':'']" required @change="get_states">
                                <option value="">Select one</option>
                                <option v-for="(country, key) in countries" :key="key" :data-country-id="country.id" :value="country.sortname" v-html="country.name"></option>
                            </select>
                            <i class="fa fa-chevron-down"></i>
                            <small class="form-text text-danger" v-if="errors.country">{{ errors.country[0] }}</small>
                        </div>
                        <div class="col-md-6">
                            <label>zip/postal</label>
                            <input type="text" name="zip_code" :class="['form-control', errors.zip_code?'is-invalid':'']" required>
                            <small class="form-text text-danger" v-if="errors.zip_code">{{ errors.zip_code[0] }}</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <label>state</label>
                            <select name="state" :class="['form-control', 'option-has-icon', errors.state?'is-invalid':'']" required @change="get_cities">
                                <option value="">Select one</option>
                                <option v-for="(state, key) in states" :key="key" :data-state-id="state.id" :value="state.name" v-html="state.name"></option>
                            </select>
                            <i class="fa fa-chevron-down"></i>
                            <small class="form-text text-danger" v-if="errors.state">{{ errors.state[0] }}</small>
                        </div>
                        <div class="col-md-6">
                            <label>city</label>
                            <select name="city" :class="['form-control', 'option-has-icon', errors.city?'is-invalid':'']" required @change="get_city_id">
                                <option value="">Select one</option>
                                <option v-for="(city, key) in cities" :key="key" :data-city-id="city.id" :value="city.name" v-html="city.name"></option>
                            </select>
                            <i class="fa fa-chevron-down"></i>
                            <small class="form-text text-danger" v-if="errors.city">{{ errors.city[0] }}</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12">
                            <label>Address Line 1</label>
                            <input name="address_line_1" type="text" :class="['form-control', errors.address_line_1?'is-invalid':'']" required>
                            <small class="form-text text-danger" v-if="errors.address_line_1">{{ errors.address_line_1[0] }}</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12">
                            <label>Address Line 2</label>
                            <input name="address_line_2" type="text" :class="['form-control', errors.address_line_2?'is-invalid':'']">
                            <small class="form-text text-danger" v-if="errors.address_line_2">{{ errors.address_line_2[0] }}</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <label>Email</label>
                            <input name="email" type="text" :class="['form-control', errors.email?'is-invalid':'']" required>
                            <small class="form-text text-danger" v-if="errors.email">{{ errors.email[0] }}</small>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary text-white">Save</button>
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
                countries: [],
                states: [],
                cities: [],
                errors: {},
            }
        },
        components: {
            Layout,
        },
        methods: {
            CreateBillingAddress: function (el){
                var self = this
                let data = self.FilterForm(el.target, 'formdata')
                self.errors = {}
                axios.post('api/institution/post_create_billing_address', data).then(res => {
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
            get_countries: function (){
                axios.get('api/institution/get_all_countries').then(res => {
                    this.countries = res.data
                }).catch(error => {
                    self.ValidtaeForm(error)
                });
            },
            get_states: function (el){
                this.states = [];this.cities = []
                let country_id = parseInt(el.target.selectedOptions[0].getAttribute('data-country-id'))
                document.getElementById('country_id').value = country_id

                axios.get(`api/institution/get_all_states_in_countries/${country_id}`).then(res => {
                    this.states = res.data
                }).catch(error => {
                    self.ValidtaeForm(error)
                });
            },
            get_cities: function (el){
                this.cities = []
                let state_id = parseInt(el.target.selectedOptions[0].getAttribute('data-state-id'))
                document.getElementById('state_id').value = state_id

                axios.get(`api/institution/get_all_cities_in_states/${state_id}`).then(res => {
                    this.cities = res.data
                }).catch(error => {
                    self.ValidtaeForm(error)
                });
            },
            get_city_id: function (el){
                let city_id = parseInt(el.target.selectedOptions[0].getAttribute('data-city-id'))
                document.getElementById('city_id').value = city_id
            },
        },
        mounted() {
            this.get_countries()
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
