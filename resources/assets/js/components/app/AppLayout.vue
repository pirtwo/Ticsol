<template>
  <div class="container-fluid">
    <header 
      v-if="this.header" 
      class="header"
    >
      <slot name="header" />
    </header>    
    <main class="main">
      <slot />
    </main>   
    <footer 
      v-if="this.footer" 
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
      let head = $(".header").outerHeight(true) || 0;
      let foot = $(".footer").outerHeight(true) || 0;
      let contentHeight = $(window).height() - (head + foot);
      let toolbarHeight = $(".navbar").outerHeight(true);

      this.setContentDim({
        width: $(".main").outerWidth(true),
        height: contentHeight
      });
      this.setDocumentDim({
        width: $("body").outerWidth(true),
        height: $("body").outerHeight(true)
      });
      this.toolbar({ height: toolbarHeight, show: undefined });

      $(".main").css("height", contentHeight);
    }
  }
};
</script>

<style scoped>
</style>

