<template>
  <div
    ref="tsToast"
    role="alert"
    aria-live="assertive"
    aria-atomic="true"
    class="toast"
  >
    <div class="toast-header">
      <slot name="header" />
    </div>
    <div class="toast-body">
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  name: "TsToast",

  props: {
    show: {
      type: Boolean,
      default: true
    },
    animation: {
      type: Boolean,
      default: true
    },
    autohide: {
      type: Boolean,
      default: true
    },
    delay: {
      type: Number,
      default: 500
    }
  },

  data() {
    return {};
  },

  watch: {
    show: function(val) {
      if (val) $(this.$refs.tsToast).toast("show");
      else $(this.$refs.tsToast).toast("hide");
    }
  },

  mounted() {
    $(this.$refs.tsToast).toast({
      animation: this.animation,
      autohide: this.autohide,
      delay: this.delay
    });
    $(this.$refs.tsToast).on("show.bs.toast", this.onShow);
    $(this.$refs.tsToast).on("shown.bs.toast", this.onShown);
    $(this.$refs.tsToast).on("hide.bs.toast", this.onHide);
    $(this.$refs.tsToast).on("hidden.bs.toast", this.onHidden);
    $(this.$refs.tsToast).toast("show");
  },

  methods: {
    onShow(e) {
      this.$emit("onShow");
    },

    onShown(e) {
      this.$emit("shown");
      this.$emit("update:show", true);
    },

    onHide(e) {
      this.$emit("onHide");
    },

    onHidden(e) {
      this.$emit("hidden");
      this.$emit("update:show", false);
    }
  }
};
</script>

<style>
</style>
