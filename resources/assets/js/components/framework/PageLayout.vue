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
      mainHeight: "appUI/setMainHeight"
    }),

    resizeHandler() {
      let head = $(".header").outerHeight() | 0;
      let foot = $(".footer").outerHeight() | 0;
      let main = $(window).height() - (head + foot);
      $(".main").css("height", main);
      this.mainHeight({ height: main });
    }
  }
};
</script>

<style scoped>

</style>

