<template>
    <div>
        <div class="mb-4" v-if="question_type == 4">
            <p class="text-sm text-icon mb-2">{{ date }}</p>
            <p class="mb-2">{{ title }}</p>
            <p class="text-secondary mb-2 d-inline-block" v-for="(item, i) in response" :key="i">
                <span v-if="i < 1">{{ item.student_response }}</span>
                <span v-else>, {{ item.student_response }}</span>
            </p>
            <p v-if="status === 0" >
                <star-rating text-class="text-xs mt-1 text-neutral" :inline="true" :read-only="true" :star-size="12" active-color="#FFAA38" :rating="rating" /> <span class="text-sm text-icon mt-1 ml-1">Scores have not been provided yet.</span>
            </p>
            <star-rating v-if="status === 1" text-class="text-xs mt-1 text-neutral" :read-only="true" :star-size="12" active-color="#FFAA38" :rating="rating" />
        </div>

        <div class="mb-4" v-else>
            <p class="text-sm text-icon mb-2">{{ date }}</p>
            <p class="mb-2">{{ title }}</p>
            <p class="text-secondary mb-2" >{{ response }}</p>
            <p v-if="status === 0" >
                <star-rating text-class="text-xs mt-1 text-neutral" :inline="true" :read-only="true" :star-size="12" active-color="#FFAA38" :rating="rating" /> <span class="text-sm text-icon mt-1 ml-1">Scores have not been provided yet.</span>
            </p>
            <star-rating v-if="status === 1" text-class="text-xs mt-1 text-neutral" :read-only="true" :star-size="12" active-color="#FFAA38" :rating="rating" />
        </div>
    </div>
</template>
<script>
    import StarRating from 'vue-star-rating'
    export default {
        props: {
            date: {
                type: String
            },
            title: {
                type: String
            },
            question_type: {
                type: [String, Number]
            },
            response: {
                type: [String, Array]
            },
            status: {
                type: Number
            },
            rating: {
                type: Number,
                default: 0
            }
        },
        components: {
            StarRating,
        },
    }
</script>
<style>

</style>
