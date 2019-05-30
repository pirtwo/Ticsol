<template>
  <div class="vb-datepicker d-flex align-items-center">
    <div 
      class="btn-group" 
      role="group" 
      aria-label="select date"
    >
      <button 
        type="button" 
        class="btn btn-sm btn-secondary" 
        @click="onBack"
      >
        <i class="material-icons">keyboard_arrow_left</i>
      </button>
      <button 
        type="button" 
        class="btn btn-sm btn-secondary" 
        @click="onToday"
      >
        Today
      </button>
      <button 
        type="button" 
        class="btn btn-sm btn-secondary" 
        @click="onNext"
      >
        <i class="material-icons">keyboard_arrow_right</i>
      </button>
    </div>
    <div class="vb-datepicker__range">
      {{ todayToText }}
    </div>
  </div>
</template>

<script>
export default {
  name: "TsDateScroller",

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
      today: this.getToday(),
      picker: {}
    };
  },

  watch: {
    range: function(value) {
      this.today = this.getToday();
      this.$emit("input", this.today);
    }
  },

  computed: {
    todayToText: function() {
      return this.range == "Month"
        ? this.today.toString("MMM yyyy")
        : `${this.today.toString("MMM dd")} - ${this.today.addDays(6).toString("MMM dd")}`;
    }
  },

  mounted() {
    this.today = this.getToday();
    // this.picker = new DayPilot.DatePicker({
    //     target: 'picker', 
    //     pattern: 'yyyy-MM-dd', 
    //     onTimeRangeSelected: function(args) { 
    //         console.log(args.date);
    //     }
    //   });
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
.vb-datepicker {
  color: white;  
  overflow: hidden;   
  background-color: #6c757d;
  border: 1px solid rgba(0, 0, 0, 0.1);  
}

.vb-datepicker__range{
  padding: 0px 5px;
  font-size: 0.8rem;
}

.btn-group .btn{
  font-size: 0.8rem;
}

.btn-group .btn i {
  font-size: 0.8rem;
  line-height: 1;
  vertical-align: baseline;
}
</style>
