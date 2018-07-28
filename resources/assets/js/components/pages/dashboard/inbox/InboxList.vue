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
            <select-box v-model="value" :data="options" :multi-select="false" name="selectJob"></select-box>

            <br>
            <br>

            <auto-complete v-model="value2" :data="options" name="jobs"></auto-complete>
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
        { key: 2, value: "database management" },
        { key: 3, value: "debuger" },
        { key: 4, value: "tester" },
        { key: 5, value: "manager" },
        { key: 6, value: "RE Analist" },
      ],
      value: {},
      value2: {},
    };
  },
  watch: {
    value: function(val) {
      console.log(val);
    },
    value2: function(val) {
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
