<template>
  <div 
    ref="statusbar" 
    class="statusbar"
  >
    <ts-dropdown 
      :btn-class="'btn-light'" 
      :menu-class="'dropdown-menu-right'" 
      :menu-size="'md'"
    >
      <template slot="button">
        <ts-icon class="icon">
          notifications
        </ts-icon>
        <span class="caption">NOTIFs</span>
        <span 
          class="badge badge-danger" 
          v-show="unSeen > 0"
        >{{ unSeen }}</span>
      </template>
      <template slot="dropdown">
        <div class="statusbar__dropdown__controls">
          <button 
            class="btn btn-link" 
            type="button" 
            @click="clearAll"
          >
            clear all
          </button>
        </div>
        <div 
          class="text-center" 
          v-show="logs.length == 0"
        >
          List is empty
        </div>
        <div class="statusbar__dropdown__list">
          <!-- list -->
          <ul class="list-group">
            <li 
              class="list-group-item list-group-item-action" 
              v-for="(value, index) in logList"
              :key="index"
            >
              <ts-notification 
                :title="value.title"
                :type="value.type" 
                :icon="getIcon(value.type)" 
                :message="value.message" 
                :footer="value.footer"
              />
            </li>
          </ul><!-- list END -->
        </div>
      </template>
    </ts-dropdown>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  name: "AppStatusbar",

  data() {
    return {};
  },

  watch: {},

  computed: {
    ...mapGetters({
      logs: "core/getAppLogs"
    }),

    logList: function() {
      return this.logs.slice(0).sort((a, b) => {
        new Date(b.date) - new Date(a.date);
      });
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
      clearLog: "core/clearLog"
    }),

    onHide(e) {
      if (e.clickEvent === undefined) return;
      let elm = this.$refs.statusbar;
      let target = e.clickEvent.target;
      if (elm.contains(target)) e.preventDefault();
    },

    hoverLog(log) {},

    clearAll() {
      this.clearLog();
    },

    getDate(date) {
      let options = {
        day: "numeric",
        year: "2-digit",
        month: "short",
        hour: "2-digit",
        minute: "2-digit"
      };
      let locale = "en-AU";
      return new Date(date).toLocaleDateString(locale, options);
    },

    getIcon(type) {
      switch (type) {
        case "primary":
          return "notifications_active";
        case "secondary":
          return "notifications_active";
        case "success":
          return "done";
        case "danger":
          return "error_outline";
        case "warning":
          return "warning";
        case "info":
          return "info";
        default:
          return "notifications_active";
      }
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
}

.statusbar__dropdown__list .alert {
  margin-bottom: 0px;
}
</style>