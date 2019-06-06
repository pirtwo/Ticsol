<template>
  <app-layout 
    :header="true" 
    :footer="true"
  >
    <template slot="header">
      <iconbar-top />
    </template>
    <router-view />
    <template slot="footer">
      <iconbar-bot />
    </template>
  </app-layout>
</template>

<script>
export default {
  name: "Users",
  components: {
   
  }
};
</script>

<style scoped>
</style>
