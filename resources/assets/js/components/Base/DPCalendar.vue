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

    this.dpCal.onEventMoved = this.eventMoveHandler;
    this.dpCal.onEventClicked = this.eventClickHandler;
    this.dpCal.onEventResized = this.eventResizeHandler;
    this.dpCal.onEventDeleted = this.eventDeleteHandler;
    this.dpCal.onTimeRangeSelected = this.timeRangeSelectHandler;

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
