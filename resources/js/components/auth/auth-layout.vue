<template>
    <div class="wam-body">
        <div class="wam-signup-wrapper">
            <!-- start left porsion -->
            <div class="wam-column-signup wam-column-signup-left set-background-image">
                <a href="/">
                    <img src="/assets/images/wam-academy-auth-logo.png" alt="" />
                </a>
                <div class="text-light text-left" v-if="(['/auth/login', '/auth/forget-password'].includes($route.path))">
                    <span class="red-mark"></span>
                    <h2 class="text-uppercase font-weight-bold">Build an amazing <br>World for todays <br>young stars</h2>
                    <p class="text-disable text-sm">Designed to reach more students in less time for all <br> teachable moments.</p>
                </div>
                <div class="text-light text-left" v-if="(['/auth/registation'].includes($route.path))">
                    <span class="red-mark"></span>
                    <h2 class="text-uppercase font-weight-bold">Sign up and enjoy a <br> 14 day free trial. <br> fully featured</h2>
                    <p class="text-disable text-sm">Designed to reach more students in less time for all <br> teachable moments.</p>
                </div>
            </div>
            <!-- end left porsion -->

            <!-- start right porsion -->
            <div class="wam-column-signup wam-column-signup-right">
                <div class="text-right desktop-navbar">
                    <p class="d-inline text-neutral text-sm">{{ signTitle }}</p>
                    <router-link :to="signAddr" class="ml-3 btn btn-border-primary">{{ signText }}</router-link>
                </div>
                <div class="mobile-navbar">
                    <router-link to="/">
                        <img src="/assets/images/wam-academy-auth-logo.png" alt="" />
                    </router-link>
                    <router-link :to="signAddr" tag="a" class="btn btn-sm btn-secondary">{{ signText }}</router-link>
                </div>
                <router-view @setLayout="inItPage" ></router-view>
                <p class="text-secondary text-center mt-5">All Rights Reserved | WAM Academy</p>
            </div>
            <!-- end right porsion -->
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                signTitle:null,
                signText:null,
                signAddr:'/auth'
            }
        },
        methods: {
            inItPage: function (val){
                this.signTitle = val.signTitle
                this.signText = val.signText
                this.signAddr = val.signAddr
            }
        },
        mounted() {
            console.log('WAM is running...');
        },
    }
</script>
<style scoped>
    @import "./style.css";
</style>
