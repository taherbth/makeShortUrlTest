<template>
    <div>
        <input type="hidden" name="created_at" :value="question.created_at" />
        <!-- this is text question type -->
        <div class="form-group" v-if="question.question_type == 1">
            <label>{{ question.question }}</label>
            <input type="hidden" :name="`response_type[${question.your_response_id}]`" value="1">
            <textarea :name="`answer[${question.your_response_id}]`" :maxlength="question.answer_length" :placeholder="question.answer_placeholder" v-html="question.your_response" class="form-control" rows="5"></textarea>
        </div>

        <!-- this is number question type -->
        <div class="form-group" v-else-if="question.question_type == 2">
            <label>{{ question.question }}</label>
            <input type="hidden" :name="`response_type[${question.your_response_id}]`" value="2">
            <input :name="`answer[${question.your_response_id}]`" :max="question.answer_max_length" :min="question.answer_min_length" :placeholder="question.answer_placeholder" :value="question.your_response" type="number" class="form-control" />
        </div>

        <!-- this is radio question type -->
        <div class="form-group" v-else-if="question.question_type == 3">
            <label>{{ question.question }}</label>
            <input type="hidden" :name="`response_type[${question.your_response_id}]`" value="3">

            <div class="form-check" v-for="option in question.options" :key="option.id">
                <input :name="`answer[${question.your_response_id}]`" :value="option.id" :checked="option.is_response && option.is_response === 1" class="form-check-input" type="radio" />
                <label class="form-check-label">{{ option.courseQuestion_option }}</label>
            </div>

        </div>

        <!-- this is checkbox question type -->
        <div class="form-group" v-else-if="question.question_type == 4">
            <label>{{ question.question }}</label>
            <input type="hidden" :name="`question_type[${question.id}]`" value="4">

            <div class="form-check" v-for="option in question.options" :key="option.id">
                <input v-if="option.your_response_id" :name="`delete_response_options[${option.your_response_id}]`" :value="question.id" type="hidden" />
                <input :name="`answer[${question.id}][]`" :value="option.id" :checked="option.is_response && option.is_response === 1" class="form-check-input" type="checkbox" />
                <label class="form-check-label">{{ option.courseQuestion_option }}</label>
            </div>

        </div>

        <!-- this is select-option question type -->
        <div class="form-group" v-else-if="question.question_type == 5">
            <label>{{ question.question }}</label>
            <input type="hidden" :name="`response_type[${question.your_response_id}]`" value="5">

            <select :name="`answer[${question.your_response_id}]`" class="form-control">
                <option v-for="option in question.options" :key="option.id" :value="option.id" :selected="option.is_response && option.is_response === 1" v-html="option.courseQuestion_option"></option>
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
