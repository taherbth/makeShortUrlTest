<template>
    <div class="page-wrapper">
        <div class="text-center">
            <h3>Profile</h3>
            <p class="text-secondary">Manage your personal settings from this section</p>
            <img :src="User.profile_picture || '/assets/images/user-avatar.png'" class="rounded-circle mb-4" style="width:150px">
            <table class="text-left">
                <tr>
                    <td>
                        <p class="text-neutral">Full name</p>
                        <p class="mb-4">{{ ProfileInfo.name }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="text-neutral">Institution name</p>
                        <p class="mb-4">{{ ProfileInfo.institution_name }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="text-neutral">Institution code</p>
                        <p class="mb-4">{{ ProfileInfo.institution_code }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="text-neutral">User type</p>
                        <p class="mb-4">Admin</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="text-neutral">Member since</p>
                        <p class="mb-4">{{ ConvertDate(ProfileInfo.created_at) }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="javascript:void(0)" class="btn btn-primary-text col-12" @click="ChangeStep">Edit Profile</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                ProfileInfo: {}
            }
        },
        methods: {
            ChangeStep: function (){
                this.$emit('change-step', 2)
            },
            getProfile: function (){
                axios.get('api/facilitator/get_profile').then(res => {
                    this.ProfileInfo = res.data
                }).catch(error => {
                    this.ValidtaeForm(error)
                });
            },
            ConvertDate: function (RawDate){
                var d = new Date(RawDate)
                return [d.getDate(), d.getMonth()+1, d.getFullYear()].join('/');
            },
        },
        mounted() {
            this.getProfile()
        },
    }
</script>
<style scoped>
    .page-wrapper{
        display: flex;
        justify-content: center;
        background: #fff;
        margin-bottom: 10px;
        padding: 20px;
        padding-bottom: 30px;
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
