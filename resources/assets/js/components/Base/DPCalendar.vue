<template>
  <div id="dpCal" />
</template>

<script>
export default {
  name: "DPCalendar",

  props: {
    startDate: {
      type: Object,
      default: () => {
        return {};
      }
    },
    events: {
      type: Array,
      default: () => {
        return [];
      }
    },
    disabled: {
      type: Boolean,
      default: false
    },
    message: {
      type: Object,
      default: () => {
        return { msg: "", delay: 0 };
      }
    }
  },

  data() {
    return {
      dpCal: {}
    };
  },

  watch: {
    startDate: function(val) {
      this.dpCal.startDate = val;
      this.dpCal.update();
    },

    events: function(val) {
      this.dpCal.events.list = val;
      this.dpCal.update();
    },

    disabled: function(val) {
      console.log(val);
      if (val) {
        this.dpCal.eventClickHandling = "Disabled";
        this.dpCal.eventMoveHandling = "Disabled";
      } else {
        this.dpCal.eventClickHandling = "Enabled";
        this.dpCal.eventMoveHandling = "Enabled";
      }
      this.dpCal.update();
    },

    message: function(val) {
      this.dpCal.message(val.msg, val.delay);
    }
  },

  mounted() {
    this.dpCal = new DayPilot.Month("dpCal");
    this.dpCal.locale = "en-us";
    this.dpCal.viewType = "week";
    this.dpCal.startDate = this.startDate;
    this.dpCal.weeks = 1;
    this.dpCal.timeRangeSelectedHandling = true;
    this.dpCal.events.list = [];

    this.dpCal.timeRangeSelectedHandling = "Disabled";

    this.dpCal.onEventMoved = this.eventMoveHandler;
    this.dpCal.onEventClicked = this.eventClickHandler;
    this.dpCal.onEventResized = this.eventResizeHandler;
    this.dpCal.onEventDeleted = this.eventDeleteHandler;
    this.dpCal.onTimeRangeSelected = this.timeRangeSelectHandler;

    if (this.disabled) {
      this.dpCal.eventClickHandling = "Disabled";
      this.dpCal.eventMoveHandling = "Disabled";
      this.dpCal.timeRangeSelectedHandling = "Disabled";
    } else {
      this.dpCal.eventClickHandling = "Enabled";
      this.dpCal.eventMoveHandling = "Enabled";
      this.dpCal.timeRangeSelectedHandling = "Enabled";
    }

    this.dpCal.init();
  },

  methods: {
    timeRangeSelectHandler(e) {
      this.$emit("range-selected", {
        start: e.start.value,
        end: e.end.value
      });
      this.dpCal.clearSelection();
    },

    eventClickHandler(e) {
      console.log(e);
      this.$emit("event-clicked", e);
    },

    eventMoveHandler(e) {
      console.log(e);
      this.$emit("event-moved", e);
    },

    eventResizeHandler(e) {
      console.log(e);
      this.$emit("event-resized", e);
    },

    eventDeleteHandler(e) {
      console.log(e);
      this.$emit("event-deleted", e);
    }
  }
};
</script>

<style scoped>
</style>
