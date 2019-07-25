<template>
  <div 
    :class="`spinner-${spinnerStyle}`" 
    role="status"
  >
    <span class="sr-only">{{ sr }}</span>
  </div>
</template>

<script>
export default {
  name: 'TsSpinner',

  props:['spinnerStyle', 'sr']
}
</script>

<style scoped>

</style>
