<template>
  <div
    ref="stepBody"
    :class="[{ show: isVisible, hide: !isVisible }, 'step-body']"
  >
    <slot />
  </div>
</template>

<script>
export default {
  name: "StepBody",

  props: {
    value: {
      type: Number,
      default: 1
    },
    stepNumber: {
      type: Number,
      default: 1
    }
  },

  data() {
    return {};
  },

  computed: {
    isVisible: function() {
      return this.stepNumber === this.value;
    }
  },

  methods: {}
};
</script>

<style scoped>
.step-body {
  display: none;
  transition: all 0.3s cubic-bezier(0.075, 0.82, 0.165, 1);
}

.show {
  display: block;
}

.hide {
  display: none;
}
</style>
