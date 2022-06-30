<template>
    <div class="wam-navbar">
        <div class="container">
            <!-- this is for big screen navbar like big laptop, tablet and desktop  -->
            <section class="wam-header-xl">
                <div class="part-1">
                    <div class="dropdown">
                        <h3> <a href="/" class="mr-3">Url Shortner</a> </h3>                    
                    </div>
                </div>
            </section>

            <!-- this is for small screen navbar like mobile, cell phone, laptop and tablet  -->
            <section class="wam-header-sm">
                <div class="top-part">
                    <a href="/" ><img src="/assets/images/wam-academy-logo.png" /></a>
                    <a href="javascript:void(0)" id="sidebarCollapse"><font-awesome icon="bars" /></a>
                </div>
               
            </section>
        </div>
        <div id="overlay" @click="overlayOff()"></div>
    </div>
</template>
<script>
    
    export default {
        data(){
            return {
                
            }
        },
        components: {
           
        },
        methods:{            

        },
        mounted() {
            
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
