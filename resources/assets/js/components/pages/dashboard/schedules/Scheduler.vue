<template>
  <app-main
    :drawer-toolbar="true"
    :scrollbar="false"
    :loading="isLoading"
  >
    <template slot="toolbar">
      <ts-datescroller
        v-model="currDate"
        :range="range"
        @input="fetchData"
      />

      <div
        class="btn-group btn-group-sm"
        role="group"
        aria-label="schedule range"
      >
        <button
          type="button"
          @click="range = 'Week'"
          :class="[{'active' : range === 'Week'} ,'btn']"
        >
          Week
        </button>
        <button
          type="button"
          @click="range = 'Month'"
          :class="[{'active' : range === 'Month'} ,'btn']"
        >
          Month
        </button>
      </div>

      <div
        class="btn-group btn-group-sm mr-1"
        role="group"
        aria-label="schedule view"
      >
        <button
          type="button"
          @click="view = 'user'"
          :class="[{'active' : view === 'user'} ,'btn']"
        >
          Employee
        </button>
        <button
          type="button"
          @click="view = 'job'"
          :class="[{'active' : view === 'job'} ,'btn']"
        >
          Jobs
        </button>
      </div>

      <button
        type="button"
        class="btn btn-sm mr-auto"
        @click="showFilter = true"
      >
        <i class="material-icons">filter_list</i>
      </button>
    </template>

    <template slot="drawer-toolbar">
      <div class="p-2">
        <input
          v-model="sidebarQuery"
          type="text"
          class="form-control form-control-sm"
          placeholder="search here..."
        >
      </div>
    </template>

    <template slot="drawer">
      <ul
        id="dp-draggable"
        class="res-menu"
      >
        <template v-if="view === 'job'">
          <template v-for="user in this.sidebarResources">
            <li
              :key="user.id"
              :data-id="user.id"
              :class="[{ 'draggable' : userCan('schedule', ['full', 'create']) }, 'res-user d-flex align-items-center']"
            >
              <img
                :src="user.avatar"
                class="rounded"
              >
              <div class="res__caption">
                {{ user.fullname }}
              </div>
            </li>
          </template>
        </template>
        <template v-else>
          <template v-for="job in this.sidebarResources">
            <li
              :key="job.id"
              :data-id="job.id"
              :class="[{ 'draggable' : userCan('schedule', ['full', 'create']) }, 'res-job']"
            >
              <div class="res__title">
                {{ job.title }}
              </div>
              <div class="res__caption">
                Code: {{ job.code }}
              </div>
            </li>
          </template>
        </template>
      </ul>
    </template>

    <template slot="content">
      <day-pilot
        @range-selected="rangeSelectHandler"
        @event-clicked="clickHandler"
        @event-dragged="draggHandler"
        @event-moved="moveHandler"
        @event-hoverd="hoverHandler"
        @event-resized="resizeHandler"
        scale="Day"
        time-header-format="Weeks/Days"
        crosshair="Header"
        cell-width="Auto"
        :week-start="'Mon'"
        :start-date="startDate"
        :view="view"
        :range="range"
        :time-header-auto-fit="false"
        :time-header-height="35"
        :height="dpHeight"
        :event-height="57"
        :events="scheduleEvents"
        :resource="scheduleResources"
      />

      <assign-modal
        v-model="assignModal"
        :event="event"
        :view="view"
      />
      
      <update-modal
        v-model="updateModal"
        :event="event"
        :view="view"
      />

      <ts-filter
        v-model="query"
        :show.sync="showFilter"
        :columns="filterColumns"
        @apply="fetchData"
      />
    </template>
  </app-main>
</template>

<script>
import moment from "moment";
import { mapGetters, mapActions } from "vuex";
import BaseDayPilot from "../schedules/BaseDayPilot.vue";
import AssignModal from "../schedules/AssignModal.vue";
import UpdateModal from "../schedules/UpdateModal.vue";
import pageMixin from "../../../../mixins/page-mixin";

