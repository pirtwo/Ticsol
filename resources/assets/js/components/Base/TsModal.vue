<template>
  <div 
    ref="tsModal" 
    :class="[{ 'fade': animation }, 'modal']" 
    tabindex="-1" 
    role="dialog">
    <div
      :class="[{ 'modal-sm': size === 'sm', 'modal-lg': size === 'lg', 'modal-dialog-centered': centered }, 'modal-dialog']"
      role="document"
    >
      <div class="modal-content">
        <div class="modal-header">
          <slot name="header">
            <h5 class="modal-title">{{ title }}</h5>
            <button 
              type="button" 
              class="close" 
              data-dismiss="modal" 
              aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </slot>
        </div>
        <div class="modal-body">
          <slot/>
        </div>
        <div class="modal-footer">
          <slot name="footer"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "TsModal",

  props: {
    show: {
      value: Boolean,
      default: false
    },
    title:{
      value:String,
      default:''
    },
    size: {
      value: String,
      default: ""
    },
    centered: {
      type: Boolean,
      default: true
    },
    animation: {
      type: Boolean,
      default: true
    }
  },

  watch: {
    show: function(value) {
      if (value) {
        $(this.$refs.tsModal).modal("show");
      } else {
        $(this.$refs.tsModal).modal("hide");
      }
    }
  },

  mounted() {
    $(this.$refs.tsModal).on("show.bs.modal", this.onShow);
    $(this.$refs.tsModal).on("shown.bs.modal", this.shown);
    $(this.$refs.tsModal).on("hide.bs.modal", this.onHide);
    $(this.$refs.tsModal).on("hidden.bs.modal", this.hidden);
  },

  methods: {
    onShow(e) {
      this.$emit("onShow");
    },

    shown(e) {
      this.$emit("shown");
      this.$emit("update:show", true);
    },

    onHide(e) {
      this.$emit("onHide");
    },

    hidden(e) {
      this.$emit("hidden");
      this.$emit("update:show", false);
    }
  }
};
</script>

<style scoped>
</style>
