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
        return ["Day", "Week", "Month", "Year"].indexOf(value) !== -1;
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
        return ["Day", "Hour", "CellDuration"].indexOf(value) !== -1;
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
      default: "Days/Hours/Mins",
      validator: value => {
        return (
          [
            "Years/Months/Days",
            "Months/Days",
            "Months/Days/HalfDays",
            "Weeks/Days",
            "Days/Hours",
            "Days/Hours/Mins"
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
      default: "Auto"
    },
    crosshair: {
      type: String,
      default: "Disabled",
      validator: value => {
        return ["Disabled", "Header", "Full"].indexOf(value) !== -1;
      }
    },
    autoScroll: {
      type: String,
      default: "Disabled",
      validator: value => {
        return ["Disabled", "Drag", "Always"].indexOf(value) !== -1;
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
      dp.height = this.height;
      dp.heightSpec = "Fixed";
      dp.resources = this.resource;
      dp.events.list = this.events;

      // Time Axies
      dp.days = this.days || this.getDays();
      dp.startDate = this.startDate || this.getStartDate();
      dp.timeHeaders = this.timeHeader || this.getTimeHeader();
      dp.scale = this.scale;
      if (this.scale === "CellDuration") dp.cellDuration = this.cellDuration;
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
      dp.treeEnabled = this.treeView;

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
          //console.log(args);
          args.html = "Event details";
        }
      });

      dp.onBeforeTimeHeaderRender = function(args) {
        if (args.header.level === 0) {
          //console.log(args.header.start.getDayOfWeek());
          //console.log(args.header.end.getDayOfWeek());
          if (args.header.start.getDayOfWeek() == 0) {
            args.header.html =
              args.header.start.getDay() +
              " - " +
              args.header.start.addDays(6).toString("d") +
              " " +
              args.header.start.toString("MMM yyyy") +
              "  w" +
              args.header.start.weekNumber();
          } else {
            args.header.html = "";
          }
        }
      };

      dp.init();
      this.makeDraggable();
    });
  },

  watch: {
    resource: function(value) {
      this.dayPilot.resources = value;
      this.dayPilot.update();
      this.makeDraggable();
    },
    events: function(value) {
      this.dayPilot.events.list = value;
      this.dayPilot.update();
    }
  },

  methods: {
    getDays() {
      switch (this.range) {
        case "Day":
          return 1;
        case "Week":
          return 7;
        case "Month":
          return window.DayPilot.Date.today().daysInMonth();
        case "Year":
          return window.DayPilot.Date.today().daysInYear();
        default:
          throw new Error("Invalid range for dayPilot.");
      }
    },

    getStartDate() {
      switch (this.range) {
        case "Day":
          return window.DayPilot.Date.today();
        case "Week":
          return window.DayPilot.Date.today().firstDayOfWeek();
        case "Month":
          return window.DayPilot.Date.today().firstDayOfMonth();
        case "Year":
          return window.DayPilot.Date.today().firstDayOfYear();
        default:
          throw new Error("Invalid range for dayPilot.");
      }
    },

    getTimeHeader() {
      switch (this.timeHeaderFormat) {
        case "Years/Months/Days":
          return [
            { groupBy: "Year" },
            { groupBy: "Month" },
            { groupBy: "Day", format: "d" }
          ];
        case "Months/Days":
          return [{ groupBy: "Month" }, { groupBy: "Day", format: "d" }];
        case "Months/Days/HalfDays":
          return [
            { groupBy: "Month" },
            { groupBy: "Day", format: "d" },
            { groupBy: "Cell", format: "tt" }
          ];
        case "Weeks/Days":
          return [{ groupBy: "Week" }, { groupBy: "Day", format: "ddd d" }];
        case "Days/Hours":
          return [{ groupBy: "Day" }, { groupBy: "Hour" }];
        case "Days/Hours/Mins":
          return [
            { groupBy: "Day", format: "dddd, d MMMM yyyy" },
            { groupBy: "Hour" },
            { groupBy: "Cell", format: "mm" }
          ];
        default:
          throw new Error("Invalid time header format for dayPilot.");
      }
    },

    getCellWidthSpec() {
      return this.cellWidth === "Auto" ? "Auto" : "Fixed";
    },

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
      //console.log(arg);
      this.$emit("range-selected", {
        resourceId: arg.resource,
        start: arg.start,
        end: arg.end
      });
    },

    eventClickHandler(arg) {
      //console.log(arg);
      this.$emit("eventClicked", {
        eventId: arg.e.id()
      });
    },

    eventMoveHandler(arg) {
      //console.log(arg);
      if (arg.external) {
        this.$emit("eventdraged", {
          eventId: arg.e.id(),
          resourceId: arg.newResource,
          start: arg.newStart,
          end: arg.newEnd
        });
        this.dayPilot.events.remove(arg.e);
      } else {
        this.$emit("eventMoved", {
          eventId: arg.e.id(),
          resourceId: arg.newResource,
          newStart: arg.newStart,
          newEnd: arg.newEnd
        });
      }
      arg.preventDefault();
    },

    eventHoverHandler(arg) {
      //console.log(arg);
      this.$emit("eventHoverd", {
        eventId: arg.e.id()
      });
    },

    eventResizeHandler(arg) {
      console.log(arg);
      this.$emit("eventResized", {});
    },

    eventDeleteHandler(arg) {
      //console.log("delete event...");
      this.$emit("eventDeleted", {});
    }
  }
};
</script>

<style scoped>
#dp {
  background-color: white !important;
}
</style>