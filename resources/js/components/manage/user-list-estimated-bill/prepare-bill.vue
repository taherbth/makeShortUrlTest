<template>
    <div>
        <Layout>
            <div class="row">
                <div class="col-xl-8">
                    <div class="table-wrapper">
                        <div class="table-header">
                            <h4 class="">Current Users <span class="total-users">{{ total_users_count }}</span></h4>
                            <div class="form-group search-box">
                                <input type="text" class="form-control" placeholder="Search users" v-model="search_query" @keyup="get_all_users">
                                <search-icon />
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless r-table r-table-text-center">
                                <thead>
                                    <tr>
                                        <th>
                                            Selected ({{ TotalSelectedUsers() }})
                                            <div class="custom-control custom-checkbox d-inline">
                                                <input type="checkbox" class="custom-control-input" id="check-all" @change="check_all_user" />
                                                <label class="custom-control-label custom-bg" for="check-all"></label>
                                            </div>
                                        </th>
                                        <th>Current Users</th>
                                        <th>Member Since</th>
                                        <th>Member Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, key) in allUsers" :key="key">
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" :checked="is_active_checkbox(item.member_type, item.id)" :id="`check-${item.member_type}-${item.id}`" :data-type="item.member_type" :data-id="item.id" @change="check_user">
                                                <label class="custom-control-label custom-bg" :for="`check-${item.member_type}-${item.id}`"></label>
                                            </div>
                                        </td>
                                        <td><img :src="item.profile_picture || '/assets/images/user-avatar.png'" class="rounded-circle mr-2" style="width:35px"> {{ item.name }}</td>
                                        <td>{{ ConvertDate(item.created_at) }}</td>
                                        <td>
                                            <span v-if="item.member_type === 1">Facilitator</span>
                                            <span v-if="item.member_type === 2">Student</span>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <pagination align="center" :show-disabled="true" :data="paginationInfo_allUsers" @pagination-change-page="get_all_users">
                        <span slot="prev-nav">Prev</span>
                        <span slot="next-nav">Next</span>
                    </pagination>
                </div>
                <div class="col-xl-4">
                    <div class="table-wrapper">
                        <div class="table-header">
                            <h4 class="">Bill Estimates</h4>
                        </div>
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
                                        <td>{{ TotalSelectedUsers() }} Users until this month</td>
                                        <td class="text-right font-weight-bold">{{ `$${(package_pricing.unit_amount/100) || 0} X ${TotalSelectedUsers()}` }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ `Tax/ VAT(${package_pricing.tax_rate || 0}%)` }}</td>
                                        <td class="text-right font-weight-bold">{{ (((((package_pricing.unit_amount/100)*TotalSelectedUsers())/100)*package_pricing.tax_rate)) || 0 }}$</td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td class="text-right text-primary font-weight-bold h3">${{ (((package_pricing.unit_amount/100)*TotalSelectedUsers()) + ((((package_pricing.unit_amount/100)*TotalSelectedUsers())/100)*package_pricing.tax_rate)) || 0 }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <p class="text-right text-sm text-secondary">Next Billing Date: {{ NextPaymentDate() }}</p>
                        <div class="text-right">
                            <a href="javascript:void(0)" class="btn btn-primary text-white" @click="ComfirmUsers">Confirm and Continue</a>
                        </div>
                    </div>
                </div>

            </div>
        </Layout>
    </div>
</template>
<script>
    import Layout from '../../layouts/layout'
    import SearchIcon from '../../wam-partial/icons/search-icon.vue'
    import Pagination from 'laravel-vue-pagination'

    export default {
        data() {
            return {
                paginationInfo_allUsers: {},
                allUsers: [],
                total_users_count: 0,
                package_pricing: {},
                checked_all_user: false,
                enabled_user_list: [],
                disabled_user_list: [],
                search_query: ''
            }
        },
        components: {
            Layout,
            SearchIcon,
            Pagination
        },
        methods: {
            get_all_users: function (page=1){
                axios.get(`api/institution/get_all_users?query=${this.search_query}&page=${page}`).then(res => {
                    this.allUsers = res.data.all_users.data
                    this.paginationInfo_allUsers = res.data.all_users
                    this.total_users_count = res.data.count
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            get_package_pricing: function (page=1){
                axios.get(`api/institution/get_package_pricing`).then(res => {
                    this.package_pricing = res.data
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
            ConvertDate: function (RawDate){
                var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
                var d = new Date(RawDate)
                return [d.getDate(), months[d.getMonth()], d.getFullYear()].join(' ');
            },
            check_all_user: function (el){
                this.disabled_user_list = []
                this.enabled_user_list = []
                this.checked_all_user = el.target.checked
            },
            check_user: function (el){
                let user_type = parseInt(el.target.getAttribute('data-type'))
                let user_id = parseInt(el.target.getAttribute('data-id'))

                if (el.target.checked) {
                    this.enabled_user_list.push({
                        member_type: user_type,
                        id: user_id,
                    })
                    let findIndex = this.disabled_user_list.findIndex(item => {
                        return (item.id === user_id && item.member_type === user_type)
                    })
                    if(findIndex !== -1){
                        this.disabled_user_list.splice(findIndex , 1)
                    }
                } else {
                    this.disabled_user_list.push({
                        member_type: user_type,
                        id: user_id,
                    })
                    let findIndex = this.enabled_user_list.findIndex(item => {
                        return (item.id === user_id && item.member_type === user_type)
                    })
                    if(findIndex !== -1){
                        this.enabled_user_list.splice(findIndex , 1)
                    }
                }
            },
            is_active_checkbox: function (type, id){
                if (this.checked_all_user) {
                    if (this.disabled_user_list.length) {
                        return this.SearchForDisabled(this.disabled_user_list, id, type)
                    } else {
                        return true
                    }
                } else {
                    if (this.enabled_user_list.length) {
                        return this.SearchForEnabled(this.enabled_user_list, id, type)
                    } else {
                        return false
                    }
                }
            },
            SearchForDisabled: function (array, user_id, user_type){
                var res = true
                for (var i=0; i < array.length; i++) {
                    if (array[i].id === user_id && array[i].member_type === user_type) {
                        res = false
                        break;
                    }
                }
                return res
            },
            SearchForEnabled: function (array, user_id, user_type){
                var res = false
                for (var i=0; i < array.length; i++) {
                    if (array[i].id === user_id && array[i].member_type === user_type) {
                        res = true
                        break
                    }
                }
                return res
            },
            ComfirmUsers: function (){
                let data = {
                    check_all_users: this.checked_all_user,
                    disabled_user_list: this.disabled_user_list,
                    enable_users_list: this.enabled_user_list,
                }
                axios.post(`api/institution/post_enable_disable_users`, data).catch(error => {
                    this.ValidtaeForm(error)
                })
                this.$router.push('/institution/manage/billing')
            },
            TotalSelectedUsers: function(){
                return this.checked_all_user?(this.paginationInfo_allUsers.total-this.disabled_user_list.length):(this.enabled_user_list.length)
            },
            NextPaymentDate: function (){
                var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
                var d = new Date();
                d.setDate(d.getDate() + 30);
                return [d.getDate(), months[d.getMonth()], d.getFullYear()].join(' ');
            }
        },
        mounted() {
            this.get_all_users()
            this.get_package_pricing()
        },
    }
</script>
<style scoped>
    .table-wrapper{
        background: #fff;
        padding: 30px;
        padding-bottom: 25px;
        box-shadow: 0px 1px 3px #38B6FF1A;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    .table-header{
        display: flex;
        justify-content: space-between;
        padding: 0 10px;
        margin-bottom: 50px;
    }
    .table-header h4{
        font-weight: bold;
    }
    .table-header .search-box{
        position: relative;
        width: 25%;
        margin: 0;
    }
    .table-header .search-box input{
        padding-left: 32px;
    }
    .table-header .search-box svg{
        position: absolute;
        top: 12px;
        left: 12px;
    }
    .total-users{
        background: #E1F4FFCC;
        padding: 4px;
        border: 1px solid #38B6FF;
        border-radius: 5px;
        font-size: 20px;
    }
    .r-table th {
        color: #909FA8;
        font-weight: normal;
        padding-top: 0;
    }
    .r-table tr th, .r-table tr td{
        border-bottom: 1px solid #E8EBED;
    }
    .r-table.r-table-text-center tr th, .r-table.r-table-text-center tr td{
        text-align: center;
    }
    .r-table tr:last-child td{
        border-bottom: 0;
    }

    .custom-checkbox .custom-control-input:checked ~ .custom-control-label.custom-bg::before{
        background: #2A365C;
        border-color: #2A365C;
    }
</style>
