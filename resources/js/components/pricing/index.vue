<template>
    <div>
        <Layout>
            <div class="page-header">
                <h3 class="font-weight-bold">Pricing Packages <span class="total-users">1</span></h3>
            </div>
            <div class="row">
                <div class="col-lg-4 offset-lg-4">
                    <div class="r-card">
                        <div class="r-card-header">
                            <h1>${{ (PackageInfo.unit_amount/100) || 'Unknown' }}</h1>
                            <p>Per user <br> Per month</p>
                        </div>
                        <ul>
                            <li>Provides students access to the powerful stories of mentors</li>
                            <li>Measures student character growth through their writing</li>
                            <li>Impactful stories divided by key areas of character development</li>
                            <li>Content designed for maximum student engagement</li>
                            <li>Uniquely designed around student input to appeal to middle and high school students</li>
                        </ul>
                        <div class="text-center">
                            <router-link to="/authority/pricing/primary" class="btn btn-border-primary text-default">Edit Package</router-link>
                        </div>
                    </div>
                </div>
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
                PackageInfo: {}
            }
        },
        components: {
            Layout,
            Pagination,
        },
        methods: {
            get_package_pricing: function (){
                axios.get(`api/authority/get_package_pricing`).then(res => {
                    this.PackageInfo = res.data
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            },
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
        text-align: center;
        font-weight: bold;
        text-transform: uppercase;
        border-bottom: 1px solid #E8EBED;
        padding-bottom: 1rem;
    }
    .r-card-header h1{
        font-weight: bold;
        color: #38B6FF;
    }
    .page-header{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        margin-bottom: 30px;
        margin-top: 10px;
    }
    .total-users{
        background: #E1F4FFCC;
        padding: 4px;
        border: 1px solid #38B6FF;
        border-radius: 5px;
        font-size: 20px;
    }
    .r-card ul{
        margin: 30px 0 50px 0;
    }
    .r-card ul li {
        padding-left: 1.3em;
        list-style-type: none;
        color: #909FA8;
        margin-bottom: 20px;
    }
    .r-card ul li:before {
        content: "\f058"; /* FontAwesome Unicode */
        font-family: "Font Awesome 5 Free";
        display: inline-block;
        margin-left: -1.3em; /* same as padding-left set on li */
        width: 1.3em; /* same as padding-left set on li */
        color: #B2BCC3;
    }
</style>
