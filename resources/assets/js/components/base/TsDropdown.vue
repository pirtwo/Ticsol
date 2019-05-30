<template>
  <div 
    ref="VbDropdown" 
    class="dropdown"
  >
    <button 
      :class="[btnClass, 'btn']" 
      type="button" 
      :id="id" 
      data-toggle="dropdown" 
      aria-haspopup="true" 
      aria-expanded="false"
    >
      <slot name="button" />
    </button>
    <div 
      :class="[menuClass, menuSize, 'dropdown-menu']" 
      :aria-labelledby="id"
    >
      <slot name="dropdown" />
    </div>
  </div>
</template>

<script>
export default {
  name: "TsDropdown",

  props: ["id", "btnClass", "menuClass", "menuSize"],

  mounted() {
    $(".statusbar").on("hide.bs.dropdown", this.onHide);
  },

  methods: {
    onHide(e) {
      if (e.clickEvent === undefined) return;
      let elm = this.$refs.VbDropdown;
      let target = e.clickEvent.target;
      if (elm.contains(target)) e.preventDefault();
    }
  }
};
</script>

<style scoped>
.md {
  width: 400px;
}
</style>
