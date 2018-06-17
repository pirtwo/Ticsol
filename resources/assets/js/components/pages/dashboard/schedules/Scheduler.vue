<template>
    <nav-view 
        v-bind:scrollbar="true" 
        v-bind:loading="loading"
        menu-title="Scheduler" 
        drawer-title="Actions"> 

        <template slot="menu">

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
                range="day"
                scale="CellDuration"                
                v-bind:days="7"
                v-bind:cell-duration="10"   
                v-bind:cell-width="40"  
                v-bind:event-height="40"                      
                v-bind:resource="[{'name': 'Resource 1', 'id': 'R1' },{'name': 'Resource 3','id': 'R3' },{'name': 'Resource 4','id': 'R4' },{'name': 'Resource 5','id': 'R5' }]"
                v-bind:events="[{'id': 1,'resource': 'R1',  'start': '2018-06-04T00:00:00', 'end': '2018-06-08T00:00:00',   'text': 'Event 1' },{'id': 2, 'resource': 'R1','start': '2018-06-06T00:00:00','end': '2018-06-11T00:00:00', 'text': 'Event 2'}]">
            </day-pilot>
        </template>

    </nav-view>
</template>

<script>
import DayPilot from "../schedules/DayPilot.vue";
import NavView from "../../../framework/NavView.vue";
import { mapGetters, mapActions } from "vuex";

export default {
  name: "Scheduler",
  components: {
    "nav-view": NavView,
    "day-pilot": DayPilot
  },
  data: function() {
    return {
      events: [],
      resources: [],
      startDate: Date.now(),
      loading: false,
    };
  },
  mounted() {
    this.listJobsHandler();
    this.listUsersHandler();
  },
  computed: {
    ...mapGetters({
      resource: "sidebar/getResource",
      type: "sidebar/getResourceType"
    })
  },
  methods: {
    ...mapActions(["sidebar/listJobs", "sidebar/listUsers"]),
    listJobsHandler() {
      this.loading = true;
      this.$store
        .dispatch("sidebar/listJobs")
        .then(done => {
          this.loading = false;
        })
        .catch(error => {
          console.log(error);
        });
    },
    listUsersHandler() {
      this.loading = true;
      this.$store
        .dispatch("sidebar/listUsers")
        .then(done => {
          this.loading = false;
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>

<style scoped>

</style>
