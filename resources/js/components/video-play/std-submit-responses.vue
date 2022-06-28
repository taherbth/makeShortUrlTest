<template>
    <div>
        <!-- this is text question type -->
        <div class="form-group" v-if="question.question_type == 1">
            <label>{{ question.question }}</label>
            <input type="hidden" :name="`question_type[${question.id}]`" value="1">
            <textarea :name="`answer[${question.id}]`" :maxlength="question.answer_length" :placeholder="question.answer_placeholder" class="form-control" rows="5"></textarea>
        </div>

        <!-- this is number question type -->
        <div class="form-group" v-else-if="question.question_type == 2">
            <label>{{ question.question }}</label>
            <input type="hidden" :name="`question_type[${question.id}]`" value="2">
            <input :name="`answer[${question.id}]`" :max="question.answer_max_length" :min="question.answer_min_length" :placeholder="question.answer_placeholder" type="number" class="form-control" />
        </div>

        <!-- this is radio question type -->
        <div class="form-group" v-else-if="question.question_type == 3">
            <label>{{ question.question }}</label>
            <input type="hidden" :name="`question_type[${question.id}]`" value="3">

            <div class="form-check" v-for="option in question.options" :key="option.id">
                <input :name="`answer[${question.id}]`" :value="option.id" class="form-check-input" type="radio" />
                <label class="form-check-label">{{ option.courseQuestion_option }}</label>
            </div>

        </div>

        <!-- this is checkbox question type -->
        <div class="form-group" v-else-if="question.question_type == 4">
            <label>{{ question.question }}</label>
            <input type="hidden" :name="`question_type[${question.id}]`" value="4">

            <div class="form-check" v-for="option in question.options" :key="option.id">
                <input :name="`answer[${question.id}][]`" :value="option.id" class="form-check-input" type="checkbox" />
                <label class="form-check-label">{{ option.courseQuestion_option }}</label>
            </div>

        </div>

        <!-- this is select-option question type -->
        <div class="form-group" v-else-if="question.question_type == 5">
            <label>{{ question.question }}</label>
            <input type="hidden" :name="`question_type[${question.id}]`" value="5">

            <select :name="`answer[${question.id}]`" class="form-control">
                <option v-for="option in question.options" :key="option.id" :value="option.id" v-html="option.courseQuestion_option"></option>
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
