<template lang="">
    <div>
        <Layout>
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="wrapper-body">
                        <div class="wrapper-header">
                            <h4>Signup This Month</h4>
                        </div>
                        <div class="progress-bar-container">
                            <div id="progress"></div>
                        </div>
                        <div class="text-center">
                            <p class="h5 mb-0">More institutions are signing up this month</p>
                            <p class="text-neutral">Keep it up and always explore.</p>
                        </div>
                    </div>

                    <div class="wrapper-body">
                        <div class="wrapper-header">
                            <h4>Revenue This Month</h4>
                            <a href="">View report</a>
                        </div>
                        <h1>$8750</h1>
                        <img src="/assets/icons/products-sold-graph.svg" style="width:100%">
                    </div>
                </div>

                <div class="col-lg-8 mb-4">

                    <div class="table-wrapper">
                        <div class="table-header">
                            <h4 class="">Registered Institutions <span class="total-users">{{ all_institutions.length }}</span></h4>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless r-table r-table-text-center">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Institution Name</th>
                                        <th>Members</th>
                                        <th>Member Since</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, key) in all_institutions" :key="key">
                                        <td>{{ key+1 }}</td>
                                        <td>{{ item.institution_name }}</td>
                                        <td>{{ item.member_count }}</td>
                                        <td>{{ ConvertDate(item.created_at) }}</td>
                                    </tr>
                                    <tr v-if="all_institutions.length<1">
                                        <td class="r-card-empty-registered-institutions" colspan="4">
                                            <empty-recent-responses class="mb-4"/>
                                            <h6 class="font-weight-bold">No Registered Institution to show</h6>
                                            <p class="text-secondary mb-0">We'll show Registered Institution once you get started!</p>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <router-link to="/authority/institution" class="btn btn-border-primary text-default">View Details</router-link>
                    </div>


                </div>
            </div>
        </Layout>
    </div>
</template>
<script>
    import Layout from '../../layouts/layout'
    import EmptyRecentResponses from '../../wam-partial/icons/empty-recent-responses'

    export default {
        data() {
            return {
                all_institutions:[]
            }
        },
        components: {
            Layout,
            EmptyRecentResponses
        },
        methods: {
            get_all_institutions: function (){
                axios.get(`/api/authority/get_all_institutions?query=`).then(res => {
                    this.all_institutions = res.data.data
                }).catch(error => {
                    this.ValidtaeForm(error)
                })

            },
            InitProgress: function (){
                $(document).ready(function() {
                    axios.get(`/api/admin/get_admin_progress`).then(res => {
                        $("#progress").percircle({
                            progressBarColor: "#15B33F",
                            percent: 50
                        })
                    }).catch(error => {
                        this.ValidtaeForm(error)
                    })
                })
            },
            ConvertDate: function (RawDate){
                var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
                var d = new Date(RawDate)
                return [d.getDate(), months[d.getMonth()], d.getFullYear()].join(' ');
            },
        },
        mounted() {
            this.InitProgress()
            this.get_all_institutions()
        }
    }
</script>
<style>
    #progress>span{
        color: #234153;
    }
</style>
<style scoped>
    .wrapper-body{
        width: 100%;
        display: block;
        background: #fff;
        padding: 30px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .wrapper-body .wrapper-header{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
    .wrapper-body .wrapper-header h4, .wrapper-body h1{
        font-weight: bold;
    }
    .wrapper-body .wrapper-header a{
        color: #909FA8;
        text-decoration: underline;
    }
    .progress-bar-container{
        display: flex !important;
        flex-direction: row;
        justify-content: center;
        margin: 55px 0 30px 0;
    }
    #progress{
        transform: scale(1.4);
    }

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
        margin-bottom: 30px;
    }
    .table-header h4{
        font-weight: bold;
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
    .r-card-empty-registered-institutions {
        text-align: center;
        padding: 15vh 0px;
    }
</style>
