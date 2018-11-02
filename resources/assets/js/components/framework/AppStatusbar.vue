<template>
    <div ref="statusbar" class="statusbar dropdown">
        <button class="btn btn-light statusbar__btn" 
            role="button" id="statusbar" 
            data-toggle="dropdown" 
            aria-haspopup="true"
            aria-expanded="false">
            <i class="icon material-icons">
                notifications
            </i>
            <span class="caption">NOTIFs</span>
            <span class="badge badge-light" v-show="unSeen > 0">{{ unSeen }}</span>
        </button>

        <!-- dropdown -->
        <div class="statusbar__dropdown dropdown-menu dropdown-menu-right" aria-labelledby="statusbar">
            <div class="statusbar__dropdown__controls">
                <button class="btn btn-link" type="button" @click="clearAll">clear all</button>
            </div>
            <div class="text-center" v-show="logs.length == 0">List is empty</div>
            <div class="statusbar__dropdown__list">
                <!-- list -->
                <ul class="list-group">
                    <li class="list-group-item list-group-item-action flex-column align-items-start" 
                        v-for="(value, index) in logList"
                        :key="index">
                        <div :class="['alert-' + value.type, 'alert d-flex']" 
                        @mouseover.once="hoverLog(value)">
                            <i class="p-2 mr-2 material-icons">{{ getIcon(value.type) }}</i>
                            <div class="d-flex flex-grow-1 flex-column">
                                <!-- <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ value.title }}</h6>                                    
                                </div> -->
                                <p class="mb-1">{{ value.content }}</p>
                                <small>{{ value.footer }}</small>
                            </div>
                            <small class="d-flex flex-shrink-0 ml-2">
                              {{ getDate(value.date) }}</small>
                        </div>
                    </li>
                </ul><!-- list END -->
            </div>
        </div><!-- dropdown END -->
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
      return this.logs.sort((a, b) => {
        return new Date(b.date) - new Date(a.date);
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

.statusbar__dropdown {
  width: 400px;
  padding: 4px;
  font-size: 12px;
}

.statusbar__dropdown__list {
  max-height: 200px;
  overflow-y: auto;
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