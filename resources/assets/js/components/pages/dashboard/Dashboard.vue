<template>
    <div class="row">
        <header class="cell-sm-12">            
            <iconbar-top></iconbar-top>            
        </header>
        <main class="cell-sm-12 dashboard">
            <div id="dash-nav" data-role="navview">
                <div class="navview-pane">
                    <sidebar></sidebar>
                </div>

                <div class="navview-content">
                    <div class="content">
                        <router-view></router-view>
                    </div>
                </div>
            </div>
        </main>
        <footer class="cell-sm-12">           
            <iconbar-bot></iconbar-bot>            
        </footer>
    </div>
</template>

<script>
import Sidebar from "../../framework/menu/NavView.vue";
import IconbarTop from "../../framework/iconbar/IconbarTop.vue";
import IconbarBot from "../../framework/iconbar/IconbarBot.vue";

export default {
  components: {
    "sidebar": Sidebar,
    "iconbar-top": IconbarTop,
    "iconbar-bot": IconbarBot
  },
  methods:{
      updateSidebar: function(){
          
      }
  }
};
</script>

<style scoped>

</style>