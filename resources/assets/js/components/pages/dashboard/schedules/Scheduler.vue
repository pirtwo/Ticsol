<template>
    <nav-view 
        v-bind:scrollbar="false" 
        v-bind:loading="loading"
        menu-title="Scheduler" 
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
                v-on:range-selected="selectHandler"
                v-on:event-draged="dragHandler"
                v-on:event-moved="moveHandler"
                range="Month"
                scale="Day"
                time-header-format="Weeks/Days"
                crosshair="Header"
                v-bind:time-header-auto-fit="false"
                v-bind:time-header-height="30"
                v-bind:height="height"
                v-bind:cell-width="40"  
                v-bind:event-height="60"  
                v-bind:events="scheduleEvents"                    
                v-bind:resource="scheduleResources">
            </day-pilot>   
            <assign-user-modal
              v-model="assignUserPopup"
              v-bind:event="event"
            ></assign-user-modal>         
        </template>

    </nav-view>
</template>

<script>
import DayPilot from "../schedules/DayPilot.vue";
import NavView from "../../../framework/NavView.vue";
import AssignUserModal from '../schedules/AssignUserModal.vue';
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
      sidebarListJobs: "sidebar/listJobs",
      sidebarListUsers: "sidebar/listUsers"
    }),

    selectHandler(event) {
      event.userName = this.resource[event.resourceId - 1].name;
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
