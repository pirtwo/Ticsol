<template>
  <div id="dp" />
</template>

<script>
export default {
  name: "DPScheduler",

  props: {    
    config: {
      type: Object,
      default: () => {
        return {};
      }
    },

    events: {
      type: Array,
      required: true,
      default: () => {
        return [];
      }
    },

    resource: {
      type: Array,
      required: true,
      default: () => {
        return [];
      }
    },

    startDate: {
      type: [Object, String],
      default: null
    },

    height: {
      type: Number,
      default: 400
    },

    message: {
      type: String,
      default: ""
    },

    dragAndDrop: {
      type: Boolean,
      default: false
    },

    dragAreaId: {
      type: String,
      default: null
    }
  },

  data() {
    return {
      dayPilot: ""
    };
  },

  watch: {
    events: function(value) {
      if (this.dayPilot.events) {
        this.dayPilot.events.list = value;
        this.dayPilot.update();
      }
    },

    resource: function(value) {
      if (this.dayPilot.resources) {
        this.dayPilot.resources = value;
        this.dayPilot.update();
        if (this.dragAndDrop) this.makeDraggable();
      }
    },

    startDate: function(value) {
      console.log(value);
      this.dayPilot.startDate = new window.DayPilot.Date(value);
      this.dayPilot.update();
    },

    height: function(value) {
      if (this.dayPilot.height) {
        this.dayPilot.height = value;
        this.dayPilot.update();
      }
    },

    message: function(value) {
      this.dayPilot.message(value);
    }
  },

  mounted() {
    var _this = this;

    let dp = (this.dayPilot = new window.DayPilot.Scheduler("dp", this.config));

    dp.height = this.height;
    dp.resources = this.resource;
    dp.events.list = this.events;

    // Event Handlers
    dp.onEventMove = function(args) {
      _this.$emit("event-move", args);
    };

    dp.onEventClick = function(args) {
      _this.$emit("event-click", args);
    };

    dp.onEventResize = function(args) {
      _this.$emit("event-resize", args);
    };

    dp.onEventDelete = function(args) {
      _this.$emit("event-delete", args);
    };

    dp.onTimeRangeSelect = function(args) {
      _this.$emit("timerange-select", args);
    };

    dp.onEventMoved = function(args) {
      _this.$emit("event-moved", args);
    };

    dp.onEventClicked = function(args) {
      _this.$emit("event-clicked", args);
    };

    dp.onEventResized = function(args) {
      _this.$emit("event-resized", args);
    };

    dp.onEventDeleted = function(args) {
      _this.$emit("event-deleted", args);
    };

    dp.onTimeRangeSelected = function(args) {
      _this.$emit("timerange-selected", args);
    };

    // Render functions
    dp.onBeforeCellRender = function(args) {
      _this.$emit("cell-render", args);
    };

    dp.onBeforeEventRender = function(args) {
      _this.$emit("event-render", args);
    };

    dp.onBeforeResHeaderRender = function(args) {
      _this.$emit("resheader-render", args);
    };

    dp.onBeforeTimeHeaderRender = function(args) {
      _this.$emit("timeheader-render", args);
    }

    dp.init();
    if (this.dragAndDrop) this.makeDraggable();
  },

  methods: {
    makeDraggable() {
      var parent = document.getElementById(this.dragAreaId);
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
    }
  }
};
</script>

<style scoped>
</style>