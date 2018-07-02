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
            <auto-complete v-model="value" v-bind:data="jobs" name="user"></auto-complete>
        </template>
    </nav-view>
</template>
<script>
import NavView from "../../../framework/NavView.vue";
import AutoComplete from "../../../framework/BaseAutoComplete.vue";

export default {
  name: "InboxList",
  components: {
    "nav-view": NavView,
    "auto-complete": AutoComplete
  },
  data(){
    return{
      jobs: [
            'Frontend developer',
            'Backend developer',
            'Finding the Requirements',
            'Database adminstrator',
            'Tester',
            'UI Tester',
        ],
      value: ''
    }
  },
  watch:{
    value:function(val){
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
