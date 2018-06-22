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
    $(window).resize(() => {
      clearTimeout(this.resizeTimer);
      this.resizeTimer = setTimeout(this.resizeHandler, this.bounceTime);
    });
  },
  methods: {
    resizeHandler() {
      let head = $(".header").height() | 0;
      let foot = $(".footer").height() | 0;
      let main = $(window).height() - (head + foot + 7);
      $(".main").css("height", main);
    }
  }
};
</script>

<style>
</style>
