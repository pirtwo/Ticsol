<template>
  <div class="container-fluid">
      <router-view></router-view>
      <loading-box></loading-box>
  </div>
</template>

<script>
import Loading from "./framework/loading/Loading.vue";
export default {
  components: {
    "loading-box": Loading
  }
};
</script>

<style scoped>

</style>
