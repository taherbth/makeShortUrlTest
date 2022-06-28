<template>
    <div>
        <input type="hidden" name="created_at" :value="question.created_at" />
        <!-- this is text question type -->
        <div class="form-group" v-if="question.question_type == 1">
            <label>{{ question.question }}</label>
            <textarea disabled v-html="question.your_response" class="form-control" rows="5"></textarea>
        </div>

        <!-- this is number question type -->
        <div class="form-group" v-else-if="question.question_type == 2">
            <label>{{ question.question }}</label>
            <input disabled :value="question.your_response" type="number" class="form-control" />
        </div>

        <!-- this is radio question type -->
        <div class="form-group" v-else-if="question.question_type == 3">
            <label>{{ question.question }}</label>
            <div class="form-check" v-for="option in question.options" :key="option.id">
                <input disabled :value="option.id" :checked="option.is_response && option.is_response === 1" class="form-check-input" type="radio" />
                <label class="form-check-label">{{ option.courseQuestion_option }}</label>
            </div>

        </div>

        <!-- this is checkbox question type -->
        <div class="form-group" v-else-if="question.question_type == 4">
            <label>{{ question.question }}</label>
            <div class="form-check" v-for="option in question.options" :key="option.id">
                <input disabled :value="option.id" :checked="option.is_response && option.is_response === 1" class="form-check-input" type="checkbox" />
                <label class="form-check-label">{{ option.courseQuestion_option }}</label>
            </div>

        </div>

        <!-- this is select-option question type -->
        <div class="form-group" v-else-if="question.question_type == 5">
            <label>{{ question.question }}</label>
            <select disabled class="form-control">
                <option v-for="option in question.options" :key="option.id" :selected="option.is_response && option.is_response === 1" v-html="option.courseQuestion_option"></option>
            </select>
        </div>

    </div>
</template>
<script>
    export default {
        props: {
            question: {
                type: Object
            }
        }
    }
</script>
<style scoped>
    label{
        font-size: 1rem;
    }
</style>
