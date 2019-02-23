<template>
  <router-view /> 
</template>

<script>
import { mapActions } from "vuex";
export default {
  created() {       
    this.goRealTime();   
  },

  methods:{
    ...mapActions({
      goRealTime: 'core/goRealTime'
    })
  }
};
</script>

<style scoped>
</style>
