<template>
    <nav-view>
        <template slot="pane">
            <ul id="resource" class="navview-menu">                
                <template v-if="this.type === 'employee'">
                    <template v-for="res in this.resource">  
                        <li :key="res.user_id" v-bind:data-id="res.user_id">
                            <a href="#">
                                <span class="caption">{{ res.user_name }}</span>
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
                v-on:listJobs="listJobsHandler"
                v-on:listUsers="listUsersHandler"
                v-bind:dpResource="this.type"
            ></day-pilot>
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
    return {};
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
      this.$store
        .dispatch("sidebar/listJobs")
        .then(done => {})
        .catch(error => {});
    },
    listUsersHandler() {
      this.$store
        .dispatch("sidebar/listUsers")
        .then(done => {})
        .catch(error => {});
    }
  }
};
</script>

<style>
</style>
