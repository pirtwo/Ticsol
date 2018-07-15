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
            <select-box v-model="value" :data="options" :multi-select="true"></select-box>
        </template>
    </nav-view>
</template>
<script>
import NavView from "../../../framework/NavView.vue";
import AutoComplete from "../../../framework/BaseAutoComplete.vue";
import SelectBox from "../../../framework/BaseSelectBox.vue";

export default {
  name: "InboxList",
  components: {
    "nav-view": NavView,
    "auto-complete": AutoComplete,
    "select-box": SelectBox
  },
  data() {
    return {
      jobs: [
        "Frontend developer",
        "Backend developer",
        "Finding the Requirements",
        "Database adminstrator",
        "Tester",
        "UI Tester"
      ],
      options: [
        { key: 0, value: "frontend dev" },
        { key: 1, value: "backend dev" },
        { key: 2, value: "database management" }
      ],
      value: {}
    };
  },
  watch: {
    value: function(val) {
      console.log(val);
    }
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
