<template>
<div class="row">
    <header v-if="this.header" class="cell-sm-12">
        <slot name="header">

        </slot>
    </header>    
    <main class="cell-sm-12 dashboard">
        <slot></slot>
    </main>   
    <footer v-if="this.footer" class="cell-sm-12">
        <slot name="footer">

        </slot>
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
  }
};
</script>

<style>

</style>
