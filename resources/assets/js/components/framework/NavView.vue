<template>  
    <md-app md-mode="fixed">

        <!-- toolbar -->
        <md-app-toolbar class="md-primary">
            <md-button class="md-icon-button" @click="toggleMenu">
                <md-icon>menu</md-icon>
            </md-button>
            <span class="md-title">{{ menuTitle }}</span>
            <slot name="menu"></slot>
        </md-app-toolbar>

        <!-- drawer -->
        <md-app-drawer v-bind:class="[menuVisible? 'open' : 'close']" md-permanent="full">
            <md-toolbar class="md-transparent" md-elevation="0">
                <span>{{ drawerTitle }}</span>                
            </md-toolbar>
            <slot name="drawer"></slot>
        </md-app-drawer>

        <!-- content -->
        <md-app-content>
            <div class="wrap-loading" v-show="loading">
              <div class="loading-box">
                <md-progress-spinner class="md-accent"
                  :md-stroke="7" 
                  :md-diameter="30"                
                  md-mode="indeterminate">
                </md-progress-spinner>
                <span class="caption">Loading, Please wait...</span>
              </div>
            </div>
            <div class="wrap-content" v-show="!loading">
              <slot name="content"></slot>
            </div>            
        </md-app-content>

    </md-app>
</template>

<script>
export default {
  name: "NavView",
  props: {
    scrollbar: {
      type: Boolean
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
  data: () => ({
    menuVisible: true
  }),
  methods: {
    toggleMenu() {
      this.menuVisible = !this.menuVisible;
    }
  }
};
</script>

<style lang="scss" scoped>
.md-app {
  height: 100%;
  border: none;
  background-color: rgba(255, 255, 255, 0) !important;
}

.md-app-content {
  padding: 1px 0px 0px 0px;
  height: auto !important;
  min-height: 100% !important;
  background-color: rgba(255, 255, 255, 0.8) !important;
}

.md-drawer {
  width: 230px;
  margin-right: 8px;
  max-width: calc(100vw - 125px);

  transition: all 0.3s !important;
  color: black !important;
  background-color: rgba(255, 255, 255, 0.8) !important;
}

.md-drawer.open{
  width: 230px !important;
}

.md-drawer.close{
  width: 0px !important;
  margin-right: 0px !important;
  overflow: hidden;
}

.md-app-toolbar {
  background-color: rgb(148, 0, 195) !important;
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