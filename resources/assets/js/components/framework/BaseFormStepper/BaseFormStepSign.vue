<template>
  <div 
    :id="'step-sign-' + stepNumber" 
    class="step-sign"
  >
    <slot>
      <button 
        class="btn" 
        type="button"
        :class="[value === stepNumber ? 'btn-success':'btn-primary']"  
        @click="onClick"
      >
        {{ stepNumber }}
      </button>
      <label>{{ label }}</label>
    </slot>
  </div>
</template>

<script>
export default {
  name: "BaseFormStepSign",

  props: {
    value: {
      type: Number
    },
    stepNumber: {
      type: Number
    },
    label: {
      type: String
    },
    icon: {
      type: String
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
.step-sign {
  min-width: 100px;
  text-align: center;
  padding: 10px;
  margin: 5px 10px;
  z-index: 1;
}

.step-sign button {
  width: 40px;
  height: 40px;
  padding: 4px;
  border-radius: 0px;
}

.step-sign label {
  text-transform: uppercase;
}

.step-sign button,
.step-sign label {
  margin: auto;
  display: block;
}
</style>
