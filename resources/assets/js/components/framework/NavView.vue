<template>  
    <md-app>

        <!-- toolbar -->
        <md-app-toolbar class="md-primary">
            <md-button class="md-icon-button" @click="toggleMenu" v-if="!menuVisible">
                <md-icon>menu</md-icon>
            </md-button>
            <span class="md-title">{{ menuTitle }}</span>
            <slot name="menu"></slot>
        </md-app-toolbar>

        <!-- drawer -->
        <md-app-drawer :md-active.sync="menuVisible" md-persistent="full">
            <md-toolbar class="md-transparent" md-elevation="0">
                <span>{{ drawerTitle }}</span>

                <div class="md-toolbar-section-end">
                <md-button class="md-icon-button md-dense" @click="toggleMenu">
                    <md-icon>keyboard_arrow_left</md-icon>
                </md-button>
                </div>
            </md-toolbar>
            <slot name="drawer"></slot>
        </md-app-drawer>

        <!-- content -->
        <md-app-content>
            <slot name="content"></slot>
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
    }
  },
  data: () => ({
    menuVisible: false
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
  background-color: rgba(255, 255, 255, 0.8) !important;
}

// Demo purposes only
.md-drawer {
  width: 230px;  
  max-width: calc(100vw - 125px);
  color: black !important;
  background-color: rgba(255, 255, 255, 0.0) !important;
}

.md-app-toolbar {  
  background-color:rgb(148, 0, 195) !important;
}

.md-content {
  height: 100%;
  background-color: rgba(255, 255, 255, 0) !important;
}
</style>