<template>
    <nav-view 
        :scrollbar="false" 
        :loading="loading"        
        drawer-title="Actions"> 

        <template slot="toolbar">

          <div class="dp-ctrl form-row ml-auto">
            <div class="col">
              <input type="text" class="form-control form-control-sm" placeholder="filter...">
            </div>
            <div class="col">
              <select class="form-control form-control-sm">
                <option value="">Employee</option>
                <option value="">Job</option>
              </select>   
            </div>                    
          </div> 
          
        </template>

        <template slot="drawer">

            <div class="p-2">
              <input type="text" class="form-control form-control-sm">
            </div>
            
            <ul id="dp-draggable" class="res-menu">                
              <template v-if="true">
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
                              <span class="caption">{{ res.title }}</span>
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
                range="Month"
                scale="Day"
                time-header-format="Weeks/Days"
                crosshair="Header"
                cell-width="Auto"
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
              :event="event"
            ></assign-user-modal>   
            <div class="popover">This is a popover</div>      
        </template>

    </nav-view>
</template>

<script>
import popper from "popper.js";
import DayPilot from "../schedules/DayPilot.vue";
import NavView from "../../../framework/NavView.vue";
import AssignUserModal from "../schedules/AssignUserModal.vue";
import { mapGetters, mapActions } from "vuex";
import Popper from "popper.js";

export default {
  name: "Scheduler",

  components: {
    "nav-view": NavView,
    "day-pilot": DayPilot,
    "assign-user-modal": AssignUserModal
  },

  data: function() {
    return {
      loading: false,
      message: "",
      event: null,
      assignUserPopup: false
    };
  },

  created() {},

  mounted() {
    console.log($(".scheduler_default_scrollable"));
    this.loading = true;
    this.fetch({ resource: "user" });
    this.scheduleInit("user").then(() => {
      this.loading = false;
    });
  },

  computed: {
    ...mapGetters({
      height: "core/getUiContentHeight",
      getList: "resource/getList",
      events: "resource/getScheduleEvents",
      resources: "resource/getScheduleResources"
    }),

    sidebarResources: function() {
      return this.getList("user");
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
      scheduleInit: "resource/scheduleInit"
    }),

    avatar(json) {
      return JSON.parse(json).avatar;
    },

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
      var popper = new Popper(ref, popover, { placement: "top" });
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
      this.scheduleUpdate({
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
      this.message = "resizing...";
      this.scheduleUpdate({
        id: event.eventId,
        data: {
          start: event.newStart,
          end: event.newEnd
        }
      }).then(() => {
        this.message = "event resized successfuly";
      });
    }
  }
};
</script>

<style scoped>
.popover{
  display: none;
  background-color: yellow;
  padding: 10px;
}

.dp-ctrl input {
  max-width: 100px;
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
