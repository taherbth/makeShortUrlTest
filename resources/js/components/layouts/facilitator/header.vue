<template>
    <div class="wam-navbar">
        <div class="container">
            <!-- this is for big screen navbar like big laptop, tablet and desktop  -->
            <section class="wam-header-xl">
                <div class="part-1">
                    <div class="dropdown">
                        <a href="/dashboard" class="mr-3"><img src="/assets/images/wam-academy-logo.png" /></a>
                        <a href="#" data-toggle="dropdown" @click="overlayOn()">
                            <search-icon /> Search video
                        </a>
                        <form class="form-group has-search dropdown-menu dropdown-menu-right" @submit.prevent="SearchAllData" :style="searchFieldStyle">
                            <input class="form-control form-control-lg" placeholder="Search" @keyup="SearchKeyPress">
                            <searchSubmitIcon class="field_icon search-submit-xl" />
                            <ul class="list-group search-list-container mt-1">
                                <a href="javascript:void(0)" class="list-group-item" v-for="item in searchData" :key="item.id" @click="getSearchQuery(item.id)">{{ item.title }}</a>
                            </ul>
                        </form>
                    </div>
                </div>
                <div class="part-2">
                    <router-link to="/dashboard" class="btn nav-item dashboard"> <material-dashboard /> Dashboard </router-link>
                    <router-link to="/facilitator/watch" class="btn nav-item watch"> <font-awesome icon="play-circle" class="text-danger"/> Watch </router-link>
                    <router-link to="/facilitator/responses" class="btn nav-item response"> <font-awesome icon="bars" class="text-warning"/> Responses </router-link>
                    <router-link to="/facilitator/video/upload" class="btn nav-item uploads"> <metro-file-upload /> Uploads </router-link>
                </div>
                <div class="part-3 dropdown">
                    <div class="dropdown mr-3">
                        <a href="#" class="has_notification" data-toggle="dropdown" @click="getNotifications">
                            <font-awesome icon="bell" />
                            <span :class="[notificationStatus?'notification_badge':'']"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="notification-title">
                                <p class="d-inline-block">Notifications</p>
                                <span class="text-secondary">{{ unread_notifications }} new</span>
                            </div>
                            <div class="notification-body">
                                <all-notifications :notifications="notifications" />
                            </div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown"> <span v-html="User.dynamicName"></span> <img :src="User.profile_picture || '/assets/images/user-avatar.png'" /></a>
                        <div class="dropdown-menu dropdown-menu-right profile-section">
                            <div class="profile-header">
                                <img :src="User.profile_picture || '/assets/images/user-avatar.png'" class="rounded-circle" style="width:64px;">
                                <h6>{{ User.name }}</h6>
                                <p>Facilitator</p>
                            </div>
                            <ul class="list-group profile-links" style="list-style:none">
                                <li class="list-items"><router-link to="/facilitator/profile#/profile" class="col-12">Personal Settings</router-link></li>
                                <li class="list-items"><a href="#" class="col-12" @click="LogOut">Log out</a></li>
                            </ul>
                            <div class="profile-footer">
                                <p>All Rights Reserved</p>
                                <p>Powered By We Are Marcus</p>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <!-- this is for small screen navbar like mobile, cell phone, laptop and tablet  -->
            <section class="wam-header-sm">
                <div class="top-part">
                    <a href="/" ><img src="/assets/images/wam-academy-logo.png" /></a>
                    <a href="javascript:void(0)" id="sidebarCollapse"><font-awesome icon="bars" /></a>
                </div>
                <hr style="1px solid #F5F7FF; margin: 0">
                <div class="bottom-part">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" @click="overlayOn()">
                            <search-icon /> Search video
                        </a>
                        <form class="form-group has-search dropdown-menu dropdown-menu-right" @submit.prevent="SearchAllData" :style="searchFieldStyle">
                            <input class="form-control form-control-lg" placeholder="Search" @keyup="SearchKeyPress">
                            <searchSubmitIcon class="field_icon search-submit-md" />
                            <ul class="list-group search-list-container mt-1">
                                <a href="javascript:void(0)" class="list-group-item" v-for="item in searchData" :key="item.id" @click="getSearchQuery(item.id)">{{ item.title }}</a>
                            </ul>
                        </form>
                    </div>

                    <div class="part-3">
                        <div class="dropdown mr-3">
                            <a href="#" class="has_notification" data-toggle="dropdown" @click="getNotifications">
                                <font-awesome icon="bell" />
                                <span :class="[notificationStatus?'notification_badge':'']"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" :style="notificationDropdownStyle">
                                <div class="notification-title">
                                    <p class="d-inline-block">Notifications</p>
                                    <span class="text-secondary">{{ unread_notifications }} new</span>
                                </div>
                                <div class="notification-body">
                                    <all-notifications :notifications="notifications" />
                                </div>
                            </div>
                        </div>
                        <a href="#" class="mr-1"> <span v-html="User.dynamicName"></span> <img class="user-logo" :src="User.profile_picture || '/assets/images/user-avatar.png'" /></a>
                    </div>
                </div>
            </section>
        </div>

        <!-- small screen sidebar  -->
        <nav id="sidebar">
            <div class="row">
                <div class="col-3 side-part"></div>
                <div class="col-9 sidebar-content-wrapper">
                    <div class="sidebar-top">
                        <div id="dismiss"><font-awesome icon="bars" /></div>

                        <div class="sidebar-header">
                            <ul style="list-style: none;padding: 0">
                                <li><img class="user-img" :src="User.profile_picture || '/assets/images/user-avatar.png'" /></li>
                                <li><p class="mb-0 mt-2">{{ User.name }}</p></li>
                                <li><p class="text-sm text-secondary">Institution</p></li>
                            </ul>
                        </div>

                        <ul class="list-unstyled components">
                            <li><router-link to="/dashboard">Dashboard</router-link></li>
                            <li><router-link to="/facilitator/watch">Watch</router-link></li>
                            <li><router-link to="/facilitator/responses">Responses</router-link></li>
                            <li><router-link to="/facilitator/video/upload">Uploads</router-link></li>
                            <li><router-link to="/facilitator/profile#/profile">Profile Settings</router-link></li>
                            <li><a href="javascript:void(0)" @click="LogOut">Logout</a></li>
                        </ul>
                    </div>

                    <div class="sidebar-bottom">
                        <p class="text-center">All Rights Reserved <br> Powered By We Are Marcus</p>
                    </div>
                </div>
            </div>
        </nav>
        <div id="overlay" @click="overlayOff()"></div>
    </div>
