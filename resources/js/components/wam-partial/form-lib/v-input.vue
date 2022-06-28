<template>
    <div class="form-group">
        <label v-if="title" class="text-uppercase">
            {{ title }}
            <span class="text-danger" v-if="required">*</span>
            <tooltip v-if="toolTipOptions.content" :content="toolTipOptions.content" :icon="toolTipOptions.icon" :effect="toolTipOptions.effect" :theme="toolTipOptions.theme" />
        </label>
        <input :type="type" :name="name" :class="['form-control', clearable == true ? 'clearable':'', errorMsg? 'is-invalid':'', successMsg? 'is-valid':'', warningMsg? 'is-warning':'', className]" :id="id" :placeholder="placeHolder" :required="required"/>
        <small class="form-text text-secondary text-left" v-if="Msg">{{ Array.isArray(Msg)? Msg.join():Msg }}</small>
        <small class="form-text text-danger text-left" v-if="errorMsg">{{ Array.isArray(errorMsg)? errorMsg.join():errorMsg }}</small>
        <small class="form-text text-success text-left" v-if="successMsg">{{ Array.isArray(successMsg)? successMsg.join():successMsg }}</small>
        <small class="form-text text-warning text-left" v-if="warningMsg">{{ Array.isArray(warningMsg)? warningMsg.join():warningMsg }}</small>
    </div>
</template>
<script>
    import tooltip from '../tooltip.vue'
    export default {
        components: {
            tooltip,
        },
        props: {
            title: {
                type: String,
                default: ""
            },
            name: {
                type: String,
                default: ""
            },
            type: {
                type: String,
                default: "text"
            },
            id:{
                type: String,
                default: ""
            },
            className:{
                type: String,
                default: ""
            },
            placeHolder:{
                type: String,
                default: ""
            },
            Msg:{
                type: [Array, String],
                default: ""
            },
            errorMsg:{
                type: [Array, String],
                default: ""
            },
            successMsg:{
                type: [Array, String],
                default: ""
            },
            warningMsg:{
                type: [Array, String],
                default: ""
            },
            clearable:{
                type: Boolean,
                default: false
            },
            required:{
                type: Boolean,
                default: false
            },
            toolTipOptions: {
                content:{
                    type : String
                },
                effect: {
                    type: String,
                    default: "scale"
                },
                theme:{
                    type: String,
                    default: 'google'
                },
                default: ""
            }
        },
        mounted() {
            /**
             * Clearable text inputs
            */
            function tog(v){return v ? "addClass" : "removeClass";}
            $(document).on("input", ".clearable", function(){
                $(this)[tog(this.value)]("x");
            }).on("mousemove", ".x", function( e ){
                $(this)[tog(this.offsetWidth-25 < e.clientX-this.getBoundingClientRect().left)]("onX");
            }).on("touchstart click", ".onX", function( ev ){
                ev.preventDefault();
                $(this).removeClass("x onX").val("").change();
            });

        },
    }
</script>
<style scoped>
    .form-control-lg{
        height: 64px;
        font-size: 24px;
    }
    /*
        Clearable text inputs
    */
    .clearable{
        background: #fff url('./clearable-icon.svg') no-repeat right -15px center;
        padding: 3px 25px 3px 4px; /* Use the same right padding (18) in jQ! */
        transition: background 0.4s;
    }
    .clearable.x  { background-position: right 5px center; } /* (jQ) Show icon */
    .clearable.onX{ cursor: pointer; } /* (jQ) hover cursor style */
    .clearable::-ms-clear {display: none; width:0; height:0;} /* Remove IE default X */
</style>
