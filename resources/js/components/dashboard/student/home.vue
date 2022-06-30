<template lang="">
    <div>
        <Layout>
            <div class="row">
                <div class="col-lg-12 mb-4 pt-80 pb-80">
                    <div class="advertisement-container">
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12 status-message">
                                <h3>Welcome To Url Shortner</h3>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 status-message">
                                <p class="text-neutral">Start learning new things and share your experience with others</p>
                            </div>
                        </div>
                        <form @submit.prevent="makeShortUrl">
                            <div class="row">                            
                                <div class="col-2 col-md-2 col-lg-2 offset-5">
                                    <input type="text" name="url"  v-model="url" placeholder="Write your url here">
                                </div>
                                <div class="col-2 col-md-2 col-lg-2 offset-1">
                                    <button type="submit" class="btn btn-primary-text">Make short url</button>
                                </div>         
                            </div>
                        </form>
                        <div class="row" v-if="short_url">                
                            <div class="col-12 col-md-12 col-lg-12 status-message">
                                <h3>{{ response_message }}</h3><br/>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 status-message">
                                <h5>{{ base_url }}/{{ short_url }}</h5>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>            
        </Layout>
    </div>
</template>
<script>
    import Layout from '../../layouts/layout';

    export default {
        data() {
            return {
                url: "",
                short_url: null,
                base_url: process.env.MIX_API_URL,
                response_message: "",
                error:null
            }
        },
        components: {
            Layout,            
        },
        methods: {
            makeShortUrl: function(){
                this.short_url = null;
                axios.post('/api/makeShortlink', {url: this.url}).then(response => {
                    if (response.status === 201) {   
                        this.short_url = response.data.short_code;
                        this.response_message = response.data.message;
                        this.$swal.fire({
                            icon: 'success',
                            title: 'Yeh',
                            text: response.data.message,
                        })
                    }
                }).catch(error => {                   
                    if (error.response.status === 422) {   
                        this.error = error;                     
                        this.$swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: error.response.data.message,
                        })
                    }
                });
            },
        },
        mounted() {
            
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
        display: block;
        overflow: hidden;
        background: #fff;
        width: 100%;
        padding: 30px;
        border-radius: 5px;
    }
    .wrapper-body.chart-wrapper{
        padding-bottom: 50px;
    }
    .progress-bar-container{
        display: flex !important;
        flex-direction: row;
        justify-content: center;
        margin: 60px 0;
    }
    #progress{
        transform: scale(1.5);
    }
    #monthly-activity{
        height: 300px;
    }


    /* this is for dashboard */

    .advertisement-container {
        display: block !important;
        background: #fff;
        /* min-height: 360px; */
        overflow: hidden;
        border-radius: 5px;
    }
    .advertisement-container .status-message {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .advertisement-container .status-message div{
        margin-left: 3rem;
    }
    .advertisement-container .status-media {
        position: relative;
        text-align: center;
    }
    .advertisement-container .status-media .advertiser-img {
        width: 100%;
        height: 400px;
        border-radius: 5px;
        filter: brightness(0.44) contrast(0.92);
        clip-path: polygon(100% 0%, 65% 0%, 2.4% 34.43%, 2.21% 34.72%, 2.09% 34.87%, 1.93% 35.33%, 1.92% 35.59%, 1.94% 36.08%, 2.04% 36.5%, 2.15% 36.89%, 2.34% 37.33%, 19.03% 99.76%, 100% 100%);
    }
    .advertisement-container .status-media .overlay-img {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 5px;
        clip-path: polygon(100% 0%, 39% 0.16%, 0.63% 65.85%, 0.45% 66.14%, 0.42% 66.45%, 0.37% 66.75%, 0.36% 67.17%, 0.38% 67.5%, 0.48% 68.08%, 0.59% 68.31%, 0.68% 68.59%, 25% 100%, 100% 100%);
    }
    .advertisement-container .status-media .overlay-contents {
        position: absolute;
        left: 20%;
        right: 20px;
        bottom: 15px;
    }
    .advertisement-container .status-media .overlay-contents svg{
        color: #fff;
        margin-bottom: 30px;
    }
    .advertisement-container .status-media .overlay-contents h5{
        color: #fff;
        font-weight: 700;
    }
    .advertisement-container .status-media .overlay-contents p{
        color: #fff;
        font-size: 13px;
        opacity: 0.7;
    }

    @media (max-width: 992px) {
        .advertisement-container .status-message div{
            margin: 0;
            padding: 20px;
            margin-bottom: 40px;
        }
    }
    /* end dashboard  */

    /* empty case */
    .r-card-empty-recent-responses{
        text-align: center;
        padding: 120px 0px;
    }
    .r-card-empty-progress{
        text-align: center;
        padding: 60px 0px;
    }
</style>