export default {
  name: "Scheduler",

  mixins: [pageMixin],

  components: {
    "day-pilot": BaseDayPilot,
    "assign-modal": AssignModal,
    "update-modal": UpdateModal
  },

  data: function() {
    return {
      query: [],
      showFilter: false,
      event: null,
      view: "user",
      range: "Month",
      sidebarQuery: "",
      currDate: null,
      assignModal: false,
      updateModal: false,
      businessHours: [],
      leaveJob: {
        id: "sys-001",
        name: "LEAVE",
        code: "LEAVE-SYS"
      },
      unaviJob: {
        id: "sys-002",
        name: "UNAVAILABLE",
        code: "UNAVAILABLE-SYS"
      }
    };
  },  

  computed: {
    ...mapGetters({
      userCan: "user/can",
      getList: "resource/getList",
      clientId: "user/getClientId",         
      dpHeight: "core/getUiContentHeight",
      userScheduleView: "user/getScheduleView",
      userScheduleRange: "user/getScheduleRange",
    }),

    startDate: function() {
      if (!this.currDate) return "";
      return this.currDate.start.startOf("d").format("YYYY-MM-DDTHH:mm:ss");
    },

    endDate: function() {
      if (!this.currDate) return "";
      return this.currDate.end.format("YYYY-MM-DDTHH:mm:ss");
    },

    sidebarResources: function() {
      if (this.view === "user") {
        if (this.sidebarQuery != "")
          return this.getList(
            "job",
            item =>
              item.title
                .toLowerCase()
                .indexOf(this.sidebarQuery.toLowerCase()) > -1 ||
              item.code.toLowerCase().indexOf(this.sidebarQuery.toLowerCase()) >
                -1
          );
        return this.getList("job");
      } else {
        if (this.sidebarQuery != "")
          return this.getList(
            "user",
            item =>
              item.fullname.toLowerCase().indexOf(this.sidebarQuery.toLowerCase()) >
              -1
          );
        return this.getList("user");
      }
    },

    scheduleEvents: function() {
      if (this.view === "user")
        return this.getList("schedule").map(item => {
          let eventText = "";

          if (item.job) {
            eventText = item.job.title;
          } else {
            eventText =
              item.event_type == "leave"
                ? this.leaveJob.name
                : this.unaviJob.name;
          }

          return {
            id: item.id,
            userId: item.user_id,
            resource: item.user_id,
            start: new window.DayPilot.Date(item.start),
            end: new window.DayPilot.Date(item.end),
            text: eventText,
            type: item.event_type,
            status: item.status
          };
        });
      else {
        return this.getList("schedule").map(item => {
          let eventResource = "";

          if (item.event_type == "scheduled") eventResource = item.job_id;
          else if (item.event_type == "leave") eventResource = this.leaveJob.id;
          else if (item.event_type == "unavailable")
            eventResource = this.unaviJob.id;

          return {
            id: item.id,
            userId: item.user_id,
            resource: eventResource,
            start: new window.DayPilot.Date(item.start),
            end: new window.DayPilot.Date(item.end),
            text: item.user.fullname,
            type: item.event_type,
            status: item.status
          };
        });
      }
    },

    scheduleResources: function() {
      if (this.view === "user")
        return this.getList("user").map(item => {
          return { id: item.id, name: item.fullname, avatar: item.avatar };
        });
      else {
        let list = this.getList("job").map(item => {
          return { id: item.id, name: item.title, code: item.code };
        });

        list.unshift(this.unaviJob, this.leaveJob);

        return list;
      }
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

  beforeRouteLeave(to, from, next) {
    this.clear("job");
    this.clear("user");
    this.clear("schedule");
    next();
  },

  mounted() {    
    this.view = this.userScheduleView || "user";
    this.range = this.userScheduleRange || "Month";

    let query = [];
    query.push({
      opt: "inRange",
      col: "",
      val: `${this.startDate},${this.endDate}`
    });

    this.startLoading();
    let p1 = this.fetchList({ resource: "user" });
    let p2 = this.fetchList({ resource: "job" });
    let p3 = this.fetchList({
      resource: "schedule",
      query: this.$queryBuilder(null, null, ["user", "job", "request", "activities"], query)
    });
    let p4 = this.show({resource:"client", id: this.clientId }).then(data=>{
      this.businessHours = data.meta.business_hours ? data.meta.business_hours:[];
    });

    Promise.all([p1, p2, p3, p4])
      .then(() => {
        this.makeDraggable();
        this.stopLoading();
      })
      .catch(error => {
        console.log(error);
        this.stopLoading();
      });
  },

  methods: {
    ...mapActions({
      clear: "resource/clearResource",
      fetchList: "resource/list",
      create: "resource/create",
      update: "resource/update",
      show: "resource/show",
    }),

    makeDraggable() {
      if(!this.userCan('schedule', ['full', 'create'])) return;

      var parent = document.getElementById("dp-draggable");
      var items = parent.getElementsByTagName("li");
      for (var i = 0; i < items.length; i++) {
        var e = items[i];
        var item = {
          element: e,
          id: e.getAttribute("data-id"),
          text: e.innerText,
          keepElement: true,
          duration: e.getAttribute("data-duration")
        };
        window.DayPilot.Scheduler.makeDraggable(item);
      }
    },

    fetchData() {
      let query = [...this.query];
      query.push({
        opt: "inRange",
        col: "",
        val: `${this.startDate},${this.endDate}`
      });

      this.startLoading();
      this.fetchList({
        resource: "schedule",
        query: this.$queryBuilder(null, null, ["user", "job", "request", "activities"], query)
      })
        .then(() => {
          this.makeDraggable();
          this.stopLoading();
        })
        .catch(error => {
          console.log(error);
          this.stopLoading();
        });
    },

    // DP Handlers
    rangeSelectHandler(event) {  
      event.name = this.scheduleResources.find(
        item => item.id == event.resourceId
      ).name;
      this.event = event;
      
      this.event.start = new window.DayPilot.Date(
        `${event.start.toString("yyyy-MM-dd")}T${this.getBusinessHourStart(event.start.getDayOfWeek())}`);

      this.event.end = new window.DayPilot.Date(
        `${event.end.toString("yyyy-MM-dd")}T${this.getBusinessHourEnd(event.end.getDayOfWeek())}`);
        
      this.assignModal = true;
    },

    clickHandler(event) {
      this.event = this.getList(
        "schedule",
        item => item.id == event.eventId
      )[0];
      this.event.name = this.scheduleResources.find(
        item => item.id == event.resourceId
      ).name;
      this.event.start = event.start;
      this.event.end = event.end;
      this.updateModal = true;
    },

    hoverHandler(event) {
      console.log("hover");
      console.log(event);
    },

    draggHandler(event) {
      console.log("drag");
      console.log(event);

      let item = {};
      item.user_id = this.view == "user" ? event.resourceId : event.eventId;
      item.job_id = this.view == "job" ? event.resourceId : event.eventId;
      item.status = "tentative";
      item.start = `${event.start.toString("yyyy-MM-dd")} ${this.getBusinessHourStart(event.start.getDayOfWeek())}`;
      item.end = `${event.end.toString("yyyy-MM-dd")} ${this.getBusinessHourEnd(event.end.getDayOfWeek())}`;
      item.offsite = false;
      item.break_length = 0;
      item.type = "schedule";
      item.event_type = "scheduled";

      console.log(item);

      this.create({ resource: "schedule", data: item })
        .then(respond => {
          this.showMessage(`Event created successfuly.`, "success");
          this.$emit("input", false);
        })
        .catch(error => {
          this.showMessage(error.response.data.message, "danger");
        });
    },

    moveHandler(event) {
      console.log("move");
      console.log(event);

      this.update({
        resource: "schedule",
        id: event.eventId,
        data: {
          start: event.newStart,
          end: event.newEnd
        }
      })
        .then(() => {
          this.showMessage(`Event updated successfuly.`, "success");
        })
        .catch(error => {
          this.showMessage(error.response.data.message, "danger");
        });
    },

    resizeHandler(event) {
      this.update({
        resource: "schedule",
        id: event.eventId,
        data: {
          start: event.newStart,
          end: event.newEnd
        }
      })
        .then(() => {
          this.showMessage(`Event updated successfuly.`, "success");
        })
        .catch(error => {
          this.showMessage(error.response.data.message, "danger");
        });
    },

    getBusinessHourStart(dayNum){
      let day = this.businessHours.find(item => item.day == dayNum);
      if(!day || !day.isopen)
        return "00:00:00";
      return `${day.start}:00`;
    },

    getBusinessHourEnd(dayNum){
      let day = this.businessHours.find(item => item.day == dayNum);
      if(!day || !day.isopen)
        return "00:01:00";
      return `${day.end}:00`;
    },
  }
};
</script>

<style scoped>
.btn-group .btn:first-child {
  margin-left: 5px;
}

.dp-ctrl {
  width: 60%;
}

.dp-ctrl input {
  max-width: 140px;
  font-size: 0.8rem;
}

.dp-ctrl .btn {
  font-size: 1rem;
  line-height: 1rem !important;
}

.dp-ctrl i {
  font-size: inherit;
  line-height: 1rem !important;
}

.res-menu {
  list-style: none;
  padding: 3px;
}

.res-menu li {
  padding: 7.5px 12px;  
  margin: 8px 5px;
}

.res-menu li:active {
  cursor: help;
}

.draggable{
  cursor: grab;
}

.res-menu li a {
  font-size: 12px;
  text-decoration: none;
}

.res-menu li img {
  margin-right: 7px;
  width: 40px;
  height: 40px;
  background-color: transparent;
}

.res__title {
  font-size: medium;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.res__caption {
  font-size: small;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
