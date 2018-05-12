<template>
  <div>
    <div class="inline-form">
      <div class="form-group">        
          <label class="label">View: </label>
          <select class="select" style="display:inline !important;" v-model="view" v-on:change="changeType">
            <option value="Job" selected>Job</option>
            <option value="Employee">Employee</option>
          </select>        
      </div>
      <div class="form-group">
        <label class="label">Scale: </label>
        <select class="select" style="display:inline !important;" v-model="scale" v-on:change="changeScale">
          <option value="Minute">Minute</option>
          <option value="Hour">Hour</option>
          <option value="Day">Day</option>
          <option value="Week">Week</option>
          <option value="Month">Month</option>
          <option value="Year">Year</option>
        </select>
      </div>     
      <div class="form-group">
        <label class="label">From: </label>
        <input type="date" v-model="dateFrom" v-on:change="changeDate" />
      </div>
    </div><!-- Controll end -->

    <div id="dp"></div>
  </div>
</template>
<script>
import { EventBus } from "../../../event-bus.js";
import axios from "axios";
export default {
  props: {
    dpResources: {
      type: String,
      default: "Employee",
      validator: function(value) {
        return ["Employee", "Job"].indexOf(value) !== -1;
      }
    },
    dpType: {
      type: String,
      default: "Scheduler",
      validator: function(value) {
        return ["Employee", "Job"].indexOf(value) !== -1;
      }
    },
    dpView: {
      type: String,
      default: "Day",
      validator: function(value) {
        return (
          ["Minute", "Hour", "Day", "Week", "Month", "Year"].indexOf(value) !==
          -1
        );
      }
    },
    dpFrom: {
      type: String,
      default: Date.now()
    },
    dpDays: {
      type: Number,
      default: 30
    }
  },
  data() {
    return {
      dayPilot: "",
      scale: "",
      resourcetype: "Job",
      eventType: "Employee",
      dateFrom: "",
      view: "",
      scriptUrl: "https://server.dev/js/daypilot-all.min.js",
      styleUrl: "https://server.dev/css/calendar_white.css",
      urls: {
        Job: "https://server.dev/api/jobs/list",
        Employee: "https://server.dev/api/user/list"
      }
    };
  },
  mounted() {
    // append daypilot style to head
    let dpStyleLoaded = new Promise(resolve => {
      let dpStyle = document.createElement("link");
      dpStyle.type = "text/css";
      dpStyle.href = this.styleUrl;
      dpStyle.rel = "stylesheet";
      dpStyle.onload = () => {
        resolve();
      };
      document.head.appendChild(dpStyle);
    });

    // append daypilot script to body
    let dpScriptLoaded = new Promise(resolve => {
      let dpScript = document.createElement("script");
      dpScript.type = "text/javascript";
      dpScript.src = this.scriptUrl;
      dpScript.async = "true";
      dpScript.defer = "true";
      dpScript.onload = () => {
        resolve();
      };
      document.body.appendChild(dpScript);
    });

    dpScriptLoaded.then(() => {
      this.dayPilot = new window.DayPilot.Scheduler("dp");
      this.dayPilot.width = "100%";      
      this.dayPilot.startDate = "2018-04-30T14:30:00";
      this.dayPilot.days = 30;     
      this.dayPilot.heightSpec = "Fixed";
      this.dayPilot.height = 400;      

      EventBus.$on('sidebar-updated', this.makeDragable);
      EventBus.$emit("sidebar-update", this.eventType);
      this.fetchResorce().then(() => {
        this.dayPilot.init();           
      });
    });
  },
  methods: {
    fetchResorce: function() {
      return new Promise(resolve => {
        axios
          .get(this.urls[this.resourcetype], {
            headers: { Accept: "application/json" }
          })
          .then(respond => {           
            let data = Object.values(respond.data).map((item, index) => {
              if (this.resourcetype === "Job") return { id: item.id, name: item.title };
              else return { id: item.user_id, name: item.user_name };
            });
            this.dayPilot.resources = data;
            resolve();
          });
      });
    },

    changeType: function(event) {
      this.resourcetype = event.target.value;
      this.eventType = event.target.value === "Job" ? "Employee" : "Job";
      EventBus.$emit("sidebar-update", this.eventType);      
      this.fetchResorce().then(() => {        
        this.dayPilot.update();               
      });      
    },
    changeScale: function(event) {
      event.preventDefault();
      this.dayPilot.scale = event.target.value;
      this.dayPilot.update();
    },
    changeDate: function(event) {
      event.preventDefault();
      this.dayPilot.startDate = event.target.value + "T00:00:00";
      this.dayPilot.update();
    },
    makeDragable() {      
      var parent = document.getElementById("resource");      
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
      this.dayPilot.update();  
    }
  }
};
</script>
<style scoped>
#dp {
  background-color: white !important;
}

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