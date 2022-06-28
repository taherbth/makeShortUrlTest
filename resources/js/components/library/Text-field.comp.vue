<template>
  <div :class="['form-group', containerClass]" :style="containerStyle">
    <label for=""
      >{{ label }} <span style="color: red" v-if="isMandatory"> * </span></label
    >
    <span v-if="sublabel">
      <br />
      <label for="" class="input-sub-label">{{ sublabel }}</label>
    </span>

    <div>
      <input
        v-if="date === true"
        min="1900-01-01"
        max="2200-04-30"
        required
        type="date"
        :class="[
          'form-control ',
          { 'form-control-rounded sss': rounded },
          { 'is-invalid': validate?.$error },
          { 'is-invalid': error },
        ]"
        v-bind="$attrs"
        :value="modelValue"
        @input="emit('update:modelValue', $event.target.value)"
        :readonly="readonly"
      />
      <input
        v-else
        :class="[
          'form-control',
          { 'form-control-rounded': rounded },
          { 'is-invalid': validate?.$error },
          { 'is-invalid': error },
        ]"
        v-bind="$attrs"
        :value="modelValue"
        @input="emit('update:modelValue', $event.target.value)"
        :readonly="readonly"
      />
      <div class="invalid-feedback" style="height: 7px">{{ validate?.$errors[0]?.$message }}</div>
      <div class="invalid-feedback" v-for="msg in error" :key="msg">
        {{ msg }}
      </div>
    </div>
  </div>
</template>
<script>
export default {
  inheritAttrs: false,
  props: {
    isMandatory: {
      type: Boolean,
      default: false,
    },
    label: {
      type: String,
      required: true,
    },
    sublabel: {
      type: String,
      required: false,
    },
    error: {
      type: Array,
      default: () => {},
    },    
    rounded: {
      type: Boolean,
      default: false,
    },
    date: {
      type: Boolean,
      default: false,
    },
    readonly: {
      type: Boolean,
      default: false,
    },
    validate: {
      type: Object,
      default: () => {},
    },
    modelValue: [String, Array, Number],
    containerClass: [String, Object, Array],
    containerStyle: [String, Object, Array],
  },
  setup(props, { emit }) {
    return { emit };
  },
};
</script>
<style scoped></style>
