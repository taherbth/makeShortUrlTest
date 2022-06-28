<template>
    <div>
        <Layout>
            <div class="page-header">
                <div><h3 class="font-weight-bold">Registered Institutions <span class="total-users">{{ pagination_info_all_institutions.total }}</span></h3></div>
            </div>

            <div class="table-wrapper">
                <div class="table-responsive">
                    <table class="table table-borderless r-table">
                        <thead>
                            <tr>
                                <th>SI</th>
                                <th>Institution Name</th>
                                <th>Members</th>
                                <th>Member Since</th>
                                <th>Institution Admin</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, key) in all_institutions" :key="key">
                                <td>{{ ((pagination_info_all_institutions.current_page*pagination_info_all_institutions.per_page)+key+1)-pagination_info_all_institutions.per_page }}</td>
                                <td><router-link :to="`/authority/institution/${item.id}`">{{ item.institution_name }}</router-link></td>
                                <td>{{ item.member_count }}</td>
                                <td>{{ ConvertDate(item.created_at) }}</td>
                                <td>{{ item.admin_name }}</td>
                                <td>
                                    <span class="text-success" v-if="item.status">Activated</span>
                                    <span class="text-danger" v-else>Deactivated</span>
                                </td>
                            </tr>
                            <tr v-if="all_institutions.length<1">
                                <td class="r-card-empty-registered-institutions" colspan="6">
                                    <empty-recent-responses class="mb-4"/>
                                    <h6 class="font-weight-bold">No Registered Institution to show</h6>
                                    <p class="text-secondary mb-0">We'll show Registered Institution once you get started!</p>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <pagination align="center" :show-disabled="true" :data="pagination_info_all_institutions" @pagination-change-page="get_all_institutions">
                <span slot="prev-nav">Prev</span>
                <span slot="next-nav">Next</span>
            </pagination>
        </Layout>

    </div>
</template>
<script>
    import Layout from '../layouts/layout'
    import Pagination from 'laravel-vue-pagination'
    import EmptyRecentResponses from '../wam-partial/icons/empty-recent-responses'

    export default {
        data() {
            return {
                pagination_info_all_institutions:{},
                all_institutions:[]
            }
        },
        components: {
            Layout,
            Pagination,
            EmptyRecentResponses
        },
        methods: {
            get_all_institutions: function (page=1){
                axios.get(`/api/authority/get_all_institutions?query=&page=${page}`).then(res => {
                    this.all_institutions = res.data.data
                    this.pagination_info_all_institutions = res.data
                }).catch(error => {
                    this.ValidtaeForm(error)
                })

            },
            ConvertDate: function (RawDate){
                var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
                var d = new Date(RawDate)
                return [d.getDate(), months[d.getMonth()], d.getFullYear()].join(' ');
            },
        },
        mounted() {
            this.get_all_institutions()
        },
    }
</script>
<style scoped>
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
    .total-users{
        background: #E1F4FFCC;
        padding: 4px;
        border: 1px solid #38B6FF;
        border-radius: 5px;
        font-size: 20px;
    }

    .r-card-empty-registered-institutions {
        text-align: center;
        padding: 28vh 0px;
    }
</style>
