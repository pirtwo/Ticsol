<template>
  <app-layout 
    :header="false" 
    :footer="false"
  >
    <div class="wrap-content d-flex justify-content-center align-items-center">      
      <router-view />
    </div>
  </app-layout>
</template>
<script>

export default {  
};
</script>

<style scoped>
.wrap-content{
  height: 100%;
}
</style>
