<template>
  <div class="form-stepper">        
    <div class="stepper-signs d-flex justify-content-center">
      <slot name="signs"/>
    </div>
    <div class="stepper-body">
      <slot name="body"/>
    </div>
    <div class="stepper-nav">
      <slot name="nav"/>
    </div>        
  </div>
</template>

<script>
export default {
  name: "BaseFormStepper",

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

.stepper-signs {
  height: 20%;
  position: relative;
}

.stepper-body {
  height: 70%;
  padding: 0px 5px;
  margin-bottom: 10px;
  overflow-x: hidden;
  overflow-y: auto;
}

.stepper-nav {
  height: 10%;  
}

.stepper-signs::before {
  position: absolute;
  content: " ";
  background-color: #d4d4d4;
  width: 100%;
  height: 1px;
  top: 35px;
  left: 0px;
  z-index: 0;
}

.stepper-nav .btn {
  border-radius: 0px;
}
</style>
