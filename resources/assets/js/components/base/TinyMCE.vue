<template>
  <editor />
</template>

<script>
import Editor from "@tinymce/tinymce-vue";
export default {
  name: "TinyMCE",

  components: {
    editor: Editor
  },

  props: {
    value: {
      type: String,
      default: ""
    },
    height: {
      type: [String, Number],
      default: "300"
    },
    mode: {
      type: String,
      default: ""
    }
  }
};
</script>

<style scoped>
</style>
