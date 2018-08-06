<template>
<div class="container-fluid">
    <header v-if="this.header" class="header">
        <slot name="header"></slot>
    </header>    
    <main class="main">
        <slot></slot>
    </main>   
    <footer v-if="this.footer" class="footer">
        <slot name="footer"></slot>
    </footer>    
</div>  
</template>

<script>
import _ from "lodash";
import { mapActions } from "vuex";

export default {
  name: "PageLayout",
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
      let head = $(".header").outerHeight() | 0;
      let foot = $(".footer").outerHeight() | 0;
      let contentHeight = $(window).height() - (head + foot);
      let toolbarHeight = $(".navbar").outerHeight();

      this.setContentDim({
        width: $(".main").outerWidth(),
        height: contentHeight
      });
      this.setDocumentDim({
        width: $("body").outerWidth(),
        height: $("body").outerHeight()
      });
      this.toolbar({ height: toolbarHeight, show: undefined });

      $(".main").css("height", contentHeight);
    }
  }
};
</script>

<style scoped>
</style>

