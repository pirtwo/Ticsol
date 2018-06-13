<template>
    <nav-view 
        v-bind:scrollbar="true" 
        menu-title="Inbox" 
        drawer-title="Actions">        
        <template slot="menu">

        </template>
        <template slot="drawer">

        </template>
        <template slot="content">

        </template>
    </nav-view>
</template>
<script>
import NavView from "../../../framework/NavView.vue";
export default {
  name: "InboxList",
  components: {
    "nav-view": NavView
  },
  methods: {
    clickHandler() {
      console.log("click");
    }
  }
};
</script>
<style scoped>
</style>
