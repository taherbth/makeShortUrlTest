<template>
  <!-- Link Button -->
  <template v-if="to.length > 0">
    <router-link
      ref="ladda"
      :class="[
        'btn',
        `btn-${color}`,
        `btn-${size}`,
        `btn-${colorType}-${color}`,
        { 'btn-rounded': rounded },
        'btn-icon-text',
        'ladda-button',
      ]"
      :to="to"
      v-bind="$attrs"
    >
      <span class="ladda-label">
        <i v-if="icon.length > 0" :class="['mr-2', icon]"></i>
        <slot></slot>
      </span>
    </router-link>
  </template>

  <!-- Regular Button -->
  <template v-else>
    <button
      ref="ladda"
      :class="[
        'btn',
        `btn-${color}`,
        `btn-${size}`,
        `btn-${colorType}-${color}`,
        { 'btn-rounded': rounded },
        'btn-icon-text',
        'ladda-button',
      ]"
      v-bind="$attrs"
    >
      <span class="ladda-label">
        <i v-if="icon.length > 0" :class="['mr-2', icon]"></i>
        <slot></slot>
      </span>
    </button>
  </template>
</template>
<script>
import * as Ladda from "ladda";
import "ladda/dist/ladda.min.css";

export default {
  inheritAttrs: false,
  props: {
    // loading prop to change the status of this component.
    dataStyle: {
      type: String,
      default: "expand-left",
    },
    loading: {
      type: Boolean,
      required: true,
    },
    progress: {
      validator: function (progress) {
        return progress >= 0 && progress <= 1;
      },
      default: 0,
    },

    color: {
      type: String,
      default: "primary",
    },
    size: {
      type: String,
      default: "m",
    },

    colorType: {
      type: String,
      default: "",
    },
    rounded: {
      type: Boolean,
      default: false,
    },
    icon: {
      type: String,
      default: "",
    },
    to: {
      type: String,
      default: "",
    },
  },
  watch: {
    loading: function (loading) {
      loading ? this.ladda.start() : this.ladda.stop();
    },

    progress: function (progress) {
      this.ladda.setProgress(progress);
    },
  },

  methods: {
    handleClick: function (e) {
      this.$emit("click", e);
    },
  },

  mounted: function () {
    this.ladda = Ladda.create(this.$refs.ladda);
    this.loading ? this.ladda.start() : this.ladda.stop();
  },

  onUnmounted: function () {
    this.ladda.remove();
    delete this.ladda;
  },
};
</script>
