<template>
  <nav-view 
    :scrollbar="false" 
    :loading="isLoading">
    <template slot="toolbar">
      <date-picker 
        v-model="start" 
        :range="range"/>

      <div 
        class="btn-group btn-group-sm" 
        role="group" 
        aria-label="schedule range">
        <button
          type="button"
          @click="range = 'Week'"
          :class="[{'active' : range === 'Week'} ,'btn btn-secondary']"
        >Week</button>
        <button
          type="button"
          @click="range = 'Month'"
          :class="[{'active' : range === 'Month'} ,'btn btn-secondary']"
        >Month</button>
      </div>

      <div 
        class="btn-group btn-group-sm" 
        role="group" 
        aria-label="schedule view">
        <button
          type="button"
          @click="view = 'user'"
          :class="[{'active' : view === 'user'} ,'btn btn-secondary']"
        >Employee</button>
        <button
          type="button"
          @click="view = 'job'"
          :class="[{'active' : view === 'job'} ,'btn btn-secondary']"
        >Jobs</button>
      </div>
    </template>

    <template slot="drawer-toolbar">
      <div class="p-2">
        <input
          v-model="query"
          type="text"
          class="form-control form-control-sm"
          placeholder="search here..."
        >
      </div>
    </template>

    <template slot="drawer">
      <ul 
        id="dp-draggable" 
        class="res-menu">
        <template v-if="view === 'job'">
          <template v-for="res in this.sidebarResources">
            <li 
              :key="res.id" 
              :data-id="res.id" 
              class="res-user">
              <a href="#">
                <img 
                  :src="res.meta.avatar" 
                  class="rounded">
                <span class="caption">{{ res.name }}</span>
              </a>
            </li>
          </template>
        </template>
        <template v-else>
          <template v-for="res in this.sidebarResources">
            <li 
              :key="res.id" 
              :data-id="res.id" 
              class="res-job">
              <a href="#">
                <span class="caption">{{ res.title }}</span>
                <br>
                <span class="caption">Code: {{ res.code }}</span>
              </a>
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
        :start-date="start"
        :view="view"
        :range="range"
        :time-header-auto-fit="false"
        :time-header-height="35"
        :height="dpHeight"
        :event-height="45"
        :events="scheduleEvents"
        :resource="scheduleResources"
      />

      <assign-modal 
        v-model="assignModal" 
        :event="event" 
        :view="view"/>
      <update-modal 
        v-model="updateModal" 
        :event="event" 
        :view="view"/>
    </template>
  </nav-view>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import NavView from "../../../framework/NavView.vue";
import DatePicker from "../../../framework/BaseDatePicker.vue";
import BaseDayPilot from "../schedules/BaseDayPilot.vue";
import AssignModal from "../schedules/AssignModal.vue";
import UpdateModal from "../schedules/UpdateModal.vue";
import pageMixin from "../../../../mixins/page-mixin";

export default {
  name: "Scheduler",

  mixins: [pageMixin],

  components: {
    "nav-view": NavView,
    "day-pilot": BaseDayPilot,
    "date-picker": DatePicker,
    "assign-modal": AssignModal,
    "update-modal": UpdateModal
  },

  data: function() {
    return {
      event: null,
      view: "user",
      range: "Month",
      query: "",
      start: DayPilot.Date.today().firstDayOfMonth(),
      assignModal: false,
      updateModal: false
    };
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList",
      dpHeight: "core/getUiContentHeight"
    }),

    dpView: function() {
      return this.view ? "user" : "job";
    },

    dpRange: function() {
      return this.range ? "Month" : "Week";
    },

    sidebarResources: function() {
      if (this.view === "user") {
        if (this.query != "")
          return this.getList(
            "job",
            item =>
              item.title.toLowerCase().indexOf(this.query.toLowerCase()) > -1 ||
              item.code.toLowerCase().indexOf(this.query.toLowerCase()) > -1
          );
        return this.getList("job");
      } else {
        if (this.query != "")
          return this.getList(
            "user",
            item =>
              item.name.toLowerCase().indexOf(this.query.toLowerCase()) > -1
          );
        return this.getList("user");
      }
    },

    scheduleEvents: function() {
      if (this.view === "user")
        return this.getList("schedule").map(item => {
          return {
            id: item.id,
            resource: item.user_id,
            start: item.start,
            end: item.end,
            text: item.job.title
          };
        });
      else {
        return this.getList("schedule").map(item => {
          return {
            id: item.id,
            resource: item.job_id,
            start: item.start,
            end: item.end,
            text: item.user.name
          };
        });
      }
    },

    scheduleResources: function() {
      if (this.view === "user")
        return this.getList("user").map(item => {
          return { id: item.id, name: item.name, avatar: item.meta.avatar };
        });
      else {
        return this.getList("job").map(item => {
          return { id: item.id, name: item.title, code: item.code };
        });
      }
    }
  },

  mounted() {
    this.start = DayPilot.Date.today()
      .firstDayOfMonth()
      .toString("yyyy-MM-dd");
    this.loadingStart();
    let p1 = this.fetchList({ resource: "user" });
    let p2 = this.fetchList({ resource: "job" });
    let p3 = this.fetchList({
      resource: "schedule",
      query: { with: "user,job" }
    });
    Promise.all([p1, p2, p3])
      .then(() => {
        this.makeDraggable();
        this.loadingStop();
      })
      .catch(error => {
        console.log(error);
        this.loadingStop();
      });
  },

  methods: {
    ...mapActions({
      fetchList: "resource/list",
      create: "resource/create",
      update: "resource/update"
    }),

    makeDraggable() {
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

    // DP Handlers
    rangeSelectHandler(event) {
      event.name = this.scheduleResources.find(
        item => item.id == event.resourceId
      ).name;
      this.event = event;
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
      item.start = event.start;
      item.end = event.end;
      item.offsite = false;
      item.break_length = 0;
      item.type = "schedule";
      item.event_type = "scheduled";

      this.create({ resource: "schedule", data: item })
        .then(respond => {
          this.showMessage(`Event created successfuly.`, "success");
          this.$emit("input", false);
        })
        .catch(error => {
          this.showMessage(error.message, "danger");
        });
    },

    moveHandler(event) {
      console.log("move");
      console.log(event);

      this.update({
        resource: "schedule",
        id: event.eventId,
        data: {
          user_id: event.resourceId,
          start: event.newStart,
          end: event.newEnd
        }
      })
        .then(() => {
          this.showMessage(`Event updated successfuly.`, "success");
        })
        .catch(error => {
          this.showMessage(error.message, "danger");
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
          this.showMessage(error.message, "danger");
        });
    }
  }
};
</script>

<style scoped>
.btn-group .btn {
  font-size: 0.8rem !important;
}

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
  padding: 5px;
  cursor: move;
  margin-bottom: 5px;
  border: 1px solid rgba(0, 0, 0, 0.1);
  background-color: rgba(255, 255, 255, 0.05);
}

.res-menu li:active {
  cursor: help;
}

.res-menu li a {
  cursor: -webkit-grab;
  color: black;
  font-size: 12px;
  text-decoration: none;
}

.res-menu li img {
  margin-right: 7px;
  width: 40px;
  height: 40px;
  background-color: transparent;
}
</style>
