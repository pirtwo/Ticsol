<template>
     <page-layout 
        v-bind:header="false" 
        v-bind:footer="false">   

        <div class="md-layout md-alignment-center-center">      
          <router-view></router-view>
        </div>
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
.md-layout{
  height: 100%;
}
</style>
