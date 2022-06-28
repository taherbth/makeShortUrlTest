<template lang="">
    <div>
        <img src="./assets/img/404.png" alt="" />
        <div class="message-box">
            <h1 class="text-danger">Page not found</h1>
            <div class="buttons-con">
                <div class="action-link-wrap">
                    <a @click="$router.go(-1)" class="link-button link-back-button">Go Back</a>
                    <router-link to="/" class="link-button">Go to Home Page</router-link>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {}
</script>
<style scoped>

    .message-box {
        height: 200px;
        width: 380px;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -100px;
        margin-left: 50px;
        color: #FFF;
        font-weight: 300;
    }

    .buttons-con .action-link-wrap {
        margin-top: 40px;
    }
    .buttons-con .action-link-wrap a {
        background: #68c950;
        padding: 8px 25px;
        border-radius: 4px;
        color: #FFF;
        font-weight: bold;
        font-size: 14px;
        transition: all 0.3s linear;
        cursor: pointer;
        text-decoration: none;
        margin-right: 10px
    }
    .buttons-con .action-link-wrap a:hover {
        background: #5A5C6C;
        color: #fff;
    }
    @media (max-width: 992px) {
        img {
            position: absolute;
            width: 100%;
        }
        .message-box {
            top: 50%;
            left: 50%;
            margin-top: -100px;
            margin-left: -190px;
            text-align: center;
        }
    }
</style>
