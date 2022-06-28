<template>
    <div>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="is_question_errors">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>All questions information fields are required</strong>
        </div>

        <div id="questions">
            <div class="header mt-1">
                <p>Question 1</p>
                <a href="#"><i class="fa fa-trash"></i></a>
            </div>
            <div class="form-group">
                <label>Question title</label>
                <input type="text" class="form-control form-control-lg" name="questions[1]"/>
                <input type="hidden" name="question_type[1]" />
            </div>
            <label>Answer type</label>
            <div class="card">
                <div class="card-body ">
                    <nav class="nav nav-pills nav-fill">
                        <a class="nav-item nav-link text-default" data-target="question-box-1" data-id="1" data-type="Text" href="#" ><i class="fa fa-circle"></i> Text</a>
                        <a class="nav-item nav-link text-default" data-target="question-box-1" data-id="1" data-type="Number" href="#" ><i class="fa fa-circle"></i> Number</a>
                        <a class="nav-item nav-link text-default" data-target="question-box-1" data-id="1" data-type="Radio" href="#" ><i class="fa fa-circle"></i> Radio</a>
                        <a class="nav-item nav-link text-default" data-target="question-box-1" data-id="1" data-type="Check" href="#" ><i class="fa fa-circle"></i> Check</a>
                        <a class="nav-item nav-link text-default" data-target="question-box-1" data-id="1" data-type="Select" href="#" ><i class="fa fa-circle"></i> Select</a>
                    </nav>
                </div>
            </div>
            <div class="card" id="question-box-1"></div>
        </div>
        <a class="btn btn-light col-12 mt-3 add_form_field"><span style="font-size:16px; font-weight:bold;">+ </span>Add Another Question</a>
    </div>
