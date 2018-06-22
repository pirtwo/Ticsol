<template>
    <nav-view 
        v-bind:scrollbar="true" 
        v-bind:loading="loading"
        menu-title="Scheduler" 
        drawer-title="Actions"> 

        <template slot="menu">

          <div class="controls md-alignment-right-center">
             <md-button class="md-primary">
              <md-icon>fullscreen</md-icon>
            </md-button>
          </div>
         
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
                v-on:range-selected="selectHandler"
                v-on:event-draged="dragHandler"
                v-on:event-moved="moveHandler"
                range="Week"
                scale="Hour"                
                time-header-format="Days/Hours"
                v-bind:height="500"
                v-bind:cell-width="40"  
                v-bind:event-height="40"  
                v-bind:events="scheduleEvents"                    
                v-bind:resource="scheduleResources">
            </day-pilot>
            <assign-uaser-popup 
              v-bind:show="assignUserPopup"  
              v-bind:event="event"            
              v-on:submit="createEvent"
              v-on:close="assignUserPopup = false">
            </assign-uaser-popup>
        </template>

    </nav-view>
</template>

<script>
import DayPilot from "../schedules/DayPilot.vue";
import NavView from "../../../framework/NavView.vue";
import AssignUserPopup from "../schedules/AssignUserPopup.vue";
import { mapGetters, mapActions } from "vuex";

export default {
  name: "Scheduler",
  components: {
    "nav-view": NavView,
    "day-pilot": DayPilot,
    "assign-uaser-popup": AssignUserPopup
  },

  data: function() {
    return {
      height: 500,
      loading: false,
      event: null,
      assignUserPopup: false
    };
  },

  mounted() {
    this.loading = true;
    this.sidebarListUsers().then(() => {
      this.scheduleInti({ resource: "job" }).then(() => {
        this.loading = false;
      });
    });
  },

  computed: {
    ...mapGetters({
      type: "sidebar/getResourceType",
      resource: "sidebar/getResource",
      scheduleEvents: "schedule/getEvents",
      scheduleResources: "schedule/getResources"
    })
  },

  methods: {
    ...mapActions({
      scheduleInti: "schedule/initi",
      scheduleCreate: "schedule/create",
      sidebarListJobs: "sidebar/listJobs",
      sidebarListUsers: "sidebar/listUsers"
    }),

    selectHandler(event) {      
      this.event = event;
      this.assignUserPopup = true;
    },

    dragHandler(event) {},

    moveHandler(event) {},

    createEvent(data) {
      this.scheduleCreate({ data: data });
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
