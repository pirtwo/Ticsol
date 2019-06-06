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
  name: "Comments",
  components: {    
  }
};
</script>

<style scoped>
</style>
