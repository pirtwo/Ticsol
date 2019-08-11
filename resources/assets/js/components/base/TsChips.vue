<template>
  <div
    class="ez-chips"
    :title="title"
  >
    <div class="ez-chips__content">
      <slot />      
    </div>
    <div
      v-if="btnClose"
      class="ez-chips__btn-close"
      @click="$emit('close', $event.target)"
    >
      &times;
    </div>
  </div>
</template>

<script>
export default {
  name: "TsChips",
  props: ["title", "btnClose"],
  methods: {
    close() {}
  }
};
</script>

<style scoped>
.ez-chips {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: flex-start;

  max-width: 150px;
  margin: 5px;
  padding: 5px 10px;
  margin-bottom: 5px;
  border-radius: 5px;
  background-color: #eeeeee;
}

.ez-chips__content {
  flex: 1;
  align-items: center;

  max-width: 150px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  color: black;
  font-size: 12px;
  user-select: none;
}

.ez-chips__btn-close {
  cursor: pointer;
  margin-left: 10px;
  margin-right: 5px;
}
</style>
