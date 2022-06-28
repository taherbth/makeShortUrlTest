<template>
    <div>
        <Layout>
            <div class="row">
                <div class="col-md-5 col-xl-3 offset-xl-1">
                     <div class="r-card">
                        <div class="r-card-header"><span>Authority</span></div>
                        <div class="r-card-body">
                            <ul class="profile-view">
                                <li><img :src="User.profile_picture || '/assets/images/user-avatar.png'"></li>
                                <li><p class="user-name">{{ User.name }}</p></li>
                            </ul>
                            <ul class="profile-links">
                                <li>
                                    <router-link to="#/profile"><font-awesome icon="user" class="profile-link-icon" /> Profile</router-link>
                                </li>
                                <li>
                                    <router-link to="#/change-email"><font-awesome icon="envelope" class="profile-link-icon" /> Change Email</router-link>
                                </li>
                                <li>
                                    <router-link to="#/change-password"><font-awesome icon="shield-alt" class="profile-link-icon" /> Change Password</router-link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 col-xl-6">
                    <div v-if='$route.hash === "#/profile"'>
                        <profile-info @change-step="ChangeStep" v-if='PageStep === 1' />
                        <edit-profile @change-step="ChangeStep" v-if='PageStep === 2'/>
                    </div>
                    <div v-if='$route.hash === "#/change-email"'>
                        <email-change />
                    </div>
                    <div v-if='$route.hash === "#/change-password"'>
                        <change-password />
                    </div>
                </div>
            </div>
        </Layout>
    </div>
</template>
<script>
    import Layout from '../../layouts/layout'
    import EditProfile from './edit-profile'
    import ChangePassword from './change-password'
    import ProfileInfo from './profile-info'
    import EmailChange from './email-change'

    export default {
        data() {
            return {
                PageStep: 1
            }
        },
        components: {
            Layout,
            ProfileInfo,
            EditProfile,
            ChangePassword,
            EmailChange,
        },
        methods: {
            ChangeStep: function (el){
                this.PageStep = el
            }
        },
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
        padding-bottom: 0;
        text-align: right;
    }
    .r-card .r-card-header span{
        background: #FFEED7;
        padding: 5px 25px;
        border-radius: 5px;
    }
    .profile-view, .profile-links{
        list-style-type: none;
        padding: 0;
    }
    .profile-view{
        text-align: center;
    }
    .profile-view .user-name{
        margin: 0;
        font-weight: 600;
    }
    .profile-view img{
        width: 64px;
        height: 64px;
        border-radius: 50%;
        margin-bottom: 20px;
    }
    .profile-links{
        list-style-type: none;
    }
    .profile-links li a{
        color: #234153;
        border-radius: 5px;
        display: block;
        padding: 20px 10px 20px 20px;
        text-decoration: none;
    }
    .profile-links li a .profile-link-icon{
        color: #38B6FF;
        margin-right: 10px;
    }
    .profile-links li .router-link-active, .profile-links li .router-link-active .profile-link-icon{
        background: #38B6FF;
        color: #fff;
    }
</style>
