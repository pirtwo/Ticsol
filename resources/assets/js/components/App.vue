<template>
  <router-view />
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  watch:{
    getTheme: function(val){
      let elm = document.getElementById('app');
      elm.className = val;
    }
  },

  computed: {
    ...mapGetters({
      getTheme: "core/getTheme"
    })
  },

  mounted(){
    this.$nextTick(() => {

      // set theme on document
      let elm = document.getElementById('app');
      elm.className = this.getTheme;

      // connect to pusher
      this.goRealTime();

      console.log('app is ready...');      
    });
  },

  methods: {
    ...mapActions({
      goRealTime: "core/goRealTime"
    })
  }
};
</script>

<style scoped>
</style>
