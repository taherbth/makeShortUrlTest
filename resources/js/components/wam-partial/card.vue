<template>
    <div class="card bg-white">
        <div class="card-header bg-white">
            <slot name="header"></slot>
        </div>
        <div class="card-body">
            <slot name="body"></slot>
        </div>
    </div>
</template>
<script>
    export default {
        props:{
            leftTitle:{
                type: String,
                default: ''
            },
            rightTitle: {
                type: String,
                default: ''
            }
        }
    }
</script>
<style scoped>
    .card-header{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
    .card-header p{
        margin: 0;
    }
    .card-header {
        padding: 0.4rem .85rem;
    }
</style>
