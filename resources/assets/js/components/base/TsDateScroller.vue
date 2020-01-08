<template>
  <div class="vb-datepicker d-flex align-items-center">
    <div
      class="btn-group"
      role="group"
      aria-label="select date"
    >
      <button
        type="button"
        class="btn btn-sm"
        @click="onBack"
      >
        <i class="material-icons">keyboard_arrow_left</i>
      </button>
      <button
        type="button"
        class="btn btn-sm"
        @click="onToday"
      >
        Today
      </button>
      <button
        type="button"
        class="btn btn-sm"
        @click="onNext"
      >
        <i class="material-icons">keyboard_arrow_right</i>
      </button>
    </div>
    <div class="vb-datepicker__range">
      {{ dayToText }}
    </div>
  </div>
</template>

<script>
import moment from "moment";

export default {
  name: "TsDateScroller",

  props: {
    value: {
      type: [Object],
      default: () => {
        return {};
      }
    },

    range: {
      type: String,
      default: "",
      validator: val => {
        return ["Month", "Week"].indexOf(val) > -1;
      }
    },

    weekStart: {
      type: String,
      default: "Mon",
      validator: val => {
        return (
          ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"].indexOf(val) > -1
        );
      }
    },

    stepSize: {
      type: Number,
      default: null
    },

    stepType: {
      type: String,
      default: "d",
      validator: val => {
        return ["d", "M"].indexOf(val) > -1;
      }
    }
  },

  data() {
    return {
      start: this.calcStart(this.range)
    };
  },

  watch: {
    value: function(val) {
      if (val) this.start = val.start;
    },

    range: function(value) {
      this.start = this.calcStart(value);
      this.$emit("input", { start: this.start.clone(), end: this.calcEnd() });
      this.$emit("change");
    }
  },

  computed: {
    dayToText: function() {
      return this.range == "Month"
        ? this.start.format("MMM YYYY")
        : `${this.start.format("MMM DD")} - 
          ${this.calcEnd().format("MMM DD")}`;
    }
  },

  mounted() {
    this.start = this.calcStart(this.range);
    this.$emit("input", { start: this.start.clone(), end: this.calcEnd() });
    this.$emit("change");
  },

  methods: {
    calcStart(range) {
      let now = moment();
      let weekStart = moment().day(this.weekStart);
      if (weekStart.isAfter(now)) weekStart.add(-7, "d");

      return range == "Month" ? now.startOf("month") : weekStart;
    },

    calcEnd() {
      if (this.stepSize) {
        return this.start.clone().add(this.stepSize - 1, this.stepType);
      } else {
        return this.range == "Month"
          ? this.start
              .clone()
              .add(1, "M")
              .add(-1, "d")
              .endOf("d")
          : this.start
              .clone()
              .add(6, "d")
              .endOf("d");
      }
    },

    onToday() {
      this.start = this.calcStart(this.range);
      this.$emit("input", { start: this.start.clone(), end: this.calcEnd() });
      this.$emit("change");
    },

    onNext() {
      if (this.stepSize) {
        this.start = this.start.add(this.stepSize, this.stepType);
      } else {
        this.start =
          this.range == "Month"
            ? this.start.add(1, "M")
            : this.start.add(7, "d");
      }

      this.$emit("input", { start: this.start.clone(), end: this.calcEnd() });
      this.$emit("change");
    },

    onBack() {
      if (this.stepSize) {
        this.start = this.start.add(-this.stepSize, this.stepType);
      } else {
        this.start =
          this.range == "Month"
            ? this.start.add(-1, "M")
            : this.start.add(-7, "d");
      }

      this.$emit("input", { start: this.start.clone(), end: this.calcEnd() });
      this.$emit("change");
    }
  }
};
</script>

<style scoped>
.vb-datepicker {
  overflow: hidden;
  border: 1px solid rgba(0, 0, 0, 0.1);
  background-color: inherit;
  display: flex;
  justify-content: left;
  align-items: center;
}

.vb-datepicker__range {
  font-size: inherit;
  padding: 0px 5px;
  width: 130px;
  text-align: center;
}

.btn-group {
  height: 100%;
}

.btn-group .btn {
  font-size: inherit;
  align-items: center;
  display: flex;
}

.btn-group .btn i {
  line-height: 1;
}

.btn-group .btn:focus {
  box-shadow: none;
}
</style>
