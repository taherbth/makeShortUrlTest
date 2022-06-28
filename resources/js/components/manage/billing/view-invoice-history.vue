<template>
    <div>
        <Layout>
             <div class="page-header">
                <div><h3 class="font-weight-bold">Invoice History</h3></div>
            </div>

            <div class="table-wrapper">
                <div class="table-responsive">
                    <table class="table table-borderless r-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Invoice ID</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, key) in InvoiceHistory" :key="key">
                                <td>{{ timeConverter(item.created) }}</td>
                                <td>{{ item.invoice }}</td>
                                <td class="text-uppercase">{{ `${item.currency} ${item.amount/100}` }}</td>
                                <td class="text-success" v-if="item.paid">Paid</td>
                                <td class="text-success" v-else>Due</td>
                                <td class="text-right"> <a :href="item.receipt_url" target="_blank" ><font-awesome icon="eye" /></a> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <pagination align="center" :show-disabled="true" :data="paginationInfo_InvoiceHistory" @pagination-change-page="getInvoiceHistory">
                <span slot="prev-nav">Prev</span>
                <span slot="next-nav">Next</span>
            </pagination>
        </Layout>
    </div>
</template>
<script>
    import Layout from '../../layouts/layout'
    import Pagination from 'laravel-vue-pagination'
    export default {
        data() {
            return {
                paginationInfo_InvoiceHistory: {},
                InvoiceHistory: [],
            }
        },
        components: {
            Layout,
            Pagination
        },
        methods: {
            getInvoiceHistory: function (page=1){
                axios.get(`api/institution/get_all_invoice_history?page=${page}`).then(res => {
                    this.InvoiceHistory = res.data.data
                    this.paginationInfo_InvoiceHistory = res.data
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            timeConverter: function (UNIX_timestamp){
                var a = new Date(UNIX_timestamp * 1000);
                var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                var year = a.getFullYear();
                var month = months[a.getMonth()];
                var date = a.getDate();
                var hour = a.getHours();
                var min = a.getMinutes();
                var sec = a.getSeconds();
                var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
                return time;
            }
        },
        mounted() {
            this.getInvoiceHistory()
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
</style>
