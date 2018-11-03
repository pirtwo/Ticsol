<template>
  <page-layout 
    :header="true" 
    :footer="true">
    <template slot="header">
      <iconbar-top/>
    </template>
    <router-view/>
    <template slot="footer">
      <iconbar-bot/>
    </template>
  </page-layout>
</template>

<script>
import PageLayout from "../../../framework/PageLayout.vue";
import IconbarTop from "../../../framework/iconbar/IconbarTop.vue";
import IconbarBot from "../../../framework/iconbar/IconbarBot.vue";
export default {
  name: "Profiles",
  components: {
    "page-layout": PageLayout,
    "iconbar-top": IconbarTop,
    "iconbar-bot": IconbarBot
  }
};
</script>

<style scoped>
</style>
