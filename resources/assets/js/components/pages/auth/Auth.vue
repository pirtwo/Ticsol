<template>
     <page-layout 
        v-bind:header="false" 
        v-bind:footer="false">         
         <router-view></router-view>
     </page-layout>
</template>
<script>
import PageLayout from "../../framework/PageLayout.vue";
export default {
  components: {
    "page-layout": PageLayout
  }
};
</script>
<style scoped>
</style>
