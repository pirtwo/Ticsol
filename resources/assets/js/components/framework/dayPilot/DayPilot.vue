<template>
  <div id="dp"></div>
</template>
<script>
export default {
  data() {
    return {
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
      var dayPilot = new window.DayPilot.Calendar("dp");
      dayPilot.cssClassPrefix = "calendar_white";
      dayPilot.viewType = "Week";
      dayPilot.events.list = [
        {
          id: "5",
          text: "Calendar Event 5",
          start: "2018-05-01T10:30:00",
          end: "2018-05-01T16:30:00"
        },
        {
          id: "6",
          text: "Calendar Event 6",
          start: "2018-05-24T09:00:00",
          end: "2018-05-24T14:30:00"
        },
        {
          id: "7",
          text: "Calendar Event 7",
          start: "2018-05-26T12:00:00",
          end: "2018-05-27T16:00:00"
        }
      ];
      dayPilot.init();
    });
  }
};
</script>
<style scoped>

</style>
