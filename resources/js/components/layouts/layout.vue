<template>
    <div id="main-layout">
        <Header />
        <div class="container">
            <slot></slot>
        </div>
    </div>
</template>
<script>
    import Header from './header'
    export default {
        components: {
            Header,
        }
    }
</script>
<style scoped>
    @media(max-width: 1140px){
        .container{
            padding: 0 10px;
            padding-top:128px;
        }
    }
    @media(min-width: 1140px){
        .container{
            padding-top:75px;
        }
    }

</style>
