<template>
  <div 
    ref="vbSnackbar" 
    class="vb-snackbar">
    <div class="d-flex align-items-center">
      <slot/>
      <button         
        type="button" 
        class="close ml-auto" 
        aria-label="Close"
        @click="hideSnackbar">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: "VbSnackbar",

  props: {
    value: {
      type: Boolean,
      default: false
    },
    fixed: {
      type: Boolean,
      default: false
    },
    timeout: {
      type: Number,
      default: 3000
    }
  },

  watch: {
    value: function(value) {      
      if (value) this.showSnackbar();
    }
  },

  methods: {
    showSnackbar() {      
      this.$emit("show");
      this.$refs.vbSnackbar.classList.remove("vb-snackbar--hide");
      this.$refs.vbSnackbar.classList.add("vb-snackbar--show");
      if (!this.fixed) {
        setTimeout(this.hideSnackbar, this.timeout);
      }
    },

    hideSnackbar() {            
      this.$refs.vbSnackbar.classList.remove("vb-snackbar--show");
      this.$refs.vbSnackbar.classList.add("vb-snackbar--hide");
      this.$emit("input", false);
      this.$emit("hide");
    }
  }
};
</script>

<style scoped>
.vb-snackbar {
  visibility: visible;
  border-radius: 0px;
  opacity: 0;
  z-index: 10;
}

.vb-snackbar__close{
    padding: 5px;
    cursor: pointer;
}

.vb-snackbar--show { 
  opacity: 1;
  animation: fadein 0.3s;
}

.vb-snackbar--hide { 
  opacity: 0; 
  animation: fadeout 0.3s;
}

@keyframes fadein {
  from {
    top: -50px;
    opacity: 0;
  }
  to {
    top: 0px;
    opacity: 1;
  }
}

@keyframes fadeout {
  from {
    top: 0px;
    opacity: 1;
  }
  to {
    top: -50px;
    opacity: 0;
  }
}
</style>
