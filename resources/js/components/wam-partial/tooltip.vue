<template>
    <a v-tippy="{ trigger : 'click', animation : effect, arrow : true, theme: theme}" :content="content">
        <svg :style="`fill: ${iconColor}`" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><defs></defs><rect class="a" width="20" height="20"/><g transform="translate(-1.375 -1.375)"><path class="b" :style="`fill: ${iconColor}`" d="M11.375,4.99A6.382,6.382,0,1,1,6.86,6.86,6.358,6.358,0,0,1,11.375,4.99m0-1.615a8,8,0,1,0,8,8,8,8,0,0,0-8-8Z"/><path class="b" d="M18.139,18.688H16.523V13.88h1.615Zm0-6.385H16.523V10.688h1.615Z" transform="translate(-5.956 -3.313)"/></g></svg>
    </a>
</template>
<script>
    export default {
        name: "Tooltip",
        props: {
            content:{
                type : String
            },
            iconColor: {
                type: String,
                default: '#b2bcc3'
            },
            effect: {
                type: String,
                default: "scale"
            },
            theme:{
                type: String,
                default: 'light-border'
            }
        }
    }
</script>
<style scoped>
    @import url('https://unpkg.com/tippy.js@4/themes/light-border.css');
    @import url('https://unpkg.com/tippy.js@4/themes/light.css');
    @import url('https://unpkg.com/tippy.js@4/themes/google.css');
    @import url('https://unpkg.com/tippy.js@4/themes/translucent.css');
    a{
        cursor: pointer;
    }
    .a{fill:none;}
</style>
