<template>
    <div class="datepicker">
        <div class="wrap">
            <button @click="onBack" type="button" class="btn btn-sm btn-light">
                <i class="material-icons">keyboard_arrow_left</i>
            </button>
            <div class="datepicker-body">
                <button @click="onToday" type="button" class="btn btn-sm btn-light">
                    Today
                </button>
                <div>{{ todayToText }}</div>
            </div>
            <button @click="onNext" type="button" class="btn btn-sm btn-light">
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
      type: [String, Object]
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
      today: DayPilot.Date.today()
    };
  },

  computed: {
    todayToText: function() {
      return this.range === "Month"
        ? this.today.toString("yyyy/MMM")
        : this.today.toString("yyyy") + "/W" + this.today.weekNumber();
    }
  },

  mounted() {
    this.today = this.getToday();
  },

  methods: {
    getToday() {
      return this.range === "Month"
        ? DayPilot.Date.today().firstDayOfMonth()
        : DayPilot.Date.today().firstDayOfWeek();
    },

    onToday() {
      this.today = this.getToday();
      this.$emit("input", this.today);
    },

    onNext() {
      this.today =
        this.range === "Month"
          ? this.today.addMonths(1)
          : this.today.addDays(7);
      this.$emit("input", this.today);
    },

    onBack() {
      this.today =
        this.range === "Month"
          ? this.today.addMonths(-1)
          : this.today.addDays(-7);
      this.$emit("input", this.today);
    }
  }
};
</script>

<style scoped>
.datepicker {
  padding: 3px;
  overflow: hidden;
  border-radius: 18px;
  display: inline-block;  
  background: linear-gradient(to bottom, #eeeeee, #ffffff 25px);
  background-image: -webkit-linear-gradient(top, #eeeeee, #ffffff 25px);
  box-shadow: inset 0 -1px white, inset 0 1px 1px rgba(0, 0, 0, 0.05);
}

.wrap {
  background-color: #f8f9fa;
  border-radius: 18px;
  overflow: hidden;
  /* box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.12), inset 0 0 2px rgba(0, 0, 0, 0.15); */
}

.datepicker-body {
  width: 120px;
  font-size: 0.8rem;
  vertical-align: middle;
  display: inline-block;
}

.datepicker-body .btn {
  font-size: 0.8rem;
  line-height: 1;
  margin-right: 3px;
}

.datepicker div {
  display: inline-block;
  vertical-align: middle;
}

.btn {
  font-size: 1rem;
  line-height: 1;
  display: inline-block;
}

.btn i {
  font-size: 1rem;
  line-height: 1;
}
</style>
