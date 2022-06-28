<template>
    <div>
        <Layout>
            <div class="page-header">
                <h3 class="font-weight-bold"><font-awesome icon="angle-left" class="text-default cursor-pointer" @click="$router.go(-1)" /> {{ institution_details.institution_details.institution_name }} </h3>
                <button type="button" class="btn btn-primary text-white" data-toggle="modal" data-target="#DeactiveModal" v-if="institution_details.institution_details.status">Deactivate Institution</button>
                <button type="button" class="btn btn-primary text-white" data-toggle="modal" data-target="#ActiveModal" v-else>Activate Institution</button>
            </div>

            <div class="r-card">
                <p class="r-card-header">Institution Details</p>
                <div class="row">
                    <div class="col-lg-6">
                        <table>
                            <tr>
                                <td>
                                    <p class="text-title">Name</p>
                                    <p>{{ institution_details.institution_details.institution_name }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-title">Established In</p>
                                    <p>{{ ConvertDate(institution_details.institution_details.established_at) }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-title">Initial Students</p>
                                    <p>{{ institution_details.institution_details.institution_primary_student_quantity }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-title">Initial Facilitators</p>
                                    <p>{{ institution_details.institution_details.institution_primary_facilitator_quantity }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-title">Status</p>
                                    <p>
                                        <span v-if="institution_details.institution_details.status" class="text-success">Activated</span>
                                        <span v-else class="text-danger">Deactivated</span>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <table>
                            <tr>
                                <td>
                                    <p class="text-title">Address</p>
                                    <p>{{ institution_details.institution_details.institution_address }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-title">Member Since</p>
                                    <p>{{ ConvertDate(institution_details.institution_details.created_at) }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-title">Current Students</p>
                                    <p>{{ institution_details.institution_student_count }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-title">Current Facilitators</p>
                                    <p>{{ institution_details.institution_facilitator_count }}</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="r-card">
                <p class="r-card-header">Admin Information</p>
                <div class="row">
                    <div class="col-lg-6">
                        <table>
                            <tr>
                                <td>
                                    <p class="text-title">Full name</p>
                                    <p>{{ institution_details.institution_details.admin_name }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-title">Institution URL</p>
                                    <p>{{ institution_details.institution_details.institution_code }}</p>
                                </td>
                            </tr>
                            <tr>  
                                <td>
                                    <p class="text-title">User type</p>
                                    <p>{{  `Institution` }}</p>
                                </td>                                
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <table>
                            <tr>
                                <td>
                                    <p class="text-title">Email</p>
                                    <p>{{ institution_details.institution_details.email }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-title">Member since</p>
                                    <p>{{ ConvertDate(institution_details.institution_details.created_at) }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-title">Account expiry date</p>
                                    <p>{{ ConvertDate(institution_details.institution_details.trial_ends_at) }}</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="r-card" v-if="institution_details['billing details']">
                <p class="r-card-header">Billing</p>
                <div class="row">
                    <div class="col-lg-6">
                        <table>
                            <tr>
                                <td>
                                    <p class="text-title">Address</p>
                                    <p>{{ institution_details['billing details'].address_line_1 }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-title">Currency</p>
                                    <p>{{ `Dollar (USD)` }}</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <table>
                            <tr>
                                <td>
                                    <p class="text-title">Email</p>
                                    <p>{{ institution_details['billing details'].email }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-title">Last Payment</p>
                                    <p>{{ ConvertDate(institution_details.last_payment) }}</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </Layout>
        <!-- Active Modal -->
        <div class="modal fade" id="ActiveModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="/assets/images/user-avatar.png" class="rounded-circle mb-4" style="width:70px">
                        <h5 class="font-weight-bold text-uppercase">DO you want to activate <br> this institution?</h5>
                        <p class="text-sm text-neutral">Institution will be deactivated from billing cycle dd/mm/yyyy <br> till you choose to reactivate them</p>
                        <button type="button" class="btn btn-primary-text mb-5" @click="ActiveUser">Activate</button>
                        <button type="button" class="btn btn-light mb-5" style="background: #D3D9DC" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Deactive Modal -->
        <div class="modal fade" id="DeactiveModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="/assets/images/user-avatar.png" class="rounded-circle mb-4" style="width:70px">
                        <h5 class="font-weight-bold text-uppercase">DO you want to deactivate <br> this institution?</h5>
                        <p class="text-sm text-neutral">Institution will be deactivated from billing cycle dd/mm/yyyy <br> till you choose to reactivate them</p>
                        <button type="button" class="btn btn-primary-text mb-5"
                         @click="DeactiveUser">Deactivate</button>
                        <button type="button" class="btn btn-light mb-5" style="background: #D3D9DC" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import Layout from '../layouts/layout'
    import Pagination from 'laravel-vue-pagination'
    export default {
        data() {
            return {
               institution_details: {}
            }
        },
        components: {
            Layout,
            Pagination,
        },
        methods: {
            get_institution_details: function (){
                axios.get(`api/authority/get_institution_details/${this.$route.params.id}`).then(res => {
                    this.institution_details = res.data
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            DeactiveUser: function (){
                axios.post(`api/authority/deactivate_institution/${this.$route.params.id}`).then(res => {
                    $('#DeactiveModal').modal('hide')
                    this.$swal.fire({
                        icon: 'success',
                        text: res.data.message,
                    })
                    this.get_institution_details()
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            ActiveUser: function (){
                axios.post(`api/authority/reactivate_institution/${this.$route.params.id}`).then(res => {
                    $('#ActiveModal').modal('hide')
                    this.$swal.fire({
                        icon: 'success',
                        text: res.data.message,
                    })
                    this.get_institution_details()
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            ConvertDate: function (RawDate){
                if(RawDate){
                    var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
                    var d = new Date(RawDate)
                    return [d.getDate(), months[d.getMonth()], d.getFullYear()].join(' ');
                }else{
                    return "Unknown"
                }
            },
        },
        mounted() {
            this.get_institution_details();
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
</style>
