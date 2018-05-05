<template>
  <div>
    <div class="inline-form">
       <select class="select" v-on:change="changeScale">
        <option value="Minute">Minute</option>
        <option value="Hour">Hour</option>
        <option value="Day">Day</option>
        <option value="Week">Week</option>
        <option value="Month">Month</option>
        <option value="Year">Year</option>
      </select>
      <input type="date" v-on:change="changeDate" />
    </div>   
    <div id="dp"></div>
  </div>  
</template>
<script>
export default {
  data() {
    return {
      dayPilot: "",
      dpUrl: "https://server.dev/js/daypilot-all.min.js",
      styleUrl: "https://server.dev/css/calendar_white.css"
    };
  },
  created() {
    // append daypilot style to head
    let dpStyle = document.createElement("link");
    dpStyle.type = "text/css";
    dpStyle.href = this.styleUrl;
    dpStyle.rel = "stylesheet";
    document.head.appendChild(dpStyle);

    // append daypilot script to body
    let dpLoaded = new Promise(resolve => {
      let dp = document.createElement("script");
      dp.type = "text/javascript";
      dp.src = this.dpUrl;
      dp.async = "true";
      dp.defer = "true";
      dp.onload = () => {
        resolve();
      };
      document.body.appendChild(dp);
      console.log("added...");
    });

    dpLoaded.then(() => {
      console.log("loaded...");
      this.dayPilot = new window.DayPilot.Scheduler("dp");
      this.dayPilot.width = "100%";
      this.dayPilot.timeHeaders = [{ groupBy: "Week" }, { groupBy: "Day"}];
      this.dayPilot.startDate = "2018-04-30T14:30:00";
      console.log(window.DayPilot.Date.today().firstDayOfMonth());
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
      console.log(event.target.value);
      this.dayPilot.scale = event.target.value;      
      this.dayPilot.update();
    },
    changeDate: function(event) {
      event.preventDefault();
      console.log(event.target.value);
      this.dayPilot.startDate = event.target.value + "T00:00:00";
      this.dayPilot.update();
    }
  }
};
</script>
<style scoped>

</style>
