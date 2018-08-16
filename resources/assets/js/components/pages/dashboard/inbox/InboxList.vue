<template>
    <nav-view 
    :scrollbar="true" 
    :loading="false"
    menu-title="Inbox" 
    drawer-title="Actions"
    padding="p-5">        
        <template slot="menu">

        </template>
        <template slot="drawer">

        </template>
        <template slot="content">           
            
        </template>
    </nav-view>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import NavView from "../../../framework/NavView.vue";

export default {
  name: "InboxList",

  components: {
    "nav-view": NavView
  },

  data() {
    return {};
  },

  mounted() {},

  computed: {},

  watch: {},

  methods: {}
};
</script>

<style scoped>
</style>
