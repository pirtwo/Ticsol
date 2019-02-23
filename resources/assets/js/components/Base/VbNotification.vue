<template>
  <div :class="['alert-' + type, 'vb-notif alert d-flex flex-column']">
    <div class="d-flex align-items-center">
      <vb-icon class="vb-notif__icon">
        {{ icon }}
      </vb-icon>
      <span class="vb-notif__title">{{ title }}</span>
    </div>
    <div class="flex-column">
      <div class="vb-notif__msg">
        {{ message }}
      </div>
      <div class="vb-notif__footer">
        {{ footer }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "VbNotification",
  props: ["icon", "type", "title", "message", "footer"]
};
</script>

<style scoped>
.vb-notif{

}

.vb-notif__icon{
    color: black;
}

.vb-notif__title{
    padding-left: 7px;
    font-size: 12px;
}

.vb-notif__msg{
    font-size: 10px;
}

.vb-notif__footer{
    color: darkgray;
    font-size: 10px;
}
</style>
