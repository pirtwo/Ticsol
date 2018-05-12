<template>
    <nav class="navview-pane">
        <button class="pull-button">
            <span class="default-icon-menu"></span>
        </button>
        <div class="suggest-box">
            <input data-role="search" data-clear-button="false">
            <button class="holder">
                <span class="mif-search"></span>
            </button>
        </div>

        <ul class="navview-menu">
            <li class="active">
                <a href="#">
                    <span class="icon">
                        <span class="mif-stack"></span>
                    </span>
                    <span class="caption">Resource</span>
                </a>
            </li>

            <li class="item-separator"></li>
           
            <div id="resource">                
                <template v-if="resType === 'Job'">
                    <template v-for="res in resource">  
                        <li :key="res.id" v-bind:data-id="res.id">
                            <a href="#">
                                <span class="caption">{{ res.title }}</span>
                            </a>                        
                        </li>
                    </template>  
                </template>  
                <template v-else>
                    <template v-for="res in resource">  
                        <li :key="res.user_id" v-bind:data-id="res.user_id">
                            <a href="#">
                                <span class="caption">{{ res.user_name }}</span>
                            </a>                        
                        </li>
                    </template>  
                </template>                 
            </div>           
        </ul>       
    </nav>
</template>
<script>
import { EventBus } from "../../../event-bus.js";
import axios from "axios";
export default {
  data() {
    return {
      message: "",
      resType: "Job",
      resource: "",
      urls: {
        Job: "https://server.dev/api/jobs/list",
        Employee: "https://server.dev/api/user/list"
      }
    };
  },
  mounted() {
    // subscribe to 'sidebar-update' chanel for updating sidebar resources
    EventBus.$on("sidebar-update", resource => {
      if (["Job", "Employee"].indexOf(resource) !== -1) {
        this.resType = resource;
        this.fetchResource(this.resType);
      }
    });
  },
  updated() {
      EventBus.$emit("sidebar-updated");
  },
  methods: {
    fetchResource: function(res) {
      axios
        .get(this.urls[res], { headers: { Accept: "application/json" } })
        .then(respond => {          
          this.resource = respond.data;         
        });
    }
  }
};
</script>
<style scoped>
#resource li a {
  cursor: move !important;
}
</style>