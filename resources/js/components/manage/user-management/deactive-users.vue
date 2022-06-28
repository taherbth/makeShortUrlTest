<template>
    <div>
        <Layout>
            <div class="page-header">
                <div><h3 class="font-weight-bold">Deactivated Users</h3></div>
                <div>
                    <router-link to="/institution/manage/active-users" class="btn btn-border-primary text-default">View Activated Users</router-link>
                    <a href="#" class="btn btn-primary text-white" data-toggle="modal" data-target="#InviteModal">Send Invitation</a>
                </div>
            </div>

            <div class="table-wrapper">
                <div class="table-responsive">
                    <table class="table table-borderless r-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Member Since</th>
                                <th>Member Type</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, key) in allUsers" :key="key">
                                <td><img :src="user.profile_picture || '/assets/images/user-avatar.png'" class="rounded-circle mr-2" style="width:35px"> {{ user.name }}</td>
                                <td> {{ ConvertDate(user.created_at) }}</td>
                                <td>{{ user.member_type===3?'Institution':(user.member_type===2?'Student':'Facilitator') }}</td>
                                <td class="text-danger">Deactivated</td>
                                <td class="text-right">
                                    <div class="custom-control custom-switch custom-switch-md">
                                        <input type="checkbox" class="custom-control-input" :id="`user-approval-${user.member_type}-${user.id}`" data-toggle="modal" data-target="#ActiveDeactiveModal" @change="getUserId(user.id, user.member_type)">
                                        <label class="custom-control-label" :for="`user-approval-${user.member_type}-${user.id}`"></label>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <pagination align="center" :show-disabled="true" :data="paginationInfo_allUsers" @pagination-change-page="getDeactiveUsers">
                <span slot="prev-nav">Prev</span>
                <span slot="next-nav">Next</span>
            </pagination>
        </Layout>

        <!-- Invite Modal -->
        <div class="modal fade" id="InviteModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form @submit.prevent="InviteUsers">
                        <div class="modal-body">
                            <h5 class="modal-title" id="exampleModalLongTitle">Invite users</h5>
                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <label class="text-uppercase">Email address</label>
                                    <input type="email" name="email" :class="['form-control', errors.emails?'is-invalid':'']" placeholder="Enter email address" data-role="tagsinput">
                                    <small class="form-text text-danger" v-if="errors.email">{{ errors.email[0] }}</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="text-uppercase">role</label>
                                    <select name="role" :class="['form-control', 'option-has-icon', errors.role?'is-invalid':'']">
                                        <option value="1">Facilitator</option>
                                        <option value="2">Student</option>
                                    </select>
                                    <i class="fa fa-chevron-down"></i>
                                    <small class="form-text text-danger" v-if="errors.role">{{ errors.role[0] }}</small>
                                </div>
                                <div class="col-md-12">
                                    <div class="text-secondary">Enter email addresses. Separate email addresses with commas.</div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0">
                            <button type="submit" class="btn btn-primary text-light">Invite</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Active/Deactive Modal -->
        <div class="modal fade" id="ActiveDeactiveModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="/assets/images/user-avatar.png" class="rounded-circle mb-4" style="width:35px">
                        <h5 class="font-weight-bold text-uppercase">DO you want to Reactivate <br> this user?</h5>
                        <p class="text-sm text-neutral">User will be reactivated from billing cycle dd/mm/yyyy</p>
                        <button type="button" class="btn btn-primary-text mb-5" @click="ActiveUser">Activate</button>
                        <button type="button" class="btn btn-light mb-5" style="background: #D3D9DC" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>
<script>
    import Layout from '../../layouts/layout'
    import Pagination from 'laravel-vue-pagination'
    export default {
        data() {
            return {
                paginationInfo_allUsers: {},
                allUsers: [],
                activeUserId: null,
                activeUserType: null,
                errors: {}
            }
        },
        components: {
            Layout,
            Pagination,
        },
        methods: {
            getDeactiveUsers: function (page=1){
                axios.get(`api/institution/get_all_users?query=&is_active=0&page=${page}`).then(res => {
                    this.allUsers = res.data.all_users.data
                    this.paginationInfo_allUsers = res.data.all_users
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            getUserId: function (id, member_type){
                this.activeUserId = id
                this.activeUserType = member_type
            },
            ActiveUser: function (){
                axios.post(`api/institution/post_deactivate_user?status=1&member_type=${this.activeUserType}&user_id=${this.activeUserId}`).then(res => {
                    $('#ActiveDeactiveModal').modal('hide')
                    this.$swal.fire({
                        icon: 'success',
                        text: res.data.message,
                    })
                    this.getDeactiveUsers()
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            ConvertDate: function (RawDate){
                var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
                var d = new Date(RawDate)
                return [d.getDate(), months[d.getMonth()], d.getFullYear()].join(' ');
            },
            InviteUsers: function (el){
                let data = this.FilterForm(el.target, 'formdata')
                this.errors = {}
                axios.post('api/institution/post_invite_users', data).then(res => {
                    $('#InviteModal').modal('hide')
                    this.$swal.fire({
                        icon: 'success',
                        text: res.data.message,
                    })
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            }
        },
        mounted() {
            import('../assets/js/tagsinput')
            this.getDeactiveUsers()
            var self = this
            $('#ActiveDeactiveModal').on('hidden.bs.modal', function () {
                if($(`#user-approval-${self.activeUserType}-${self.activeUserId}`) && $(`#user-approval-${self.activeUserType}-${self.activeUserId}`)[0]){
                    $(`#user-approval-${self.activeUserType}-${self.activeUserId}`)[0].checked = false
                }else{
                    if ($('input[type=checkbox]')[0]) {
                        $('input[type=checkbox]')[0].checked = false
                    }
                }
            });
        },
    }
</script>
<style scoped>
    @import '../assets/css/tagsinput.css';

    .page-header{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        margin-bottom: 20px;
        margin-top: 10px;
    }

    .table-wrapper{
        background: #fff;
        padding: 30px;
        padding-bottom: 0px;
        box-shadow: 0px 1px 3px #38B6FF1A;
        border-radius: 5px;
        margin-bottom: 10px;
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

    .modal-body label{
        font-size: 12px;
        font-weight: 600;
    }
    #InviteModal .modal-body h5{
        font-weight: 550;
        margin-bottom: 30px;
    }
    #InviteModal .modal-body{
        padding: 0 40px;
    }
    #InviteModal .modal-header{
        padding: 12px 12px 0px 0px;
    }
    #InviteModal .modal-footer{
        padding: 12px 40px;
    }
</style>
