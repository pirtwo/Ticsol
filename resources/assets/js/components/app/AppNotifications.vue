<template>
  <div class="notification-area">
    <template v-for="item in notifs">
      <ts-toast
        :key="item.id"
        :autohide="item.autoHide"
        :delay="item.delay"
        @hidden="hideNotification(item)"
        @click="seenNotification(item)"
        @mouseover="seenNotification(item)"
        class="text-left"
      >
        <template slot="header">
          <ts-icon
            class="mr-2"
            :icon="renderIcon(item)"
            :style="{color:renderIconColor(item)}"
          />
          <strong class="mr-auto">{{ item.title }}</strong>
          <small>{{ renderTime(item) }}</small>
          <button
            type="button"
            class="ml-2 mb-1 close"
            data-dismiss="toast"
            aria-label="Close"
            @click="onClose(item)"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </template>
        <div>{{ item.message }}</div>
      </ts-toast>
    </template>
  </div>
</template>

<script>
import Time from "../../utils/time";
import moment from "moment";
import { mapGetters, mapActions } from "vuex";

export default {
  name: "AppNotifications",

  data() {
    return {
      //
    };
  },

  computed: {
    ...mapGetters({
      getNotifs: "core/getNotifications"
    }),

    notifs: function() {
      return this.getNotifs.filter(item => item.hide == false);
    }
  },

  methods: {
    ...mapActions({
      seen: "core/seenNotification",
      hide: "core/hideNotification"
    }),

    onClose(notif){
      this.seen(notif.id);
    },

    seenNotification(notif) {
      this.seen(notif.id);
    },

    hideNotification(notif) {
      this.hide(notif.id);
    },

    renderIcon(notif) {
      if (notif.type == "connected") return "sync";
      if (notif.type == "disconnected") return "sync_problem";
      if (notif.type == "error") return "error";
      if (notif.type == "warning") return "warning";

      return "info";
    },

    renderIconColor(notif) {
      if (notif.type == "connected") return "green";
      if (notif.type == "disconnected") return "red";
      if (notif.type == "error") return "red";
      if (notif.type == "warning") return "orange";

      return "blue";
    },

    renderTime(notif) {
      return moment(notif.date).format("dd HH:mm");
    }
  }
};
</script>

<style scoped>
.notification-area {
  position: fixed;
  right: 50px;
  bottom: 10px;
  width: 290px;
  z-index: 100;
}
</style>