</template>
<script>
    import searchIcon from '../../wam-partial/icons/search-icon'
    import searchSubmitIcon from '../../wam-partial/icons/serarch-submit-icon'
    import materialDashboard from '../../wam-partial/icons/material-dashboard'
    import metroFileUpload from '../../wam-partial/icons/metro-file-upload'
    import allNotifications from '../all-notifications'
    export default {
        data(){
            return {
                notificationDropdownStyle: {},
                searchFieldStyle: {},
                searchData: [],
                isNotificationOpen: false,
                notifications: [],
                unread_notifications: 0
            }
        },
        components: {
            searchIcon,
            searchSubmitIcon,
            materialDashboard,
            metroFileUpload,
            allNotifications
        },
        methods:{
            generateSearchFieldStyle: function(){
                if(window.innerWidth >= 1140){
                    return {
                        minWidth: (((window.innerWidth-10)/100)*90) + "px",
                        padding: 0,
                        marginLeft: ((((window.innerWidth-10)/100)*10)/2) + "px",
                    }
                }else{
                    return {
                        minWidth: (window.innerWidth-20) + "px",
                        padding: 0,
                        marginLeft: "5px",
                    }
                }
            },
            generateNotificationDropdownStyle: function(){
                if(window.innerWidth){
                    return {
                        minWidth: (window.innerWidth-10) + "px",
                    }
                }else{
                    return {
                        minWidth: (window.innerWidth-10) + "px",
                    }
                }
            },
            getNotifications: function (){
                if (this.isNotificationOpen) {
                    this.isNotificationOpen = false
                } else {
                    axios.get('/api/facilitator/get_all_notifications').then((res) => {
                        this.notifications = res.data.notifications
                        this.unread_notifications = res.data.unread_notifications
                        this.isNotificationOpen = true
                        this.notificationStatus = null
                    }).catch((err) => {
                        this.ValidtaeForm(err)
                    })
                }
            },
            SearchAllData: function (el){
                let query = el.target[0].value

                if(this.$route.query.topic && this.$route.query.topic != null && this.$route.query.topic != ""){
                    this.$router.push(`/facilitator/watch?query=${query}&topic=${this.$route.query.topic}`)
                }else{
                    this.$router.push(`/facilitator/watch?query=${query}`)
                }
            },
            SearchKeyPress: function (el){
                let query = el.target.value

                if(this.$route.query.topic && this.$route.query.topic != null && this.$route.query.topic != ""){
                    axios.get(`/api/facilitator/get_courses?query=${query}&topic=${this.$route.query.topic}`).then(res => {
                        this.searchData = res.data.data?res.data.data.slice(0, 8):[]
                    })
                }else{
                    axios.get(`/api/facilitator/get_courses?query=${query}`).then(res => {
                        this.searchData = res.data.data?res.data.data.slice(0, 8):[]
                    })
                }
            },
            getSearchQuery: function (id){
                this.overlayOff()
                this.$router.push(`/facilitator/watch/play/${id}`)
            },
            overlayOn: function (){
                document.getElementById("overlay").style.display = "block";
            },
            overlayOff: function (){
                document.getElementById("overlay").style.display = "none";
                this.searchData = []
            },
            LogOut: function (){
                axios.post('/api/facilitator/logout').then(success => {
                    this.LocalLogout()
                }).catch(error => {
                    this.ValidtaeForm(error)
                })
            }


        },
        mounted() {
            let self = this

            self.searchFieldStyle = self.generateSearchFieldStyle()
            self.notificationDropdownStyle = self.generateNotificationDropdownStyle()

            window.addEventListener("resize", function(){
                self.notificationDropdownStyle = self.generateNotificationDropdownStyle()
                self.searchFieldStyle = self.generateSearchFieldStyle()
            });

            $(document).ready(function () {
                $('#dismiss').on('click', function () {
                    $('#sidebar').removeClass('active');
                });

                $('#sidebarCollapse').on('click', function () {
                    $('#sidebar').addClass('active');
                    $('.collapse.in').toggleClass('in');
                    $('a[aria-expanded=true]').attr('aria-expanded', 'false');
                });
            });

            // this is for submit search form by clicking search button
            $(document).ready(function(){
                $(".search-submit-xl, .search-submit-md").click((el) => {
                    let query = el.currentTarget.previousElementSibling.value
                    if(self.$route.query.topic && self.$route.query.topic != null && self.$route.query.topic != ""){
                        self.$router.push(`/facilitator/watch?query=${query}&topic=${self.$route.query.topic}`)
                    }else{
                        self.$router.push(`/facilitator/watch?query=${query}`)
                    }
                })
            })

            // this is for get notification status
            self.GetNotificationStatus()

            // this is for avoid dropdown close
            $('.part-3').on('hide.bs.dropdown', function (e) {
                if (e.clickEvent && e.clickEvent.target.className!="nav-link") {
                e.preventDefault();
                }
            });
        },
    }
