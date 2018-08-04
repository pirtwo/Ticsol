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
            <select-box v-model="value" :data="options" :multi-select="true" name="selectJob"></select-box>

            <br>
            <br>

            <auto-complete v-model="value2" :data="options" name="jobs"></auto-complete>
        </template>
    </nav-view>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
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
      value: {}
    };
  },

  mounted() {
    this.list({ resource: "job" })
      .then(() => {
        console.log(this.getList("job"));
      })
      .catch(error => {
        console.log(error);
      });
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    options: function() {
      return this.getList("job").map(item => {
        return { key: item.id, value: item.title };
      });
    }
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
    ...mapActions({
      list: "resource/list"
    }),
    
    clickHandler() {
      console.log("click");
    }
  }
};
</script>
<style scoped>
</style>
