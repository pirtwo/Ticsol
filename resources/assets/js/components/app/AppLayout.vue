<template>
  <div class="container-fluid">
    <header
      id="header"
      class="header"
    >
      <slot name="header" />
    </header>
    <main
      id="main"
      class="main"
    >
      <slot />
    </main>
    <footer
      id="footer"
      class="footer"
    >
      <slot name="footer" />
    </footer>
  </div>
</template>

<script>
import _ from "lodash";
import { mapActions } from "vuex";

export default {
  name: "AppLayout",

  props: {
    header: {
      type: Boolean,
      default: true
    },
    footer: {
      type: Boolean,
      default: true
    }
  },

  data() {
    return {};
  },

  mounted() {
    this.$nextTick(this.resizeHandler);
    this.debounceResize = _.debounce(this.resizeHandler, 500);
    $(window).resize(this.debounceResize);
  },

  methods: {
    ...mapActions({
      setDocumentDim: "core/documentDimension",
      setContentDim: "core/contentDimension",
      toolbar: "core/toolbar"
    }),

    resizeHandler() {
      let drawerBodyHeight = $("#drawer__body").outerHeight(true);
      let drawerToolbarHeight = $("#drawer__toolbar").outerHeight(true);
      
      this.setDocumentDim({
        width: $("body").outerWidth(true),
        height:  $("body").outerHeight(true),
      });

      this.setContentDim({
        width: $("#drawer__content").outerWidth(true),
        height: drawerBodyHeight - drawerToolbarHeight
      });

      this.toolbar({
        height: drawerToolbarHeight,
        show: undefined
      });
    }
  }
};
</script>

<style scoped>
</style>

