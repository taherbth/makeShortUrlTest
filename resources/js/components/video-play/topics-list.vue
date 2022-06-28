<template>
    <div class="brands">
        <div class="row">
            <div class="col">
                <div class="brands_slider_container">
                    <div class="owl-carousel owl-theme brands_slider">
                        <router-link :to="generate_link('', tabActive)" class="owl-item badge-topic">
                            <div class="brands_item d-flex flex-column justify-content-center"><span>All</span></div>
                        </router-link>                        
                        <router-link v-for="(topic, i) in topics" :key="i" :to="generate_link(topic, tabActive)" class="owl-item badge-topic">
                            <div class="brands_item d-flex flex-column justify-content-center"><span>{{ topic }}</span></div>
                        </router-link>
                    </div>
                    <!-- Brands Slider Navigation -->
                    <div class="brands_nav brands_prev badge-topic"> <font-awesome icon="angle-double-left" /> </div>
                    <div class="brands_nav brands_next badge-topic"> <font-awesome icon="angle-double-right" /> </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import ('./assets/js/owl.carousel.js')

    export default {
        props: {
            topics: {
                type: Array
            },
            tabActive:{
                type: String
            }
        },
        methods: {
            generate_link: function (topic, tabActive){
                if(this.$route.query.query && this.$route.query.query != null && this.$route.query.query != ""){
                    if (this.User.role === 3) {
                        return `/institution/watch?query=${this.$route.query.query}&topic=${topic}&tabActive=${tabActive}`
                    } else if (this.User.role === 2) {
                        return `/watch?query=${this.$route.query.query}&topic=${topic}&tabActive=${tabActive}`
                    } else{
                        return `/facilitator/watch?query=${this.$route.query.query}&topic=${topic}&tabActive=${tabActive}`
                    }
                }else{
                    if (this.User.role === 3) {
                        return `/institution/watch?topic=${topic}&tabActive=${tabActive}`
                    } else if (this.User.role === 2) {
                        return `/watch?topic=${topic}&tabActive=${tabActive}`
                    } else{
                        return `/facilitator/watch?topic=${topic}&tabActive=${tabActive}`
                    }
                }
            },
            inItSlider: function (){
                $(document).ready(function(){
                    if($('.brands_slider').length){
                        Window.$owl = $('.brands_slider');
                        Window.$owl.owlCarousel({
                            loop:false,
                            autoplay:false,
                            autoplayTimeout:5000,
                            nav:true,
                            dots:false,
                            autoWidth:true,
                            items:20,
                            margin:8
                        });

                        if($('.brands_prev').length){
                            var prev = $('.brands_prev');
                            prev.on('click', function(){
                                Window.$owl.trigger('prev.owl.carousel');
                            });
                        }

                        if($('.brands_next').length){
                            var next = $('.brands_next');
                            next.on('click', function(){
                                Window.$owl.trigger('next.owl.carousel');
                            });
                        }
                    }
                });
            }
        },
        mounted() {
            this.inItSlider()
        },
        updated() {
            Window.$owl?Window.$owl.trigger('destroy.owl.carousel'):false
            this.inItSlider()
        }
    }
</script>
<style scoped>
    @import './assets/css/owl.carousel.min.css';
    .badge-topic.router-link-exact-active{
        background: #234153;
        color: #fff;
    }
    .badge-topic{
        background: #fff;
        padding: 6px 12px;
        color: #234153;
        text-decoration: none;
        border-radius: 5px;
    }
    .brands {
        width: 100%;
        padding-bottom: 30px
    }

    .brands_slider_container {
        height: 40px;
        padding-left: 60px;
        padding-right: 60px;
    }

    .brands_slider {
        height: 100%;
    }

    .brands_item {
        height: 100%
    }

    .brands_item img {
        max-width: 100%
    }

    .brands_nav {
        position: absolute;
        top: 50%;
        -webkit-transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -o-transform: translateY(-50%);
        transform: translateY(-50%);
        padding: 5px;
        cursor: pointer
    }

    .brands_nav i {
        color: #e5e5e5;
        -webkit-transition: all 200ms ease;
        -moz-transition: all 200ms ease;
        -ms-transition: all 200ms ease;
        -o-transition: all 200ms ease;
        transition: all 200ms ease
    }

    .brands_nav:hover i {
        color: #676767
    }

    .brands_prev {
        left: 40px
    }

    .brands_next {
        right: 40px
    }

    /**
 * Owl Carousel v2.3.4
 * Copyright 2013-2018 David Deutsch
 * Licensed under: SEE LICENSE IN https://github.com/OwlCarousel2/OwlCarousel2/blob/master/LICENSE
 */
/*
 * 	Default theme - Owl Carousel CSS File
 */
.owl-theme .owl-nav {
  margin-top: 10px;
  text-align: center;
  -webkit-tap-highlight-color: transparent; }
  .owl-theme .owl-nav [class*='owl-'] {
    color: #FFF;
    font-size: 14px;
    margin: 5px;
    padding: 4px 7px;
    background: #D6D6D6;
    display: inline-block;
    cursor: pointer;
    border-radius: 3px; }
    .owl-theme .owl-nav [class*='owl-']:hover {
      background: #869791;
      color: #FFF;
      text-decoration: none; }
  .owl-theme .owl-nav .disabled {
    opacity: 0.5;
    cursor: default; }

.owl-theme .owl-nav.disabled + .owl-dots {
  margin-top: 10px; }

.owl-theme .owl-dots {
  text-align: center;
  -webkit-tap-highlight-color: transparent; }
  .owl-theme .owl-dots .owl-dot {
    display: inline-block;
    zoom: 1;
    *display: inline; }
    .owl-theme .owl-dots .owl-dot span {
      width: 10px;
      height: 10px;
      margin: 5px 7px;
      background: #D6D6D6;
      display: block;
      -webkit-backface-visibility: visible;
      transition: opacity 200ms ease;
      border-radius: 30px; }
    .owl-theme .owl-dots .owl-dot.active span, .owl-theme .owl-dots .owl-dot:hover span {
      background: #869791; }

</style>
