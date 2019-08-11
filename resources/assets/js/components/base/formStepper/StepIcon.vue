<template>
  <div 
    :id="'step-icon-' + stepNumber" 
    class="step-icon"
  >
    <slot>
      <button 
        class="btn" 
        type="button"
        :class="[value === stepNumber ? 'btn-success':'btn-light']"  
        @click="onClick"
        :disabled="disabled"
      >
        {{ stepNumber }}
      </button>
      <label>{{ label }}</label>
    </slot>
  </div>
</template>

<script>
export default {
  name: "StepIcon",

  props: {
    value: {
      type: Number,
      default: 1
    },
    stepNumber: {
      type: Number,
      default: 1
    },
    label: {
      type: String,
      default: ""
    },
    icon: {
      type: String,
      default: ""
    },
    disabled:{
      type: Boolean,
      default: false
    }
  },

  methods: {
    onClick() {
      this.$emit("input", this.stepNumber);
    }
  }
};
</script>

<style scoped>
.step-icon {
  text-align: center;
  padding: 10px;
  margin: 5px 10px;
  z-index: 1;
}

.step-icon button {
  width: 40px;
  height: 40px;
  padding: 4px;
  border-radius: 0px;
}

.step-icon label {
  text-transform: uppercase;
}

.step-icon button,
.step-icon label {
  margin: auto;
  display: block;
}
</style>
