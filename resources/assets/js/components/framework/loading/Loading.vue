<template>
  <div>
      <div  v-bind:class="[this.isLoading ? this.showClass : this.hideClass]" class="loading-box">
          <span class="mif-loop2 ani-pulse"></span> {{ this.loadingMsg }}
      </div>      
  </div>
</template>
<script>
import { mapGetters } from "vuex";
export default {
  name: "loading-box",
  data() {
    return {
      defaultClass: 'loading-box',
      showClass: "showElement",
      hideClass: "hideElement"
    };
  },
  computed: {
    ...mapGetters({ isLoading: "isLoading", loadingMsg: "loadingMessage" })
  }
};
</script>
