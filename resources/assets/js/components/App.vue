<template>
  <router-view/> 
</template>

<script>
import { mapActions } from "vuex";
export default {
  created() {       
    //this.subscribeForNotifs();   
  },

  methods:{
    ...mapActions({
      subscribeForNotifs: 'user/subscribeForNotifications'
    })
  }
};
</script>

<style scoped>
</style>
