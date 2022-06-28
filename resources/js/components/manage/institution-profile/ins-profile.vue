<template>
    <div>
        <Layout>
            <div class="page-wrapper">
                <div class="text-center">
                    <h3>Institution Profile</h3>
                    <p class="text-secondary">This is an overview of your Institutional Profile. You are able to edit your profile here.</p>
                    <img :src="InstitutionProfile.institution_profile_picture || '/assets/images/user-avatar.png'" class="rounded-circle mb-4" style="width:150px">
                    <table class="text-left">
                        <tr>
                            <td>
                                <p class="text-neutral">Name</p>
                                <p class="mb-4">{{ InstitutionProfile.institution_name }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="text-neutral">Address</p>
                                <p class="mb-4">{{ InstitutionProfile.institution_address }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="text-neutral">Member Since</p>
                                <p class="mb-4">{{ InstitutionProfile.created_at?ConvertDate(InstitutionProfile.created_at):'Unknown' }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="text-neutral">Established In</p>
                                <p class="mb-4">{{ InstitutionProfile.established_at?ConvertDate(InstitutionProfile.established_at):'Unknown' }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="text-neutral">Current Students</p>
                                <p class="mb-4">{{ InstitutionProfile.number_of_students }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="text-neutral">Current Facilitators</p>
                                <p class="mb-4">{{ InstitutionProfile.number_of_facilitators }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <router-link to="institution-profile/edit" class="btn btn-primary-text col-12 mt-4">Edit Profile</router-link>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </Layout>
    </div>
</template>
<script>
    import Layout from '../../layouts/layout'
    export default {
        data() {
            return {
                InstitutionProfile: {}
            }
        },
        components: {
            Layout,
        },
        methods: {
            getInstitutionProfile: function (){
                axios.get(`api/institution/get_institution_profile`).then(res => {
                    this.InstitutionProfile = res.data
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
            this.getInstitutionProfile()
        },
    }
</script>
<style scoped>
    .page-wrapper{
        display: flex;
        justify-content: center;
        height: 85vh;
        min-height: 1150px;
        background: #fff;
        margin-bottom: 10px;
    }
    .page-wrapper h3{
        margin-top: 64px;
        font-weight: bold;
    }
    .page-wrapper table{
        margin: 0 auto;
    }
    .page-wrapper table p{
        margin: 0;
    }
</style>
