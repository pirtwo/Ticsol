<template>
  <div 
    ref="vbSnackbar" 
    class="vb-snackbar"
    v-show="value">
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
    }
  },

  watch: {
    value: function(value) {
      if (value) this.showSnackbar()
      else this.hideSnackbar();
    }
  },

  methods: {
    showSnackbar() {
      this.$emit("show");
      //this.$refs.vbSnackbar.classList.remove("vb-snackbar--hide");
      //this.$refs.vbSnackbar.classList.add("vb-snackbar--show");
    },

    hideSnackbar() {      
      //this.$refs.vbSnackbar.classList.remove("vb-snackbar--show");
      //this.$refs.vbSnackbar.classList.add("vb-snackbar--hide");          
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
  z-index: 10;
}

.vb-snackbar__close {
  padding: 5px;
  cursor: pointer;
}

.vb-snackbar--show {
  visibility: visible;
  opacity: 1;
  animation: fadein 0.3s;
}

.vb-snackbar--hide {
  visibility: hidden;
  opacity: 0;
  animation: fadeout 0.3s;
}

@keyframes fadein {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes fadeout {
  from {
    opacity: 1;
  }
  to {
    opacity: 0;
  }
}
</style>
