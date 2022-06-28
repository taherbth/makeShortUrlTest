<template>
    <div>
        <Layout>
            <div class="row">
                <div class="col-md-6 col-xl-4">
                    <div class="r-card">
                        <h5 class="r-card-header"> <font-awesome icon="users" class="text-default mr-3"/> Users</h5>
                        <div class="r-card-body">
                            <p class="text-neutral mb-5">In this section, you can add new users, view the list of active and deactivated users. Also you can choose to deactivate/reactivate users from their respective lists.</p>
                            <div class="text-right">
                                <router-link to="/institution/manage/active-users" class="btn btn-info">View Users</router-link>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="r-card">
                        <h5 class="r-card-header"> <font-awesome icon="credit-card" class="text-default mr-3"/> Billing</h5>
                        <div class="r-card-body">
                            <p class="text-neutral mb-5">In this section, you can add/edit payment methods, billing contacts and view the estimated bill for your institution. You can also view your monthly invoice history and generate the pdf.</p>
                            <div class="text-right">
                                <router-link to="/institution/manage/billing" class="btn btn-info">View Billing</router-link>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="r-card">
                        <h5 class="r-card-header"> <font-awesome icon="user-circle" class="text-default mr-3"/> Institution Profile</h5>
                        <div class="r-card-body">
                            <p class="text-neutral mb-5">In this section, you can edit your institutions name, logo and location.</p>
                            <div class="text-right">
                                <router-link to="/institution/manage/institution-profile" class="btn btn-info">View Institution Profile</router-link>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-6 col-xl-4">
                    <div class="r-card">
                        <h5 class="r-card-header"> <font-awesome icon="user-check" class="text-default mr-3"/> Approval</h5>
                        <div class="r-card-body">
                            <div class="user-approval">
                                <p class="text-neutral">Users require approval</p>
                                <div class="custom-control custom-switch custom-switch-md">
                                    <input type="checkbox" class="custom-control-input" id="user-approval" :checked="UserApprovalStatus" @change="getUserApprovalStatus">
                                    <label class="custom-control-label" for="user-approval"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </Layout>
    </div>
</template>
<script>
    import Layout from '../layouts/layout'
    export default {
        data() {
            return {
                UserApprovalStatus: 0
            }
        },
        components: {
            Layout,
        },
        methods: {
            getUserApprovalStatus: function (el=null){
                if (el === null) {
                    var request_url = `/api/institution/get_user_approval_status`
                } else {
                    let isChecked = el.target.checked===true?1:0
                    var request_url = `/api/institution/get_user_approval_status?user_approval=${isChecked}`
                }
                axios.get(request_url).then(res => {
                    this.UserApprovalStatus = res.data
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
        },
        mounted() {
            this.getUserApprovalStatus()
        }
    }
</script>
<style scoped>
    .r-card{
        width: 100%;
        height: auto;
        background: #fff;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .r-card .r-card-header, .r-card .r-card-body{
        padding: 30px 0;
        margin: 0 30px;
    }
    .r-card .r-card-header{
        font-weight: bold;
        border-bottom: 1px solid #F0F2F2;
    }
    .user-approval{
        display: flex;
        justify-content: space-between;
    }
</style>
