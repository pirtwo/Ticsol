<template>
  <div>
    <div class="inline-form">
      <div class="form-group">        
          <label class="label">View: </label>
          <select class="select" style="display:inline !important;" v-model="view" v-on:change="changeScale">
            <option value="Job">Job</option>
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
export default {
  props:{
    dpResources: {
      type: String,
      default: 'Employee',
      validator:function(value){
        return ['Employee', 'Job'].indexOf(value) !== -1;
      }
    },
    dpType: {
      type: String,
      default: 'Scheduler',
      validator:function(value){
        return ['Employee', 'Job'].indexOf(value) !== -1;
      }
    },
    dpView:{
      type: String,
      default: 'Day',
      validator:function(value){
        return ['Minute', 'Hour', 'Day', 'Week', 'Month', 'Year'].indexOf(value) !== -1;
      }
    },
    dpFrom:{
      type:String,
      default: Date.now()
    },
    dpDays:{
      type: Number,
      default: 30
    }

  },
  data() {
    return {
      dayPilot: "",
      scale: "",
      dateFrom: "",
      view: "",
      scriptUrl: "https://server.dev/js/daypilot-all.min.js",
      styleUrl: "https://server.dev/css/calendar_white.css"
    };
  },
  created() {
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
      //this.dayPilot.timeHeaders = [{ groupBy: "Week" }, { groupBy: "Day" }];
      this.dayPilot.startDate = "2018-04-30T14:30:00";      
      this.dayPilot.days = 30;
      //dayPilot.cssClassPrefix = "calendar_white";
      //dayPilot.viewType = "Week";
      //dayPilot.heightSpec = "Parent100Pct";
      //dayPilot.height = 400;

      this.dayPilot.resources = [
        { name: "Room A", id: "A" },
        { name: "Room B", id: "B" },
        { name: "Room C", id: "C" },
        { name: "Room D", id: "D" },
        { name: "Room E", id: "E" },
        { name: "Room F", id: "F" },
        { name: "Room G", id: "G" },
        { name: "Room H", id: "H" },
        { name: "Room I", id: "I" },
        { name: "Room J", id: "J" },
        { name: "Room K", id: "K" },
        { name: "Room L", id: "L" }
      ];

      this.dayPilot.events.list = [
        {
          id: "B",
          text: "Calendar Event 5",
          start: "2018-05-05T10:30:00",
          end: "2018-05-05T16:30:00",
          resource: "B"
        },
        {
          id: "A",
          text: "Calendar Event 6",
          start: "2018-05-05T09:00:00",
          end: "2018-05-05T14:30:00",
          resource: "A"
        },
        {
          id: "C",
          text: "Calendar Event 7",
          start: "2018-05-05T12:00:00",
          end: "2018-05-08T16:00:00",
          resource: "C"
        }
      ];
      this.dayPilot.init();
    });
  },
  methods: {
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
    fetchResources(){

    },
    fetchEvents(){

    }
  }
};
</script>
<style scoped>
  input[type="date"]{
    font-size: 10px;
    padding: 5px;
    height: 25px;
    border: 1px #d9d9d9 solid;
    border-radius: 3px;
  } 

  .select{
    font-size: 10px !important;
    padding: 5px !important;
    height: 25px !important;
    min-width: 80px !important;
    border-radius: 3px !important;
  }

  .inline-form{
    vertical-align: bottom !important;
  }

  .form-group{
    margin-top: 1rem !important;
    margin-bottom: 1rem !important;
    padding-left: 7px;
  }

  .label{
    font-size: 15px !important;
    vertical-align: bottom !important;
  }
</style>