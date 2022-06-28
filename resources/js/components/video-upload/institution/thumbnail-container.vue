<template>
    <div class="brands">
        <div class="row">
            <div class="col">
                <div class="brands_slider_container">
                    <div class="owl-carousel owl-theme brands_slider">

                        <a v-for="item in thumbnails.course_thumbnails" :key="item" class="owl-item slider-item" href="javascript:void(0)">
                            <div class="brands_item d-flex flex-column justify-content-center">
                                <img
                                    :src="`https://wam.academy/thumbnails/${thumbnails.course_id}/${item}`"
                                    @click="setThumbnailURL"
                                />
                            </div>
                            <font-awesome icon="check-circle" class="text-primary d-none" />
                        </a>

                       <a class="owl-item" href="javascript:void(0)">
                           <div class="brands_item d-flex flex-column justify-content-center">
                               <img src="../assets/img/upload-img.png" @click="chooseThumbnail"/>
                               <form method="post" enctype="multipart/form-data" class="d-none">
                                   <input type="file" name="" id="upload-thumble" accept="image/*" @change="uploadThumbnail" />
                               </form>
                           </div>
                       </a>


                    </div>
                    <!-- Brands Slider Navigation -->
                    <div class="brands_nav brands_prev badge-course"> <font-awesome icon="angle-double-left" /> </div>
                    <div class="brands_nav brands_next badge-course"> <font-awesome icon="angle-double-right" /> </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import ('../assets/js/owl.carousel')

    export default {
        props: {
            thumbnail_info: {
                type: Object
            }
        },
        data() {
            return {
                thumbnails: this.thumbnail_info,
            }
        },
        watch: {
            thumbnail_info: function () {
                this.thumbnails= this.thumbnail_info
            }
        },
        methods: {
            setThumbnailURL: function (el){
                this.$emit('getActiveThumbnail', el.target.getAttribute('src'))

                let elements = document.getElementsByClassName('slider-item')
                for (let index = 0; index < elements.length; index++) {
                    elements[index].classList.remove('active')
                }
                el.target.parentElement.parentElement.classList.add('active')
            },
            chooseThumbnail: function (){
                document.getElementById('upload-thumble').click()
            },
            uploadThumbnail: function (el){
                let file = el.target.files[0]
                let data = new FormData()
                data.append('thumbnail', file)
                axios.post('/api/institution/upload_thumbnails', data).then(res => {
                    let course_id = parseInt(res.data.video_id)
                    let thumbnails = []
                    for(let i=0; i<res.data.thumbnails.length;i++){
                        thumbnails[i] = res.data.thumbnails[i].replace(`/var/www/login/public/thumbnails/${course_id}/`, '')
                    }
                    let thumbnail_info = {
                        course_id: course_id,
                        course_thumbnails: Object.assign({}, thumbnails)
                    }
                    this.thumbnails = thumbnail_info
                }).catch(error => {
                    this.ValidtaeForm(error)
                })

            },
            inItSlider: function (){
                $(document).ready(function(){
                    if($('.brands_slider').length){
                        window.$owl = $('.brands_slider');
                        window.$owl.owlCarousel({
                            loop:false,
                            autoplay:false,
                            autoplayTimeout:5000,
                            nav:false,
                            dots:false,
                            autoWidth:true,
                            items:20,
                            margin:8
                        });

                        if($('.brands_prev').length){
                            var prev = $('.brands_prev');
                            prev.on('click', function(){
                                window.$owl.trigger('prev.owl.carousel');
                            });
                        }

                        if($('.brands_next').length){
                            var next = $('.brands_next');
                            next.on('click', function(){
                                window.$owl.trigger('next.owl.carousel');
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
            window.$owl.trigger('destroy.owl.carousel')
            this.inItSlider()

            let elements = document.getElementsByClassName('slider-item')
            for (let index = 0; index < elements.length; index++) {
                elements[index].classList.remove('active')
            }
            if(elements.length > 0){
                elements[0].classList.add('active')
                this.$emit('getBannerImage', elements[0].children[0].children[0].getAttribute('src'))
            }

        },
    }
</script>
<style scoped>
    @import '../assets/css/owl.carousel.min.css';

    .slider-item{
        position: relative;
        width: 100%;
    }
    .slider-item.active img{
        filter: brightness(44%) contrast(91%);
    }
    .slider-item.active svg{
        display: inline !important;
        position: absolute;
        top: 40px;
        left: 42px;
        font-size: 24px;
        background: #fff;
        border-radius: 50%;
    }



    .brands {
        width: 100%;
        padding-top: 10px;
    }

    .brands_slider_container {
        height: 154px;
        border: solid 1px #e8e8e8;
        box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
        padding-left: 60px;
        padding-right: 60px;
        background: #fff;
        border-radius: 5px;
    }

    .brands_slider {
        height: 100%;
        margin-top: 25px
    }

    .brands_item {
        height: 100%
    }

    .brands_item img {
        width: 108px;
        height: 100px;
        border-radius: 5px;
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
