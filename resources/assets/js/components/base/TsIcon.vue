<template>
  <i class="material-icons">
    <slot>
      <!-- fallback content -->
      {{ icon }}
    </slot>
  </i>
</template>

<script>
export default {
  name: 'TsIcon',

  props:['icon']
}
</script>

<style scoped>
</style>
