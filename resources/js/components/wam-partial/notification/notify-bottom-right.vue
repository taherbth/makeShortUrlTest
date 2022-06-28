<template>
    <div aria-live="polite" aria-atomic="true" >
        <!-- Position it -->
        <div style="position: fixed; bottom: .4em; right: .3em;">
            <!-- Then put toasts within -->
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="10000">
                <div class="toast-header">
                    <strong class="mr-auto">{{content}}</strong>
                    <small class="text-muted ml-3">just now</small>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props:{
        content:{
            type: String,
        }
    }
}
</script>
<style>

</style>
