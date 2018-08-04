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
      default: [],
      required: true
    },
    events: {
      type: Array,
      default: [],
      required: true
    },
    message: {
      type: String,
      default: ""
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
    timeHeaderAutoFit: {
      type: Boolean,
      default: true
    },
    timeHeaderHeight: {
      type: Number,
      default: 30
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

      //dp.theme = "scheduler_green";
      dp.width = "100%";
      dp.height = this.height - this.timeHeaderHeight * 2 - 4;
      dp.heightSpec = "Fixed";
      dp.resources = this.resource;
      dp.events.list = this.events;

      dp.durationBarVisible = true;
      dp.durationBarMode = "PercentComplete";

      // Time Axies
      dp.days = this.days || this.getDays();
      dp.startDate = this.startDate || this.getStartDate();
      dp.timeHeaders = this.timeHeader || this.getTimeHeader();
      dp.headerHeightAutoFit = this.timeHeaderAutoFit;
      if (!this.timeHeaderAutoFit) dp.headerHeight = this.timeHeaderHeight;
      dp.scale = this.scale;
      if (this.scale === "CellDuration") dp.cellDuration = this.cellDuration;
      dp.showNonBusiness = this.showNonBusiness;
      dp.businessBeginsHour = this.businessBeginHour;
      dp.businessEndsHour = this.businessEndHour;
      dp.businessWeekends = this.businessWeekends;

      // Grid
      dp.cellWidthSpec = this.getCellWidthSpec;
      if (this.cellWidth !== "Auto") dp.cellWidth = this.cellWidth;
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
      dp.eventDeleteHandling = "Disabled";
      dp.eventClickHandling = "Update";
      dp.eventHoverHandling = "Disabled";

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
          if (args.header.start.getDayOfWeek() == 0) {
            args.header.html =
              "<span class='header_weekDay_weekRange'>" +
              args.header.start.getDay() +
              "-" +
              args.header.start.addDays(6).toString("d") +
              " " +
              args.header.start.toString("MMM yyyy") +
              "</span>  <span class='header_weekDay_weekNumber'>w" +
              args.header.start.weekNumber() +
              "</span>";
          } else {
            args.header.html = "";
          }
        }

        if (args.header.level === 1) {
          args.header.html =
            "<span class='header_weekDay_dayTxt'>" +
            args.header.start.toString("ddd") +
            "</span><br/><span class='header_weekDay_dayNumber'>" +
            args.header.start.getDay() +
            "</span>";
        }
      };

      dp.onBeforeResHeaderRender = function(args) {
        let avatar = "",
          name = "",
          id = args.resource.id - 1;
        if (dp.resources[id] !== undefined) {
          name = dp.resources[id].name;
          avatar = dp.resources[id].avatar;
        }
        args.resource.html =
          `<img class='res_user_avatar' src=${avatar} />` +
          `<span class='res_user_name'> ${name} </span>`;

        args.resource.minHeight = 70;
      };

      dp.onBeforeEventRender = function(args) {        
        args.data.html =
          `<span class='event_title'>${args.data.text}</span><br/>` +
          `<span>Progress: %${args.data.complete}</span>`;
        args.data.cssClass = "has-popover";
      };

      dp.init();
      this.makeDraggable();
    });
  },

  watch: {
    height: function(value) {
      if (this.dayPilot.height !== undefined) {
        this.dayPilot.height = value - this.timeHeaderHeight * 2 - 4;
        this.dayPilot.update();
      }
    },

    resource: function(value) {
      if (this.dayPilot.resources !== undefined) {
        this.dayPilot.resources = value;
        this.dayPilot.update();
        this.makeDraggable();
      }
    },

    events: function(value) {
      if (this.dayPilot.events !== undefined) {
        this.dayPilot.events.list = value;
        this.dayPilot.update();
      }
    },

    message: function(value) {
      this.dayPilot.message(value);
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
      console.log(arg);
      if (arg.e.isEvent) {
        this.$emit("event-clicked", {
          eventId: arg.e.id(),
          div: arg.div
        });
      }
    },

    eventMoveHandler(arg) {
      //console.log(arg);
      if (arg.external) {
        this.$emit("event-dragged", {
          eventId: arg.e.id(),
          resourceId: arg.newResource,
          start: arg.newStart,
          end: arg.newEnd
        });
        this.dayPilot.events.remove(arg.e);
      } else {
        this.$emit("event-moved", {
          eventId: arg.e.id(),
          resourceId: arg.newResource,
          newStart: arg.newStart,
          newEnd: arg.newEnd
        });
      }
      arg.preventDefault();
    },

    eventHoverHandler(arg) {
      console.log(arg);
      this.$emit("event-hoverd", {
        eventId: arg.e.id()
      });
    },

    eventResizeHandler(arg) {
      console.log(arg);
      this.$emit("event-resized", {
        eventId: arg.e.id(),
        newStart: arg.newStart,
        newEnd: arg.newEnd
      });
    },

    eventDeleteHandler(arg) {
      console.log("delete event...");
      this.$emit("event-deleted", {});
    }
  }
};
</script>

<style scoped>
</style>