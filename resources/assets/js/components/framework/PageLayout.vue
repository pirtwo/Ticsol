<template>
<div class="row">
    <header v-if="this.header" class="header cell-sm-12">
        <slot name="header"></slot>
    </header>    
    <main class="cell-sm-12 main">
        <slot></slot>
    </main>   
    <footer v-if="this.footer" class="footer cell-sm-12">
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
      bounceTime: 1000
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
      let main = $(window).height() - (head + foot);
      $(".main").css("height", main);
    }
  }
};
</script>

<style>
</style>
