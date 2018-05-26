<template>
  <ul class="unstyled-list icon-list">
    <li><span class="mif-spinner ani-spin"></span> spinner</li>
    <li><span class="mif-spinner2 ani-spin"></span> spinner2</li>
    <li><span class="mif-spinner3 ani-spin"></span> spinner3</li>
    <li><span class="mif-spinner4 ani-spin"></span> spinner4</li>
    <li><span class="mif-spinner5 ani-spin"></span> spinner5</li>
    <li><span class="mif-loop2 ani-spin"></span> loop2</li>
</ul>
</template>

<script>
export default {
  
}
</script>

<style scoped>
.icon-list [class*=mif-] {
    font-size: 2em !important;
    width: 40px;
    text-align: center;
    vertical-align: middle;
    color: #1d1d1d;
    display: inline-block;
}
</style>