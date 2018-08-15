<template>
     <div id="fb-editor"></div>
</template>

<script>
export default {
  name: "FormBuilder",

  props: {
    value: {
      type: Object,
      default: null
    },
    disabledAttrs: {
      type: Array,
      default: null
    },
    disableFields: {
      type: Array,
      default: null
    },
    controlOrder: {
      type: Array,
      default: null
    },
    controlPosition: {
      type: String,
      default: "right"
    },
    stickyControls: {
      type: Boolean,
      default: false
    },
    showActionButtons: {
      type: Boolean,
      default: false
    },
    disabledActionButtons: {
      type: Array,
      default: null
    }
  },

  created() {},

  mounted() {
    $("#fb-editor")
      .formBuilder({
        disabledAttrs: this.disabledAttrs,
        disableFields: this.disableFields,
        controlOrder: this.controlOrder,
        controlPosition: this.controlPosition,
        stickyControls: {
          enable: this.stickyControls,
          offset: { top: 5, bottom: "auto", right: "auto" }
        },
        showActionButtons: this.showActionButtons,
        disabledActionButtons: this.showActionButtons
      })
      .promise.then(formBuilder => {
        this.$emit("input", formBuilder);
      });
  },

  destroyed() {},

  methods: {}
};
</script>

<style scoped>
</style>
