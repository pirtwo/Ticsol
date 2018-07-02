<template>
<div class="container page-container">
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
    return {
      resizeTimer: {},
      bounceTime: 250
    };
  },
  mounted() {
    this.$nextTick(this.resizeHandler);
    this.debounceResize = _.debounce(this.resizeHandler, 500);
    $(window).resize(this.debounceResize);
  },
  methods: {
    resizeHandler() {
      let head = $(".header").outerHeight() | 0;
      let foot = $(".footer").outerHeight() | 0;
      let main = $(window).height() - (head + foot);
      $(".main").css("height", main);
    }
  }
};
</script>

<style>
</style>
