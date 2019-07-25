<template>
  <div 
    ref="stepBody" 
    class="step-body"
  >        
    <slot />
  </div>
</template>

<script>
export default {
  name: "BaseStep",

  props: {
    value: {
      type: Number
    },
    stepNumber: {
      type: Number,
      default: 1
    }    
  },

  mounted() {
    if (this.stepNumber === this.value) this.show();
    else this.hide();
  },

  watch: {
    value: function(val) {
      if (this.stepNumber === val) this.show();
      else this.hide();
    }
  },

  methods: {
    show() {
      let elm = this.$refs.stepBody;
      $(elm).addClass("show");
      $(elm).removeClass("hide");
    },

    hide() {
      let elm = this.$refs.stepBody;
      $(elm).addClass("hide");
      $(elm).removeClass("show");
    }
  }
};
</script>

<style scoped>
.step-body {  
  display: none;
  height: 100%;  
  transition: all 0.3s cubic-bezier(0.075, 0.82, 0.165, 1);
}

.show {
  display: block;
}

.hide {
  display: none;
}
</style>
