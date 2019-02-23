<template>
  <div>
    <div 
      :class="[this.isLoading ? this.showClass : this.hideClass]" 
      class="loading-box"
    >
      <span class="mif-loop2 ani-pulse" /> {{ this.loadingMsg }}
    </div>      
  </div>
</template>
<script>
import { mapGetters } from "vuex";
export default {
  name: "LoadingBox",
  data() {
    return {
      defaultClass: 'loading-box',
      showClass: "showElement",
      hideClass: "hideElement"
    };
  },
  computed: {
    ...mapGetters({ isLoading: "loading/isLoading", loadingMsg: "loading/getMessage" })
  }
};
</script>
