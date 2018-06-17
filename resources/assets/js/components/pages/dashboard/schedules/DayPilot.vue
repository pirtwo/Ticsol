<template>
  <div id="dp"></div>  
</template>

<script>
export default {
  props: {
    // General Settings
    width: {
      type: [String, Number],
      default: "100%"
    },
    height: {
      type: Number,
      default: 400
    },
    resource: {
      type: Array,
      required: true
    },
    events: {
      type: Array,
      required: true
    },

    // Time Axies
    range: {
      type: String,
      required: true,
      validator: value => {
        return ["day", "week", "month", "year"].indexOf(value) !== -1;
      }
    },
    startDate: {
      type: Number,
      default: null
    },
    days: {
      type: Number,
      default: null
    },
    scale: {
      type: String,
      required: true,
      validator: value => {
        return ["day", "hour", "CellDuration"].indexOf(value) !== -1;
      }
    },
    cellDuration: {
      type: Number,
      default: 15
    },
    timeHeader: {
      type: Array,
      default: null
    },
    timeHeaderFormat: {
      type: String,
      default: "days/hours/mins",
      validator: value => {
        return (
          [
            "years/months/days",
            "months/days",
            "months/days/halfDays",
            "weeks/days",
            "days/hours",
            "days/hours/mins"
          ].indexOf(value) !== -1
        );
      }
    },
    showNonBusiness: {
      type: Boolean,
      default: false
    },
    businessBeginHour: {
      type: Number,
      default: 6
    },
    businessEndHour: {
      type: Number,
      default: 18
    },
    businessWeekends: {
      type: Boolean,
      default: true
    },

    // Grid
    cellWidth: {
      type: [Number, String],
      default: "auto"
    },
    crosshair: {
      type: String,
      default: "disabled",
      validator: value => {
        return ["disabled", "header", "full"].indexOf(value) !== -1;
      }
    },
    autoScroll: {
      type: String,
      default: "disabled",
      validator: value => {
        return ["disabled", "drag", "always"].indexOf(value) !== -1;
      }
    },

    // Events
    eventHeight: {
      type: Number,
      default: 30
    },
    floatingLable: {
      type: Boolean,
      default: false
    },
    movingLable: {
      type: Boolean,
      default: false
    },
    resizingLable: {
      type: Boolean,
      default: false
    },
    creatingLable: {
      type: Boolean,
      default: false
    },

    // Overlaps
    allowEventOverlap: {
      type: Boolean,
      default: true
    },
    groupOverlapedEvents: {
      type: Boolean,
      default: false
    },
    eventStackLineHeight: {
      type: Number,
      default: 100,
      validator: value => {
        return value >= 1 && value <= 100;
      }
    },

    // Rows
    treeView: {
      type: Boolean,
      default: true
    }
  },

  data() {
    return {
      dayPilot: ""
    };
  },

  created() {
    // append daypilot style to head
    let dpStyleLoaded = new Promise(resolve => {
      if (window.DayPilot === undefined) {
        let dpStyle = document.createElement("link");
        dpStyle.type = "text/css";
        dpStyle.href = "./css/ts_scheduler_green.css";
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
        let dpScript = document.createElement("script");
        dpScript.type = "text/javascript";
        dpScript.src = "./js/daypilot-all.min.js";
        dpScript.async = "true";
        dpScript.defer = "true";
        dpScript.onload = () => {
          resolve();
        };
        document.body.appendChild(dpScript);
      } else {
        resolve();
      }
    });

    dpScriptLoaded.then(() => {
      let dp = (this.dayPilot = new window.DayPilot.Scheduler("dp"));      
      dp.theme = "scheduler_green";
      dp.width = "100%";
      dp.height = 400;
      dp.heightSpec = "Fixed";
      dp.resources = this.resource;
      dp.events.list = this.events;

      // Time Axies
      dp.days = this.days || this.getDays;
      dp.startDate = this.startDate || this.getStartDate;
      dp.timeHeaders = this.timeHeader || this.getTimeHeader;
      dp.scale = this.scale;
      dp.cellDuration = this.cellDuration;
      dp.showNonBusiness = this.showNonBusiness;
      dp.businessBeginsHour = this.businessBeginHour;
      dp.businessEndsHour = this.businessEndHour;
      dp.businessWeekends = this.businessWeekends;

      // Grid
      dp.cellWidthSpec = this.getCellWidthSpec;
      if (this.cellWidthSpec != "auto") dp.cellWidth = this.cellWidth;
      dp.crosshairType = this.crosshair;
      dp.autoScroll = this.autoScroll;

      // Events
      dp.eventHeight = this.eventHeight;
      dp.floatingEvents = this.floatingLable;
      dp.eventMovingStartEndEnabled = this.movingLable;
      dp.eventResizingStartEndEnabled = this.resizingLable;
      dp.timeRangeSelectingStartEndEnabled = this.creatingLable;

      // Overlaps
      dp.allowEventOverlap = this.allowEventOverlap;
      dp.groupConcurrentEvents = this.groupOverlapedEvents;
      dp.eventStackingLineHeight = this.eventStackLineHeight;

      // Rows
      dp.treeEnabled =this.treeView;

      // Events
      dp.timeRangeSelectedHandling = "Enabled";
      dp.eventMoveHandling = "Update";
      dp.eventResizeHandling = "Update";
      dp.eventDeleteHandling = "Update";
      dp.eventClickHandling = "Enabled";
      dp.eventHoverHandling = "Bubble";

      // Event Handlers      
      dp.onEventMoved = this.eventMoveHandler;    
      dp.onEventClicked = this.eventClickHandler;  
      dp.onEventResized = this.eventResizeHandler;
      dp.onEventDeleted = this.eventDeleteHandler;
      dp.onTimeRangeSelected = this.eventCreateHandler;

      dp.contextMenu = new window.DayPilot.Menu({
        items: [
          {
            text: "Delete",
            onClick: function(args) {
              var dp = args.source.calendar;
              dp.events.remove(args.source);
            }
          }
        ]
      });

      dp.bubble = new window.DayPilot.Bubble({
        onLoad: function(args) {
          // if event object doesn't specify "bubbleHtml" property
          // this onLoad handler will be called to provide the bubble HTML
          args.html = "Event details";
        }
      });

      dp.init();
      this.makeDraggable();
    });
  },

  watch: {
    resource: function() {
      this.makeDraggable();
    },
    events: function() {}
  },

  computed: {
    getDays: () => {
      switch (this.range) {
        case "day":
          return 1;
        case "week":
          return 7;
        case "month":
          return window.DayPilot.Date.today().daysInMonth();
        case "year":
          return window.DayPilot.Date.today().daysInYear();
        default:
          return 1;
      }
    },

    getStartDate: () => {
      switch (this.range) {
        case "day":
          return window.DayPilot.Date.today();
        case "week":
          return window.DayPilot.Date.today().firstDayOfWeek();
        case "month":
          return window.DayPilot.Date.today().firstDayOfMonth();
        case "year":
          return window.DayPilot.Date.today().firstDayOfYear();
        default:
          return window.DayPilot.Date.today();
      }
    },

    getTimeHeader: () => {
      switch (this.timeHeaderFormat) {
        case "years/months/days":
          return [
            { groupBy: "Year" },
            { groupBy: "Month" },
            { groupBy: "Day", format: "d" }
          ];
        case "months/days":
          return [{ groupBy: "Month" }, { groupBy: "Day", format: "d" }];
        case "months/days/halfDays":
          return [
            { groupBy: "Month" },
            { groupBy: "Day", format: "d" },
            { groupBy: "Cell", format: "tt" }
          ];
        case "weeks/days":
          return [{ groupBy: "Week" }, { groupBy: "Day", format: "d" }];
        case "days/hours":
          return [{ groupBy: "Day" }, { groupBy: "Hour" }];
        case "days/hours/mins":
          return [
            { groupBy: "Day", format: "dddd, d MMMM yyyy" },
            { groupBy: "Hour" },
            { groupBy: "Cell", format: "mm" }
          ];
        default:
          return [
            { groupBy: "Day", format: "dddd, d MMMM yyyy" },
            { groupBy: "Hour" },
            { groupBy: "Cell", format: "mm" }
          ];
      }
    },

    getCellWidthSpec: () => {
      return this.cellWidth === "auto" ? "Auto" : "Fixed";
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
    },

    eventCreateHandler(arg) {
      console.log('range selected...');
    },

    eventClickHandler(arg) {
      console.log('click event...');
    },   

    eventMoveHandler(arg) {
      console.log('move event...');
    },

    eventHoverHandler(arg) {
      console.log('hover event...');
    },

    eventResizeHandler(arg) {
      console.log('resize event...');
    },

    eventDeleteHandler(arg) {
      console.log('delete event...');
    }
  }
};
</script>

<style scoped>
#dp {
  background-color: white !important;
}
</style>