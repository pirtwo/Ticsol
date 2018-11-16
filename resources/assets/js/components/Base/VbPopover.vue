<template>
  <div 
    class="popover" 
    role="tooltip">
    <div class="arrow"/>
    <h3 class="popover-header">
      <slot name="title"/>
    </h3>
    <div class="popover-body">
      <slot name="body"/>
    </div>
  </div>
</template>

<script>
export default {
  name: "VbPopover",

  props: ["container", "delay", "title", "body"]
};
</script>

<style>
</style>
