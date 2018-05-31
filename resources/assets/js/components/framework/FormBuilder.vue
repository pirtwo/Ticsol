<template>
     <div id="fb-editor"></div>
</template>

<script>
//import fb from "formBuilder";
export default {
  name: "FormBuilder",
  mounted() {
    let options = {
      stickyControls: { enable: false }
    };
    $(document.getElementById("fb-editor")).formBuilder(options);
  }
};
</script>

<style>
</style>
