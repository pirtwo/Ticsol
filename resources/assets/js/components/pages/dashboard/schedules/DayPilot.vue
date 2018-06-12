<template>
  <div id="dp"></div>  
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      default: "scheduler",
    },
    width: {
      type: String,
      default: '100%',
    },
    height: {
      type: Number,
      default: 400,
    },
    startDate: {
      type: Number,
      default: Date.now(),
    },
    days: {
      type: Number,
      default: 30
    },
    resource: {
      type: Array,
      default: null,
    },
    events: {
      type: Array,
      default: null,
    },
    showNonBusiness: {
      type: Boolean,
      default: true,
    },
    businessBeginHour: {
      type: Number,
      default: 6,
    },
    businessEndHour: {
      type: Number,
      default: 18,
    },
    scale: {
      type: String,
      default: "Hour",
    },
    cellDuration: {
      type: Number,
      default: 15,
    }
  },

  data() {
    return {
      dayPilot: "",      
    }
  },

  created() {

    console.log(this.type);
    console.log(this.resource);
    console.log(this.events);
    //console.log(this.resource);


    // append daypilot style to head
    let dpStyleLoaded = new Promise(resolve => {
      if (window.DayPilot === undefined) {
        let dpStyle = document.createElement("link");
        dpStyle.type = "text/css";
        dpStyle.href = "https://server.dev/css/calendar_white.css";
        dpStyle.rel = "stylesheet";
        dpStyle.onload = () => {
          resolve();
        };
        document.head.appendChild(dpStyle);
      } else {
        resolve();
      }
    });

    // append daypilot script to body
    let dpScriptLoaded = new Promise(resolve => {
      if (window.DayPilot === undefined) {
        console.log("not loaded...");
        let dpScript = document.createElement("script");
        dpScript.type = "text/javascript";
        dpScript.src = "https://server.dev/js/daypilot-all.min.js";
        dpScript.async = "true";
        dpScript.defer = "true";
        dpScript.onload = () => {
          resolve();
        };
        document.body.appendChild(dpScript);
      } else {
        console.log("loaded...");
        resolve();
      }
    });

    dpScriptLoaded.then(() => {
      console.log("resolved...");
      if(this.type == 'scheduler'){
        console.log(this.resource);
        this.dayPilot = new window.DayPilot.Scheduler("dp");
        this.dayPilot.width = '100%';
        this.dayPilot.height = 400;
        this.dayPilot.startDate = this.formatDate(this.startDate) + "T14:30:00";
        this.dayPilot.days = 30;
        this.dayPilot.heightSpec = "Fixed"; 
        this.dayPilot.resources = this.resource; 
        this.dayPilot.events.list = this.events; 
        this.dayPilot.init();
        //this.makeDraggable();      
      }
      // if(type === 'calendar'){

      // }     
            
    });
  },

  watch: {
    resource: function() {
      this.makeDragable();
    },
    events: function(){

    }
  },

  methods: {
    
    formatDate(date) {
      var d = new Date(date),
        month = "" + (d.getMonth() + 1),
        day = "" + d.getDate(),
        year = d.getFullYear();
      if (month.length < 2) month = "0" + month;
      if (day.length < 2) day = "0" + day;
      return [year, month, day].join("-");
    },

    makeDraggable() {
      var parent = document.getElementById("dp-draggable");
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
      // this.dayPilot.events.list = [];
      // this.dayPilot.update();
    }
  }
};
</script>

<style scoped>
#dp {
  background-color: white !important;
}
</style>