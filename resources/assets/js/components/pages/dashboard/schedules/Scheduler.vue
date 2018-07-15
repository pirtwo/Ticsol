<template>
    <nav-view 
        :scrollbar="false" 
        :loading="loading"        
        drawer-title="Actions"> 

        <template slot="toolbar">
                             
        </template>

        <template slot="drawer">
            <ul id="dp-draggable" class="navview-menu">                
            <template v-if="this.type === 'employee'">
                <template v-for="res in this.resource">  
                    <li :key="res.id" v-bind:data-id="res.id">
                        <a href="#">
                            <span class="caption">{{ res.name }}</span>
                        </a>                        
                    </li>
                </template>  
            </template>  
            <template v-else>
                <template v-for="res in this.resource">  
                    <li :key="res.id" v-bind:data-id="res.id">
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
        </template>

    </nav-view>
</template>

<script>
import DayPilot from "../schedules/DayPilot.vue";
import NavView from "../../../framework/NavView.vue";
import AssignUserModal from "../schedules/AssignUserModal.vue";
import { mapGetters, mapActions } from "vuex";

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

  mounted() {
    this.loading = true;
    this.sidebarListUsers().then(() => {
      this.scheduleInti({ resource: "user" }).then(() => {
        this.loading = false;
      });
    });
  },

  computed: {
    ...mapGetters({
      type: "sidebar/getResourceType",
      height: "appUI/getContentHeight",
      resource: "sidebar/getResource",
      scheduleEvents: "schedule/getEvents",
      scheduleResources: "schedule/getResources"
    })
  },

  methods: {
    ...mapActions({
      scheduleInti: "schedule/initi",
      scheduleCreate: "schedule/create",
      scheduleUpdate: "schedule/update",
      sidebarListJobs: "sidebar/listJobs",
      sidebarListUsers: "sidebar/listUsers"
    }),

    rangeSelectHandler(event) {
      event.userName = this.resource[event.resourceId - 1].name;
      this.event = event;
      this.assignUserPopup = true;
    },

    clickHandler(event) {
      console.log("click");
      console.log(event);
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
.controls {
  margin-left: auto;
  margin-right: 10px;
}
</style>
