<template>
    <nav-view 
        :scrollbar="false" 
        :loading="loading"        
        drawer-title="Actions"> 

        <template slot="toolbar">
          <div class="dp-ctrl d-flex justify-content-end"> 

            <date-picker v-model="start" range="Month"></date-picker>

            <label class="switch">
              <input v-model="range" class="switch-input" type="checkbox" />
              <span class="switch-label" data-on="wek" data-off="mon"></span> 
              <span class="switch-handle"></span> 
            </label>

            <label class="switch">
              <input v-model="view" class="switch-input" type="checkbox" />
              <span class="switch-label" data-on="emp" data-off="job"></span> 
              <span class="switch-handle"></span> 
            </label>           

          </div>  

        </template>

        <template slot="drawer">

            <div class="p-2">
              <input type="text" class="form-control form-control-sm">
            </div>
            
            <ul id="dp-draggable" class="res-menu">                
              <template v-if="!view">
                  <template v-for="res in this.sidebarResources">  
                      <li :key="res.id" :data-id="res.id" class="res-user">
                          <a href="#">
                              <img :src="avatar(res.meta)" class="rounded">                              
                              <span class="caption">{{ res.name }}</span>
                          </a>                        
                      </li>
                  </template>  
              </template>  
              <template v-else>
                  <template v-for="res in this.sidebarResources">  
                      <li :key="res.id" :data-id="res.id" class="res-job">
                          <a href="#">
                              <span class="caption">{{ res.title }}</span><br>
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
                :start-date="start"
                :range="range ? 'Week' : 'Month'"                
                time-header-format="Weeks/Days"
                crosshair="Header"
                cell-width="Auto"
                :view="view ? 'user' : 'job'"
                :message="message"
                :time-header-auto-fit="false"
                :time-header-height="35"
                :height="height"                 
                :event-height="45"  
                :events="scheduleEvents"                    
                :resource="scheduleResources">
            </day-pilot>   
            <assign-user-modal
              v-model="assignUserPopup"
              :event="event">
            </assign-user-modal>   
            <div class="popover">This is a popover</div>      
        </template>

    </nav-view>
</template>

<script>
import Popper from "popper.js";
import BaseDayPilot from "../schedules/BaseDayPilot.vue";
import NavView from "../../../framework/NavView.vue";
import AssignUserModal from "../schedules/AssignUserModal.vue";
import DatePicker from "../../../framework/BaseDatePicker.vue";
import { mapGetters, mapActions } from "vuex";

export default {
  name: "Scheduler",

  components: {
    "nav-view": NavView,
    "day-pilot": BaseDayPilot,
    "assign-user-modal": AssignUserModal,
    "date-picker": DatePicker
  },

  data: function() {
    return {
      loading: false,
      message: "",
      event: null,
      view: true,
      range: true,
      start: DayPilot.Date.today().firstDayOfMonth(),
      assignUserPopup: false
    };
  },

  created() {
    this.clear("job");
    this.clear("user");
  },

  mounted() {
    this.start = DayPilot.Date.today()
      .firstDayOfMonth()
      .toString("yyyy-MM-dd");
    this.loading = true;
    this.fetch({ resource: "job" });
    this.scheduleInit("user").then(() => {
      this.loading = false;
    });
  },

  watch: {
    view: function(value) {
      this.scheduleView(value ? "user" : "job");
    },

    start: function(value) {}
  },

  computed: {
    ...mapGetters({
      height: "core/getUiContentHeight",
      events: "resource/getScheduleEvents",
      getList: "resource/getList",
      resources: "resource/getScheduleResources"
    }),

    sidebarResources: function() {
      if (this.view) return this.getList("job");
      else return this.getList("user");
    },

    scheduleEvents: function() {
      return this.events();
    },

    scheduleResources: function() {
      return this.resources();
    }
  },

  methods: {
    ...mapActions({
      fetch: "resource/list",
      clear: "resource/clearList",
      update: "resource/update",
      scheduleInit: "resource/scheduleInit",
      scheduleView: "resource/scheduleView"
    }),

    avatar(json) {
      return JSON.parse(json).avatar;
    },

    // DP Handlers
    rangeSelectHandler(event) {
      event.userName = this.scheduleResources[event.resourceId - 1].name;
      this.event = event;
      this.assignUserPopup = true;
    },

    clickHandler(event) {
      console.log("click");
      console.log(event);

      var ref = $(event.div);
      var popover = $(".popover");
      popover.show();
      var popper = new Popper(ref, popover, {
        placement: "top",
        modifiers: {
          arrow: { enabled: true },
          preventOverflow: { enabled: true },
          hide: { enabled: false }
        }
      });
    },

    hoverHandler(event) {
      console.log("hover");
      console.log(event);
    },

    draggHandler(event) {
      console.log("drag");
      console.log(event);
    },

    moveHandler(event) {
      console.log("move");
      console.log(event);

      this.message = "updating...";
      this.update({
        resource: "schedule",
        id: event.eventId,
        data: {
          user_id: event.resourceId,
          start: event.newStart,
          end: event.newEnd
        }
      }).then(() => {
        this.message = "event updated successfuly";
      });
    },

    resizeHandler(event) {
      this.message = "updating...";
      this.update({
        resource: "schedule",
        id: event.eventId,
        data: {
          start: event.newStart,
          end: event.newEnd
        }
      }).then(() => {
        this.message = "event updated successfuly";
      });
    }
  }
};
</script>

<style scoped>
.popover {
  display: none;
  background-color: yellow;
  padding: 10px;
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

.res-menu li a {
  cursor: move;
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
