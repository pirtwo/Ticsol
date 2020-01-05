<template>
  <app-main :scrollbar="false" :loading="isLoading" padding="p-0">
    <template slot="toolbar">
      <ts-datescroller
        v-model="currentDate"
        range="Week"
        :step-size="stepSize"
        :step-type="stepType"
        @input="populateSchedule"
      />

      <button type="button" class="btn btn-sm ml-2" @click="showFilter = true">
        <i class="material-icons">filter_list</i>
      </button>
    </template>

    <template slot="drawer">
      <ul class="v-menu">
        <li class="menu-title">Actions</li>
        <li>
          <router-link tag="button" class="btn" :to="{ name: 'teamCreate' }">New</router-link>
        </li>
        <li class="menu-title">Links</li>
      </ul>
    </template>

    <template slot="content">
      <scheduler
        :config="dpConfig"
        :height="viewHeight - 45"
        :start-date="startDate"
        :events="scheduleEvents"
        :resource="scheduleResources"
        @event-clicked="eventClicked"
        @event-render="eventRender"
        @resheader-render="resheaderRender"
        @timeheader-render="timeheaderRender"
      />
      <ts-filter
        v-model="query"
        :show.sync="showFilter"
        :columns="filterColumns"
        @apply="populateSchedule"
      />
    </template>
  </app-main>
</template>

<script>
import moment from "moment";
import DPScheduler from "../../../base/DPScheduler";
import pageMixin from "../../../../mixins/page-mixin";
import { mapGetters, mapActions } from "vuex";

export default {
  mixins: [pageMixin],

  props: ["col", "opt", "val"],

  components: {
    scheduler: DPScheduler
  },

  data() {
    return {
      query: [],
      showFilter: false,
      stepSize: 28,
      stepType: "d",
      currentDate: null,
      pager: {
        page: 1,
        perPage: 10,
        pageCount: 10
      },
      dpConfig: {
        days: 28,
        scale: "Day",
        weekStarts: 1,
        heightSpec: "Fixed",
        cellWidthSpec: "Auto",
        rowMinHeight: 60,
        durationBarVisible: false,
        theme: "scheduler_green",
        timeRangeSelectedHandling: "Enabled",
        eventMoveHandling: "Disabled",
        eventResizeHandling: "Disabled",
        eventDeleteHandling: "Disabled",
        eventClickHandling: "Enabled",
        eventHoverHandling: "Disabled",
        timeHeaders: [
          { groupBy: "Week", format: "MMM yyyy" },
          { groupBy: "Day", format: "dd" }
        ]
      }
    };
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList",
      viewHeight: "core/getUiContentHeight"
    }),

    scheduleEvents: function() {
      return this.getList("timesheet").map(item => {
        return {
          id: item.id,
          resource: item.creator_id,
          start: new window.DayPilot.Date(item.week_start),
          end: new window.DayPilot.Date(item.week_end).addDays(1),
          text: `${item.schedules.length} Items - ${item.total_hours.substr(
            0,
            2
          )} Hours`,
          status: item.request.status
        };
      });
    },

    scheduleResources: function() {
      return this.getList("user").map(item => {
        return { id: item.id, name: item.fullname, avatar: item.avatar };
      });
    },

    startDate: function() {
      if (!this.currentDate) return "";
      return this.currentDate.start.format("YYYY-MM-DD");
    },

    filterColumns: function() {
      return [
        {
          key: "start",
          value: "Schedule\\Start-Date",
          type: "date",
          placeholder: "Search for Start-Date..."
        },
        {
          key: "end",
          value: "Schedule\\End-Date",
          type: "date",
          placeholder: "Search for End-Date..."
        },
        {
          key: "schedule.job.title",
          value: "Schedule\\Job\\Title",
          type: "string",
          placeholder: "Search for Job\\Title..."
        },
        {
          key: "schedule.job.code",
          value: "Schedule\\Job\\Code",
          type: "string",
          placeholder: "Search for Job\\code..."
        },
        {
          key: "schedule.job.parent.title",
          value: "Schedule\\Job\\Parent\\Title",
          type: "string",
          placeholder: "Search for Job\\code..."
        },
        {
          key: "schedule.user.firstname",
          value: "Schedule\\User\\Firstname",
          type: "string",
          placeholder: "Search for User\\Firstname..."
        },
        {
          key: "schedule.user.lastname",
          value: "Schedule\\User\\Lastname",
          type: "string",
          placeholder: "Search for User\\Lastname..."
        }
      ];
    }
  },

  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.populateSchedule();
    });
  },

  beforeRouteLeave(to, from, next) {
    this.clear("user");
    this.clear("timesheet");
    next();
  },

  methods: {
    ...mapActions({
      list: "resource/list",
      clear: "resource/clearResource"
    }),

    populateSchedule() {
      this.startLoading();

      let p1 = this.list({ resource: "user" });
      let p2 = this.list({
        resource: "timesheet",
        query: { with: "request,schedules" }
      });

      Promise.all([p1, p2]).then(() => {
        this.stopLoading();
      });
    },

    eventClicked(e) {
      console.log(e);
      let target = e.e.data;
      this.$router.push({
        name: "timesheetDetails",
        params: {
          id: target.id,
          start: target.start.toString("yyyy-MM-dd"),
          end: target.end.addDays(-1).toString("yyyy-MM-dd")
        }
      });
    },

    eventRender(e) {
      console.log(e);
      e.data.html = `
      <div style='padding:10px; margin:2px; border-radius:2px; background-color:whitesmoke; border: 1px solid #b9b9b9;'>
        <div>${e.data.text}</div>
        <span class="badge badge-${
          e.data.status === "approved" ? "success" : "secondary"
        }">${e.data.status.toUpperCase()}</span>
      </div>`;
    },

    resheaderRender(e) {
      e.resource.html = `
      <div style='display:flex;align-items:center;padding:10px;'>
        <img src=${e.resource.avatar} class='mr-1' width=40 height=40 />
        <div style='max-width:100px;'>${e.resource.data.name}</div>
      </div>`;
    },

    timeheaderRender(e) {
      e.header.html =
        e.header.level === 0
          ? `${e.header.start.toString("MMM dd")} - ${e.header.start
              .addDays(6)
              .toString("MMM dd")}`
          : `${e.header.start.getDay()}`;
    }
  }
};
</script>

<style lang="scss" scoped>
</style>