<template>
  <div
    :class="['alert-' + alert, 'vb-notif alert d-flex flex-column']"
    @mouseover="(e) => {this.$emit('mouseover', e)}"
  >
    <div class="d-flex align-items-center">
      <ts-icon class="vb-notif__icon">
        {{ icon }}
      </ts-icon>
      <span class="vb-notif__title">{{ title }} <ts-badge
        v-if="!seen"
        class="badge-info"
      >New</ts-badge> </span>
      <div class="vb-notif__time">
        {{ time }}
      </div>
    </div>

    <div class="vb-notif__msg">
      {{ message }}
    </div>
  </div>
</template>

<script>
import moment from "moment";

export default {
  name: "TsNotification",

  props: ["type", "title", "message", "seen", "date"],

  computed:{
    time:function(){
      return moment(this.date).fromNow();
    },

    alert:function(){
      if (this.type == "connected") return "success";
      if (this.type == "disconnected") return "danger";
      if (this.type == "error") return "danger";
      if (this.type == "warning") return "warning";

      return "light";
    },

    icon:function(){
      if (this.type == "connected") return "sync";
      if (this.type == "disconnected") return "sync_problem";
      if (this.type == "error") return "error";
      if (this.type == "warning") return "warning";

      return "info";
    }
  },

  methods: {
    //
  }
};
</script>

<style scoped>
.vb-notif {
}

.vb-notif__icon {
  color: black;
}

.vb-notif__title {
  padding-left: 7px;
  font-size: 15px;
  font-weight: bold;
}

.vb-notif__msg {
  font-size: 10px;
  font-size: 13px;
}

.vb-notif__time {
  color: darkgray;
  margin-left: auto;
  font-size: 13px;
}
</style>