</script>
<style scoped>
    @import url('../assets/css/style.css');
    @import url('../assets/css/sidebar.css');

    .part-3{
        display: flex;
        align-items: center;
    }
    .search-list-container a.list-group-item{
        color: #234153;
    }
    .field_icon{
        position: absolute;
        cursor: pointer;
        color: #234153;
        top: 15px;
        right: 20px;
    }
    .profile-section{
        min-width:247px !important;
        padding-left:0 !important;
        padding-right:0 !important;
    }
    .profile-header, .profile-footer{
        text-align: center;
    }
    .profile-header h6{
        margin: 0;
        font-weight: bold;
        font-size: 14px;
    }
    .profile-header p{
        font-size: 14px;
        color: #617686;
    }
    .profile-links li{
        margin: 0 10px;
    }
    .profile-links li a{
        display: inline-block;
        padding: 12px;
        font-size: 14px;
    }
    .profile-links li a:hover{
        background: #F7F8F9;
        border-radius: 5px;
    }
    .profile-footer{
        border-top: 1px solid #E2F4FE;
    }
    .profile-footer p{
        margin: 0;
        color: #B2BCC3;
        font-size: 12px;
    }

    /* if backdrop support: very transparent and blurred */
    @supports ((-webkit-backdrop-filter: blur(50px)) or (backdrop-filter: blur(50px))) {
        .wam-navbar {
            -webkit-backdrop-filter: blur(50px);
            backdrop-filter: blur(50px);
        }
    }
</style>
