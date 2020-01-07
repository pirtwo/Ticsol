<template>
  <div class="wrap-calendar">
    <div
      v-if="disabled"
      class="cover"
    />
    <div id="dpCal" />
  </div>
</template>

<script>
export default {
  name: "DPCalendar",

  props: {
    startDate: {
      type: [Object, String],
      default: ""
    },
    events: {
      type: Array,
      default: () => {
        return [];
      }
    },
    eventHeight:{
      type:Number,
      default: 50
    },
    viewType: {
      type: String,
      default: "Days",
      validator: val => {
        return ["Days", "Weeks", "Month"].indexOf(val) > -1;
      }
    },
    days: {
      type: Number,
      default: 7
    },
    weeks: {
      type: Number,
      default: 1
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

    message: function(val) {
      this.dpCal.message(val.msg, val.delay);
    }
  },

  mounted() {
    this.dpCal = new window.DayPilot.Month("dpCal");
    this.dpCal.viewType = this.viewType;
    if(this.viewType === 'Days') this.dpCal.days = this.days;
    if(this.viewType === 'Weeks') this.dpCal.weeks = this.weeks;
    this.dpCal.weekStart = 1;
    this.dpCal.startDate = this.startDate;
    this.dpCal.weeks = 1;
    this.dpCal.timeRangeSelectedHandling = true;
    this.dpCal.events.list = [];

    this.dpCal.eventHeight = this.eventHeight;

    this.dpCal.onEventMoved = this.eventMoveHandler;
    this.dpCal.onEventClicked = this.eventClickHandler;
    this.dpCal.onEventResized = this.eventResizeHandler;
    this.dpCal.onEventDeleted = this.eventDeleteHandler;
    this.dpCal.onTimeRangeSelected = this.timeRangeSelectHandler;

    this.dpCal.init();
  },

  methods: {
    timeRangeSelectHandler(e) {
      if (this.disabled) return;

      this.$emit("range-selected", {
        start: e.start.value,
        end: e.end.value
      });

      this.dpCal.clearSelection();
    },

    eventClickHandler(e) {
      console.log(e);
      if (this.disabled) return;
      this.$emit("event-clicked", e);
    },

    eventMoveHandler(e) {
      console.log(e);
      if (this.disabled) return;
      this.$emit("event-moved", e);
    },

    eventResizeHandler(e) {
      console.log(e);
      if (this.disabled) return;
      this.$emit("event-resized", e);
    },

    eventDeleteHandler(e) {
      console.log(e);
      if (this.disabled) return;
      this.$emit("event-deleted", e);
    }
  }
};
</script>

<style scoped>
.wrap-calendar {
  position: relative;
}

.cover {
  width: 100%;
  height: 100%;
  position: absolute;
  background-color: #bfbfbf54;
  z-index: 5;
}
</style>
