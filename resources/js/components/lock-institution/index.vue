<template>
    <div>
        <Layout>
            <div class="lock-profile">
                <div class="center">
                    <img src="./assets/img/profile-lock.png" alt="">
                    <h3 class="font-weight-bold text-uppercase mt-4 mb-4">Its time to make things <br> official</h3>
                    <router-link to="/institution/manage/user-list-estimated-bill" class="btn btn-primary-text">Add Payment Method</router-link>
                </div>
            </div>
        </Layout>
    </div>
</template>
<script>
    import Layout from '../layouts/layout'
    export default {
        components: {
            Layout
        }
    }
</script>
<style scoped>
    .lock-profile{
        width: 100%;
        height: 85vh;
        position: relative;
        background: #fff;
        margin-bottom: 1rem;
    }
    .lock-profile .center {
        width: 100%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
    .lock-profile .center img{
        max-width: 100%;
    }
</style>