</template>
<script>
    export default {
        props: ['errors'],
        computed: {
            is_question_errors: function (){
                return (this.errors.questions || this.errors.question_type || this.errors.answer_placeholder || this.errors.answer_length || this.errors.answer_min_length || this.errors.answer_max_length || this.errors.total_option || this.errors.answer_option);
            }
        },
        methods: {
            removeQuestion: function (el){
                el.target.parentElement.parentElement.parentElement.parentElement.remove()
            },
            cleanRenderBox: function (div){
                div.innerHTML = ''
            },
            setElementActive(el){
                let allElements = el.parentElement.children
                for (let index = 0; index < allElements.length; index++) {
                    allElements[index].classList.remove('active')
                }
                el.classList.add('active')
            }
        },
        mounted() {
            var self = this
            $(document).ready(function() {
                var max_fields = 5;
                var wrapper = $("#questions");
                var add_button = $(".add_form_field");
                var x = 1;

                // it contain hole form fields
                $(add_button).click(function(e) {
                    e.preventDefault();
                    if (x < max_fields) {
                        x++;
                        $(wrapper).append(`
                            <div id="question-form-${x}">
                                <div class="header mt-3">
                                    <p>Question ${x}</p>
                                    <a href="#" class="delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                                <div class="form-group">
                                    <label>Question title</label>
                                    <input type="text" class="form-control form-control-lg" name="questions[${x}]"/>
                                    <input type="hidden" name="question_type[${x}]" />
                                </div>
                                <label>Answer type</label>
                                <div class="card">
                                    <div class="card-body ">
                                        <nav class="nav nav-pills nav-fill">
                                            <a class="nav-item nav-link text-default" data-target="question-box-${x}" data-id="${x}" data-type="Text" href="#" ><i class="fa fa-circle"></i> Text</a>
                                            <a class="nav-item nav-link text-default" data-target="question-box-${x}" data-id="${x}" data-type="Number" href="#" ><i class="fa fa-circle"></i> Number</a>
                                            <a class="nav-item nav-link text-default" data-target="question-box-${x}" data-id="${x}" data-type="Radio" href="#" ><i class="fa fa-circle"></i> Radio</a>
                                            <a class="nav-item nav-link text-default" data-target="question-box-${x}" data-id="${x}" data-type="Check" href="#" ><i class="fa fa-circle"></i> Check</a>
                                            <a class="nav-item nav-link text-default" data-target="question-box-${x}" data-id="${x}" data-type="Select" href="#" ><i class="fa fa-circle"></i> Select</a>
                                        </nav>
                                    </div>
                                </div>
                                <div class="card" id="question-box-${x}"></div>
                            </div>
                        `); //add input box
                    } else {
                        alert('You Reached the limits')
                    }
                });

                // this event will delete question box
                $(wrapper).on("click", ".delete", function(e) {
                    e.preventDefault();
                    $(this).parent('div').parent('div').remove();
                    x--;
                })

                // this event will render radio input options
                $(wrapper).on("change", ".renderFieldsForRadio", function(el) {
                    el.preventDefault();
                    let n = parseInt(el.target.value, 10)
                    let renderBox = el.target.parentElement.nextElementSibling
                    let questionId = el.target.getAttribute('data-id')

                    if(isNaN(n)){ return false }
                    self.cleanRenderBox(renderBox);
                    let response = ''
                    for(let i=1; i<=n; i++){
                        response += `
                            <div class="radio-box mb-2">
                                <label style="margin-left:37px">Option ${i} Label</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" style="background: transparent;border: none">
                                            <i class="far fa-dot-circle"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" style="border-radius: 0.25rem" name="answer_option[${questionId}][${i}]" />
                                </div>
                            </div>
                        `;
                    }
                    renderBox.innerHTML = response
                })

                // this event will render checkbox input options
                $(wrapper).on("change", ".renderFieldsForCheck", function(el) {
                    el.preventDefault();
                    let n = parseInt(el.target.value, 10)
                    let renderBox = el.target.parentElement.nextElementSibling
                    let questionId = el.target.getAttribute('data-id')

                    if(isNaN(n)){ return false }
                    self.cleanRenderBox(renderBox);
                    let response = ''
                    for(let i=1; i<=n; i++){
                        response += `
                            <div class="checkbox-box mb-2">
                                <label style="margin-left:37px">Option ${i} Label</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" style="background: transparent;border: none">
                                            <i class="fa fa-check-square"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" style="border-radius: 0.25rem" name="answer_option[${questionId}][${i}]" />
                                </div>
                            </div>
                        `;
                    }
                    renderBox.innerHTML = response
                })

                // this event will render select input options
                $(wrapper).on("change", ".renderFieldsForSelect", function(el) {
                    el.preventDefault();
                    let n = parseInt(el.target.value, 10)
                    let renderBox = el.target.parentElement.nextElementSibling
                    let questionId = el.target.getAttribute('data-id')

                    if(isNaN(n)){ return false }
                    self.cleanRenderBox(renderBox);
                    let response = ''
                    for(let i=1; i<=n; i++){
                        response += `
                            <div class="select-box mb-2">
                                <label style="margin-left:37px">Option ${i} Label</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" style="background: transparent;border: none">
                                            <i class="fa fa-align-left"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" style="border-radius: 0.25rem" name="answer_option[${questionId}][${i}]" />
                                </div>
                            </div>
                        `;
                    }
                    renderBox.innerHTML = response
                })

                // this event will render text input
                $(wrapper).on("click", ".nav-link", function(el) {
                    el.preventDefault();
                    let element = el.target.localName === "a"? el.target : el.target.parentElement

                    let questionType = element.getAttribute('data-type')
                    let questionId = element.getAttribute('data-id')
                    let renderBox = document.getElementById(element.getAttribute('data-target'))
                    let response = ""
                    self.setElementActive(element)
                    renderBox.innerHTML = ""
                    if (questionType === "Text") {
                        response = `
                            <div class="card-body">
                                <input type="hidden" name="question_type[${questionId}]" value="1" />
                                <div class="form-group">
                                    <label>Placeholder text <span class="text-secondary">(This placeholder text will be shown in the text area)</span></label>
                                    <input type="text" class="form-control form-control-lg" name="answer_placeholder[${questionId}]" />
                                </div>
                                <div class="form-group">
                                    <label>Character limit</label>
                                    <input type="number" min="1" class="form-control form-control-lg" name="answer_length[${questionId}]" />
                                </div>
                            </div>
                        `
                    } else if (questionType === "Number"){
                        response = `
                            <div class="card-body">
                                <input type="hidden" name="question_type[${questionId}]" value="2" />
                                <div class="form-group">
                                    <label>Placeholder text <span class="text-secondary">(This placeholder text will be shown in the text area)</span></label>
                                    <input type="text" class="form-control form-control-lg" name="answer_placeholder[${questionId}]" />
                                </div>
                                <div class="form-group row">
                                    <label class="col-12">Enter a number range for the students to pick from</label>
                                    <div class="col-md-6 mb-1">
                                        <input type="number" min="1" class="form-control form-control-lg" placeholder="From e.g. 0" name="answer_min_length[${questionId}]" />
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <input type="number" min="1" class="form-control form-control-lg" placeholder="From e.g. 10" name="answer_max_length[${questionId}]" />
                                    </div>
                                </div>
                            </div>
                        `
                    } else if (questionType === "Radio"){
                        response = `
                            <div class="card-body">
                                <input type="hidden" name="question_type[${questionId}]" value="3" />
                                <div class="form-group">
                                    <label>Number of options</label>
                                    <input type="number" min="1" class="form-control form-control-lg renderFieldsForRadio" name="total_option[${questionId}]" data-id="${questionId}" />
                                </div>
                                <div class="answer-box"></div>
                            </div>
                        `
                    } else if (questionType === "Check"){
                        response = `
                            <div class="card-body">
                                <input type="hidden" name="question_type[${questionId}]" value="4" />
                                <div class="form-group">
                                    <label>Number of options</label>
                                    <input type="number" min="1" class="form-control form-control-lg renderFieldsForCheck" name="total_option[${questionId}]" data-id="${questionId}" />
                                </div>
                                <div class="answer-box"></div>
                            </div>
                        `
                    } else if (questionType === "Select"){
                        response = `
                            <div class="card-body">
                                <input type="hidden" name="question_type[${questionId}]" value="5" />
                                <div class="form-group">
                                    <label>Number of options</label>
                                    <input type="number" min="1" class="form-control form-control-lg renderFieldsForSelect" name="total_option[${questionId}]" data-id="${questionId}" />
                                </div>
                                <div class="answer-box"></div>
                            </div>
                        `
                    }

                    renderBox.innerHTML = response
                })
            });
        },
    }
</script>
<style>
    .form-group label {
        font-weight: normal;
        text-transform: none;
    }
    .card-body {
        padding: 0.4rem .85rem;
    }
    .header{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5em;
    }
    .header p{
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }
    .nav-link.active{
        background: #E1F4FF !important;
        color: var(--primary-text) !important;
    }
    .nav-link.active i{
        color: var(--blue) !important;
    }
    .nav-pills .nav-item.nav-link:hover i{
        color: var(--blue);
    }
</style>
