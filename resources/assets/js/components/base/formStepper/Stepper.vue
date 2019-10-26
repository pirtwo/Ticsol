<template>
  <div class="form-stepper d-flex flex-column">
    <!-- stepper nav -->        
    <div class="stepper-nav d-flex justify-content-center">
      <slot name="nav" />
    </div>

    <!-- stepper body -->
    <div class="stepper-body h-100">
      <slot name="body" />
    </div>

    <!-- stepper footer -->
    <div class="stepper-footer align-self-end">
      <slot name="footer" />
    </div>        
  </div>
</template>

<script>
export default {
  name: "Stepper",

  props: {
    value: {
      type: Number,
      default: 1
    }
  },

  data() {
    return {
      currentStep: 1
    };
  }
};
</script>

<style scoped>
.form-stepper {
  height: 100%;
  position: relative;
}

.stepper-nav {
  height: auto;
  position: relative;
}

.stepper-body {
  padding: 0px 5px;
  margin-bottom: 10px;
  overflow-x: hidden;
  overflow-y: auto;
}

.stepper-footer {
  width: 100%; 
}

/* .stepper-nav::before {
  position: absolute;
  content: " ";
  background-color: #d4d4d4;
  width: 100%;
  height: 1px;
  top: 35px;
  left: 0px;
  z-index: 0;
} */
</style>
