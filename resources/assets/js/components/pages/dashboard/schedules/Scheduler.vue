<template>
    <nav-view 
        v-bind:scrollbar="true" 
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
                v-bind:resource="[{'name': 'Resource 1', 'id': 'R1' },{'name': 'Resource 2','id': 'R2' }]"
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
      loading: false,
      startDate: Date.now(),
      resources: [],
      events: []
    };
  },
  mounted(){
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
input[type="date"] {
  font-size: 10px;
  padding: 5px;
  height: 25px;
  border: 1px #d9d9d9 solid;
  border-radius: 3px;
}

.select {
  font-size: 10px !important;
  padding: 5px !important;
  height: 25px !important;
  min-width: 80px !important;
  border-radius: 3px !important;
}

.inline-form {
  vertical-align: bottom !important;
}

.form-group {
  margin-top: 1rem !important;
  margin-bottom: 1rem !important;
  padding-left: 7px;
}

.label {
  font-size: 15px !important;
  vertical-align: bottom !important;
}
</style>
