<template>
  <div id="dp" />
</template>

<script>
import { mapGetters } from "vuex";

export default {
  props: {
    // General Settings
    view: {
      type: String,
      default: ""
    },
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
      default: () => {
        return [];
      },
      required: true
    },
    events: {
      type: Array,
      default: () => {
        return [];
      },
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
        return ["Day", "Week", "Month", "Year"].indexOf(value) > -1;
      }
    },
    weekStart: {
      type: String,
      default: "Mon",
      validator: value => {
        return (
          ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"].indexOf(value) > -1
        );
      }
    },

    startDate: {
      type: [Object, String],
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
        return ["Day", "Hour", "CellDuration"].indexOf(value) > -1;
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
        return ["Disabled", "Header", "Full"].indexOf(value) > -1;
      }
    },
    autoScroll: {
      type: String,
      default: "Disabled",
      validator: value => {
        return ["Disabled", "Drag", "Always"].indexOf(value) > -1;
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
      default: false
    }
  },

  data() {
    return {
      dayPilot: ""
    };
  },

  watch: {
    height: function(value) {
      if (this.dayPilot.height !== undefined) {
        this.dayPilot.height = value - this.timeHeaderHeight * 2;
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
    },

    view: function(value) {
      this.dayPilot.view = value;
      this.dayPilot.update();
    },

    range: function(value) {
      this.dayPilot.days = this.getDays();
      this.dayPilot.update();
    },

    startDate: function(value) {
      console.log(value);
      this.dayPilot.startDate = new window.DayPilot.Date(value);
      this.dayPilot.days = this.getDays();
      this.dayPilot.update();
    }
  },

  computed: {
    ...mapGetters({
      userId: "user/getId",
      userCan: "user/can"
    })
  },

  mounted() {
    var _this = this;

    let dp = (this.dayPilot = new window.DayPilot.Scheduler("dp"));

    dp.theme = "scheduler_green";
    //dp.width = "100%";
    dp.height = this.height - this.timeHeaderHeight * 2;
    dp.heightSpec = "Fixed";
    dp.resources = this.resource;
    dp.events.list = this.events;

    dp.durationBarVisible = true;
    dp.durationBarMode = "Duration";

    // Time Axies
    dp.weekStarts = this.getWeekStart();
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
    dp.cellWidthSpec = this.getCellWidthSpec();
    if (this.cellWidth !== "Auto") dp.cellWidth = this.cellWidth;
    dp.crosshairType = this.crosshair;
    dp.autoScroll = this.autoScroll;

    // Events
    dp.eventMarginBottom = 0;
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
    dp.rowMarginBottom = 1;
    dp.rowHeaderHideIconEnabled = false;
    dp.rowHeaderWidth = 120;
    dp.rowHeaderWidthMarginLeft = 0;
    dp.rowHeaderWidthMarginRight = 0;
    dp.rowHeaderWidthAutoFit = false;
    dp.treeEnabled = this.treeView;
    dp.view = this.view;

    // Events
    dp.timeRangeSelectedHandling = "Enabled";
    dp.eventMoveHandling = "Update";
    dp.eventResizeHandling = "Update";
    dp.eventResizedHandling = "Update";
    dp.eventDeleteHandling = "Disabled";
    dp.eventClickHandling = "Update";
    dp.eventHoverHandling = "Disabled";

    // Event Handlers
    dp.onEventMoved = this.eventMovedHandler;
    dp.onEventClicked = this.eventClickedHandler;
    dp.onEventResize = this.eventResizeHandler;
    dp.onEventResized = this.eventResizedHandler;
    dp.onEventDeleted = this.eventDeletedHandler;
    dp.onTimeRangeSelected = this.eventCreatedHandler;

    dp.onEventMove = this.eventMoveHandler;
    // dp.onEventClicke = this.eventClickHandler;
    // dp.onEventResize = this.eventResizeHandler;
    // dp.onEventDelete = this.eventDeleteHandler;
    // dp.onTimeRangeSelect = this.eventCreateHandler;

    /**
     * Header render function.
     */
    dp.onBeforeTimeHeaderRender = function(args) {
      if (args.header.level === 0) {
        if (
          args.header.start.getDayOfWeek() == _this.getWeekStart() &&
          args.header.start.getMonth() == args.header.end.getMonth()
        ) {
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

    /**
     * Resource render function.
     */
    dp.onBeforeResHeaderRender = function(args) {
      let item = dp.resources.find(item => item.id == args.resource.id);
      if (this.view === "user") {
        args.resource.html =
          `<img class='res_user_avatar' src=${item.avatar} />` +
          `<div class='res_user_name'> ${item.name} </div>`;
      }
      if (this.view === "job") {
        args.resource.html =
          `<div class='res_job_name'>${item.name}</div>` +
          `<div class='res_job_code'>${(item.id == 'sys-001' || item.id == 'sys-002') ? '' : `Code: ${item.code}`}</div>`;
      }
      args.resource.minHeight = 90;
    };

    dp.onBeforeCellRender = function(args) {
      //
    };

    /**
     * Event render function.
     */
    dp.onBeforeEventRender = function(args) {
      //console.log(args);
      let item = dp.events.list.find(item => item.id == args.data.id);

      // check permissions on move and resize
      if (item.type !== "scheduled") {
        // disable unavHours and leaves
        args.data.moveDisabled = args.data.resizeDisabled = true;
      } else {
        // check maintain own permission
        if (
          item.userId == _this.userId &&
          !_this.userCan("schedule", ["full", "update"])
        ) {
          args.data.moveDisabled = args.data.resizeDisabled = true;
        }

        // check maintain others permission
        if (item.userId != _this.userId && !_this.userCan("schedule", ["full"])) {
          args.data.moveDisabled = args.data.resizeDisabled = true;
        }
      }


      args.data.cssClass = `${item.type} ${item.status}`;
      args.data.html = `
        <div class='event-body'>
        <div class='event-body__text'>${args.data.text}</div>
        <div class='event-body__start'>${args.data.start.toString("hh:mm")}</div>
        <div class='event-body__end'>${args.data.end.toString("hh:mm")}</div>
        </div>`;
    };

    dp.init();
    this.makeDraggable();
  },

  methods: {
    getWeekStart() {
      switch (this.weekStart) {
        case "Sun":
          return 0;
        case "Mon":
          return 1;
        case "Tue":
          return 2;
        case "Wed":
          return 3;
        case "Thu":
          return 4;
        case "Fri":
          return 5;
        case "Sat":
          return 6;
        default:
          throw new Error("Invalid week start for dayPilot.");
      }
    },

    getDays() {
      switch (this.range) {
        case "Day":
          return 1;
        case "Week":
          return 7;
        case "Month":
          return this.dayPilot.startDate.daysInMonth();
        case "Year":
          return this.dayPilot.startDate.daysInYear();
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

    eventCreatedHandler(arg) {
      if (arg.resource === "sys-001" || arg.resource === "sys-002") {
        arg.allowed = false;
        this.dayPilot.clearSelection();
        return;
      }

      // check maintain own permission
      if (this.view === "user") {
        if (
          arg.resource == this.userId &&
          !this.userCan("schedule", ["full", "create"])
        ) {
          arg.allowed = false;
          this.dayPilot.clearSelection();
          return;
        }
      }

      // check maintain others permission
      if (this.view === "user") {
        if (
          arg.resource != this.userId &&
          !this.userCan("schedule", ["full"])
        ) {
          arg.allowed = false;
          this.dayPilot.clearSelection();
          return;
        }
      }

      this.$emit("range-selected", {
        resourceId: arg.resource,
        start: arg.start,
        end: arg.end.addDays(-1)
      });
      this.dayPilot.clearSelection();
    },

    eventClickedHandler(arg) {
      if (arg.e.isEvent) {
        this.$emit("event-clicked", {
          eventId: arg.e.id(),
          resourceId: arg.e.resource(),
          start: arg.e.start(),
          end: arg.e.end(),
          div: arg.div
        });
      }
    },

    eventMoveHandler(arg) {
      console.log(arg);

      // prevent drag and drop on leave and unavailable swim lanes
      if (arg.newResource === "sys-001" || arg.newResource === "sys-002") {        
        arg.preventDefault();
      }

      // check permissions on drag and drops
      if (arg.external) {
        let eventUserId = this.view == 'user' ? arg.newResource : arg.e.data.id;

        // check maintain own permission        
        if (
          eventUserId == this.userId &&
          !this.userCan("schedule", ["full", "update"])
        ) {
          arg.preventDefault();
        }

        // check maintain others permission
        if (
          eventUserId != this.userId &&
          !this.userCan("schedule", ["full"])
        ) {
          arg.preventDefault();
        }
      } 
    },

    eventMovedHandler(arg) {
      console.log(arg);

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
      if (arg.e.start() !== arg.newStart) {
        arg.newStart =
          arg.newStart.toString().substr(0, 10) +
          "T" +
          arg.e
            .start()
            .toString()
            .substr(11);
      }

      if (arg.e.end() !== arg.newEnd) {
        arg.newEnd = arg.newEnd.addDays(-1);
        arg.newEnd = new window.DayPilot.Date(
          arg.newEnd.toString().substr(0, 10) +
            "T" +
            arg.e
              .end()
              .toString()
              .substr(11)
        );
      }
    },

    eventResizedHandler(arg) {
      console.log(arg);
      this.$emit("event-resized", {
        eventId: arg.e.id(),
        newStart: arg.newStart,
        newEnd: arg.newEnd
      });
    },

    eventDeletedHandler(arg) {
      console.log("delete event...");
      this.$emit("event-deleted", {});
    }
  }
};
</script>

<style scoped>
</style>