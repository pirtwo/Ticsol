<template>
  <div ref="statusbar" class="statusbar">
    <ts-dropdown :btn-class="'btn-light'" :menu-class="'dropdown-menu-right'" :menu-size="'md'">
      <template slot="button">
        <ts-icon class="icon">notifications</ts-icon>
        <span class="caption">NOTIFs</span>
        <span class="badge badge-danger" v-show="unSeen > 0">{{ unSeen }}</span>
      </template>
      <template slot="dropdown">
        <div class="statusbar__dropdown__controls">
          <button class="btn" type="button" @click="clearAll">clear all</button>
        </div>
        <div class="text-center" v-show="logs.length == 0">List is empty</div>
        <div class="statusbar__dropdown__list">
          <!-- list -->
          <ul class="list-group">
            <li
              class="list-group-item list-group-item-action"
              v-for="notif in logList"
              :key="notif.id"
            >
              <ts-notification v-bind="notif" @mouseover="onHover(notif)"/>
            </li>
          </ul>
          <!-- list END -->
        </div>
      </template>
    </ts-dropdown>
  </div>
</template>

<script>
import moment from "moment";
import { mapGetters, mapActions } from "vuex";

export default {
  name: "AppNotificationsLog",

  data() {
    return {};
  },

  watch: {},

  computed: {
    ...mapGetters({
      logs: "core/getNotifications"
    }),

    logList: function() {
      return this.logs.slice(0).sort((a, b) => b.date - a.date);
    },

    unSeen: function() {
      return this.logs.filter(item => item.seen != true).length;
    }
  },

  mounted() {
    $(".statusbar").on("hide.bs.dropdown", this.onHide);
  },

  methods: {
    ...mapActions({
      seen: "core/seenNotification",
      clearLog: "core/clearNotificationLog"
    }),

    onHide(e) {
      if (e.clickEvent === undefined) return;
      let elm = this.$refs.statusbar;
      let target = e.clickEvent.target;
      if (elm.contains(target)) e.preventDefault();
    },

    onHover(notif) {
      this.seen(notif.id);
    },

    clearAll() {
      this.clearLog();
    }
  }
};
</script>

<style scoped>
.statusbar {
  display: inline-block;
}

.statusbar__dropdown__list {
  padding: 7px;
  max-height: 200px;
  overflow-y: auto;
  overflow-x: hidden;
}

.statusbar__dropdown__list i {
  font-size: 30px;
}

.statusbar__dropdown__list .list-group-item {
  padding: 0px;
  margin-top: 3px;
  border: 0px;
}

.statusbar__dropdown__list .alert {
  margin-bottom: 0px;
}
</style>