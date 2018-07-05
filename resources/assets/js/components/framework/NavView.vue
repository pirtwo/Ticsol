<template>  
    <div class="wrap-drawer">      

        <!-- drawer -->
        <div class="drawer" v-bind:class="[menuVisible? 'open' : 'close']">
            <span>{{ drawerTitle }}</span>  
            <slot name="drawer"></slot>
        </div>

        <!-- content -->
        <div class="wrap-content">

            <!-- loadingbox -->
            <div class="wrap-loading" v-show="loading">
              <div class="loading-box">                
                <span class="caption">Loading, Please wait...</span>
              </div>
            </div>

            <!-- toolbar -->
            <nav class="navbar navbar-light bg-light">                
              <button class="navbar-toggler ml-auto" type="button" v-on:click="toggleMenu">
                <span class="navbar-toggler-icon"></span>
              </button>
              <span class="navbar-brand">{{ menuTitle }}</span>
              <slot name="toolbar"></slot>
            </nav>

            <div class="content" v-bind:class="[scrollbar? 'scrollbar-show' : 'scrollbar-hide']" v-show="!loading">
              <slot name="content"></slot>
            </div>            
        </div>

    </div>
</template>

<script>
import _ from "lodash";
import { mapActions, mapGetters } from "vuex";
export default {
  name: "NavView",

  props: {
    scrollbar: {
      type: Boolean,
      default: true
    },
    menuTitle: {
      type: String,
      default: ""
    },
    drawerTitle: {
      type: String,
      default: ""
    },
    loading: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      menuVisible: true
    };
  },

  computed: {
    ...mapGetters({
      contentHeight: "appUI/getContentHeight"
    })
  },

  watch: {
    contentHeight: function(value) {
      $(".content").height(value);
    }
  },

  methods: {
    toggleMenu(e) {
      this.menuVisible = !this.menuVisible;
      e.preventDefault();
    }
  }
};
</script>

<style scoped>
.wrap-drawer {
  height: 100%;
  display: flex;
  position: relative;
}

.wrap-content {
  width: 100%;
  max-height: 100%;
  position: relative;
  background-color: rgba(255, 255, 255, 0.8);
}

.content {  
  position: relative;
}

.scrollbar-show{
  overflow-y: auto;
}

.scrollbar-hide{
  overflow-y: hidden;
}

.drawer {
  width: 230px;
  height: 100%;
  overflow-y: auto;
  margin-right: 8px;
  transition: all 0.3s;
  background-color: rgba(255, 255, 255, 0.8);
}

.navbar {
  height: 70px;
  display: -webkit-box !important;
}

.drawer.open {
  width: 270px;
}

.drawer.close {
  width: 0px;
  overflow: hidden;
  margin-right: 0px;
}

.wrap-loading {
  top: 50%;
  left: 50%;
  position: absolute;
}

.loading-box {
  left: -50%;
  top: -50%;
  padding: 10px;
  position: relative;
  background-color: rgba(255, 255, 255, 0.8);
}

.wrap-loading .md-progress-spinner {
  margin-left: 40%;
  margin-right: auto;
}

.wrap-loading .caption {
  display: flex;
}
</style>