<template>
  <div class="wrap-datepicker d-flex align-items-center">
    <div>
      <button 
        @click="onBack" 
        type="button" 
        class="btn btn-sm btn-light btn-left">
        <i class="material-icons">keyboard_arrow_left</i>
      </button>
      <div class="datepicker-body">
        <button 
          @click="onToday" 
          type="button" 
          class="btn btn-sm btn-light">
          Today
        </button>
        <div>{{ todayToText }}</div>
      </div>
      <button 
        @click="onNext" 
        type="button" 
        class="btn btn-sm btn-light btn-right">
        <i class="material-icons">keyboard_arrow_right</i>
      </button>
    </div>        
  </div>
</template>

<script>
export default {
  name: "BaseDatePicker",

  props: {
    value: {
      type: [String, Object],
      default: ''
    },
    range: {
      type: String,
      default: "",
      validator: value => {
        return ["Month", "Week"].indexOf(value) !== -1;
      }
    }
  },

  data() {
    return {
      today: this.getToday()
    };
  },

  watch: {
    range: function(value) {
      this.today = this.getToday();
      this.$emit("input", this.today);
    },

    // today: function(value) {
    //   console.log(value);
    // }
  },

  computed: {
    todayToText: function() {
      return this.range == "Month"
        ? this.today.toString("MMM yyyy")
        : `W${this.today.weekNumber()} ${this.today.toString("yyyy")}`;
    }
  },

  mounted() {
    this.today = this.getToday();
  },

  methods: {
    getToday() {
      return this.range == "Month"
        ? DayPilot.Date.today().firstDayOfMonth()
        : DayPilot.Date.today().firstDayOfWeek();
    },

    onToday() {
      this.today = this.getToday();
      this.$emit("input", this.today);
    },

    onNext() {
      this.today =
        this.range == "Month"
          ? this.today.addMonths(1)
          : this.today.addDays(7);
      this.$emit("input", this.today);
    },

    onBack() {
      this.today =
        this.range == "Month"
          ? this.today.addMonths(-1)
          : this.today.addDays(-7);
      this.$emit("input", this.today);
    }
  }
};
</script>

<style scoped>
.wrap-datepicker {
  border: 1px solid black;
  overflow: hidden;
}

.wrap-datepicker div {
  display: inline-block;
  vertical-align: middle;
}

.datepicker-body {
  width: 120px;
  font-size: 0.8rem;
  vertical-align: middle;
  display: inline-block;
}

.datepicker-body .btn {
  font-size: 0.8rem;
  padding: 6px;
  margin-right: 3px;
}

.btn {
  font-size: 1rem;
  display: inline-block;
}

.btn i {
  font-size: 1rem;
  line-height: 1;
}

.btn-left {
  border-right: 1px solid black;
}

.btn-right {
  border-left: 1px solid black;
}
</style>
