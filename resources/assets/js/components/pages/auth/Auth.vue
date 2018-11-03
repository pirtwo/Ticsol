<template>
  <page-layout 
    :header="false" 
    :footer="false">   

    <div class="wrap-content d-flex justify-content-center align-items-center">      
      <router-view/>
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
.wrap-content{
  height: 100%;
}
</style>
