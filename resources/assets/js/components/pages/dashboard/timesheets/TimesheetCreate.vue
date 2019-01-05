<template>
  <nav-view :scrollbar="true" :loading="loading" padding="p-2">
    <template slot="toolbar">
      <date-picker v-model="weekStart" range="Week"/>
    </template>

    <template slot="drawer">
      <ul class="v-menu">
        <li class="menu-title">Actions</li>
        <li>
          <button class="btn btn-light" @click="onSave">Save</button>
        </li>
        <li>
          <button class="btn btn-light" @click="onSubmit">Submit</button>
        </li>
        <li>
          <button class="btn btn-light" @click="onCancel">Cancel</button>
        </li>
        <li>
          <button
            type="button"
            class="btn btn-light"
            @click="genFromSchedule(scheduleItems)"
          >Generate</button>
        </li>
        <li class="menu-title">Links</li>
      </ul>
    </template>

    <template slot="content">
      <ts-grid 
        v-model="timesheetItems" 
        :columns="columns" 
        :has-toolbar="false">
        <template slot-scope="{ item }">
          <td>{{ item.day }}</td>
          <td>{{ item.job.title }}</td>
          <td>{{ item.job.title }}</td>
          <td>{{ item.startTime }}</td>
          <td>{{ item.EndTime }}</td>
          <td>{{ item.break_length }}</td>
          <td>{{ totalTime(item) }}</td>
        </template>
        <template 
          slot="grid-modal" 
          slot-scope="{ item }">

          <div class="p-2">
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">Number</label>
                <div class="col-sm-10">
                  <input
                    v-model="item.number"
                    type="text"
                    placeholder="please enter number..."
                    class="form-control"
                    id="number"
                  >
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">Street</label>
                <div class="col-sm-10">
                  <input
                    v-model="item.street"
                    type="text"
                    placeholder="please enter street..."
                    class="form-control"
                    id="street"
                  >
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">Suburb</label>
                <div class="col-sm-10">
                  <input
                    v-model="item.suburb"
                    type="text"
                    placeholder="please enter suburb..."
                    class="form-control"
                    id="suburb"
                  >
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">Unit</label>
                <div class="col-sm-10">
                  <input
                    v-model="item.unit"
                    type="text"
                    placeholder="please enter unit..."
                    class="form-control"
                    id="unit"
                  >
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">Country</label>
                <div class="col-sm-10">
                  <input
                    v-model="item.country"
                    type="text"
                    placeholder="please enter country..."
                    class="form-control"
                    id="country"
                  >
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">Post Code</label>
                <div class="col-sm-10">
                  <input
                    v-model="item.postcode"
                    type="text"
                    placeholder="please enter post code..."
                    class="form-control"
                    id="postcode"
                  >
                </div>
              </div>
            </div>
          </div>
        </template>
      </ts-grid>

      <div class="stats">
        <div>TOTAL: {{ this.showTotalTime }}</div>
        <div>STATUS:</div>
      </div>
    </template>
  </nav-view>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import NavView from "../../../framework/NavView.vue";
import DatePicker from "../../../framework/BaseDatePicker.vue";
import pageMixin from "../../../../mixins/page-mixin";
import uuid from '../../../../utils/uuid';

export default {
  name: "TimesheetCreate",

  mixins: [pageMixin],

  components: {
    "nav-view": NavView,
    "date-picker": DatePicker
  },

  data() {
    return {
      loading: false,      
      weekEnd: "",
      weekStart: "",
      scheduleItems: [],
      timesheetItems: [],
      columns: [
        { key: "day", value: "Day" },
        { key: "link", value: "Link" },
        { key: "job", value: "Job" },
        { key: "start-time", value: "Start Time" },
        { key: "end-time", value: "End Time" },
        { key: "break", value: "Break" },
        { key: "total", value: "Total" }
      ],
      
    };
  },

  watch: {
    weekStart: function(value) {
      this.weekEnd = value.addDays(7);
      this.fetchItems();
    }
  },

  computed: {
    ...mapGetters({
      userId: "user/getId"
    }),

    weekDays: function() {
      let days = [];
      let day = this.weekStart;
      for (let i = 0; i < 7; i++) {
        days.push(day);
        day = day.addDays(1);
      }
      return days;
    },

    showTotalTime: function() {
      let total = "00:00:00";
      this.timesheetItems.forEach(item => {
        total = this.addTime(total, item.total);
      });
      return total;
    }
  },

  created() {
    this.weekStart = DayPilot.Date.today().firstDayOfWeek();
    this.weekEnd = this.weekStart.addDays(7);
  },

  mounted() {    
  },

  methods: {
    ...mapActions({
      fetch: "resource/list",
      create: "resource/create"
    }),

    fetchItems() {
      this.loading = true;
      this.scheduleItems = [];
      this.timesheetItems = [];

      let p1 = this.fetch({
        resource: "timesheet",
        query: {
          inRange: `${this.weekStart},${this.weekEnd}`,
          with: "job,request"
        }
      }).then(data => {
        console.log("p1 finished...");
        this.genFromTimesheet(data);
        this.loading = false;
      });

      let p2 = this.fetch({
        resource: "schedule",
        query: {
          eq: `user_id,${this.userId}`,
          with: "job",
          inRange: `${this.weekStart},${this.weekEnd}`
        }
      }).then(data => {
        console.log("p2 finished...");
        this.scheduleItems = data;
      });

      Promise.all([p1, p2])
        .then(() => {
          this.loading = false;
        })
        .catch(error => {
          console.log(error);
        });
    },

    onSubmit(event) {
      let timesheets = this.timesheetItems.map(item => {
        return {
          job_id: item.job_id,
          user_id: item.user_id,
          type: "timesheet",
          status: "draft",
          break_length: item.break_length,
          start: item.startDate + "T" + item.startTime,
          end: item.endDate + "T" + item.endTime
        };
      });
      console.log(timesheets);
      console.log(this.weekDays);
    },

    onSave(event) {
      let timesheets = this.timesheetItems.map(item => {
        return {
          job_id: item.job_id,
          user_id: item.user_id,
          type: "timesheet",
          break_length: item.break_length,
          start: item.startDate + "T" + item.startTime,
          end: item.endDate + "T" + item.endTime
        };
      });

      this.create({
        resource: "timesheet",
        data: { status: "draft", timesheets: timesheets }
      })
        .then(() => {
          console.log("draft created successfuly.");
        })
        .catch(error => {
          console.log(error.response);
        });
      console.log(timesheets);
    },

    onCancel() {
      this.$router.go(-1);
    },

    genFromTimesheet(data) {
      let day = null;
      for (let i = 0; i < 7; i++) {
        day = this.weekDays[i];
        data.forEach(item => {
          if (item.start >= day.addDays(-1) && item.end <= day) {
            let timesheet = {};
            timesheet.id = uuid();
            timesheet.job = item.job;
            timesheet.day = day.toString("ddd dd MMM");
            timesheet.startDate = day.toString().slice(0, 10);
            timesheet.startTime = item.start.slice(11, 16);
            timesheet.endDate = day.toString().slice(0, 10);
            timesheet.endTime = item.end.slice(11, 16);
            timesheet.break_length = item.break_length;
            timesheet.total = this.subTime(
              this.subTime(timesheet.endTime, timesheet.startTime),
              timesheet.break_length
            );
            this.timesheetItems.push(timesheet);
          }
        });
      }
    },

    genFromSchedule(data) {
      let day = null;
      for (let i = 0; i < 7; i++) {
        day = this.weekDays[i];
        data.forEach(item => {
          if (item.start <= day && item.end >= day) {
            item.day = day.toString("ddd dd MMM");
            item.startDate = day.toString().slice(0, 10);
            item.startTime = item.start.slice(11, 16);
            item.endDate = day.toString().slice(0, 10);
            item.endTime = item.start.slice(11, 16);
            item.total = this.subTime(
              this.subTime(item.endTime, item.startTime),
              item.break_length
            );
            this.timesheetItems.push(Object.assign({}, item));
          }
        });
      }
    },

    totalTime(item) {
      let workHour = this.subTime(item.endTime, item.startTime);
      item.total = this.subTime(workHour, item.break_length);
    },

    subTime(a, b) {
      let time1 = {},
        time2 = {};
      let hour, min, sec;

      time1.raw = a.split(":");
      time2.raw = b.split(":");

      time1.hour = parseInt(time1.raw[0]);
      time1.min = parseInt(time1.raw[1]);
      time1.sec = parseInt(time1.raw[2] | "0");

      time2.hour = parseInt(time2.raw[0]);
      time2.min = parseInt(time2.raw[1]);
      time2.sec = parseInt(time2.raw[2] | "0");

      hour = time1.hour - time2.hour;
      min = time1.min - time2.min;
      sec = time1.sec - time2.sec;

      if (sec < 0) {
        sec = 60 - (time2.sec - time1.sec);
        min--;
      }

      if (min < 0) {
        min = 60 - (time2.min - time1.min);
        hour--;
      }

      if (hour < 0) {
        return "00:00:00";
      }

      return `${hour < 10 ? "0" + hour : hour}:${min < 10 ? "0" + min : min}:${
        sec < 10 ? "0" + sec : sec
      }`;
    },

    addTime(a, b) {
      let time1 = {},
        time2 = {};
      let hour, min, sec;

      time1.raw = a.split(":");
      time2.raw = b.split(":");

      time1.hour = parseInt(time1.raw[0]);
      time1.min = parseInt(time1.raw[1]);
      time1.sec = parseInt(time1.raw[2] | "0");

      time2.hour = parseInt(time2.raw[0]);
      time2.min = parseInt(time2.raw[1]);
      time2.sec = parseInt(time2.raw[2] | "0");

      hour = time1.hour + time2.hour;
      min = time1.min + time2.min;
      sec = time1.sec + time2.sec;

      if (sec >= 60) {
        sec = sec % 60;
        min++;
      }

      if (min >= 60) {
        min = min % 60;
        hour++;
      }

      return `${hour < 10 ? "0" + hour : hour}:${min < 10 ? "0" + min : min}:${
        sec < 10 ? "0" + sec : sec
      }`;
    }
  }
};
</script>

<style scoped>
.stats {
  position: absolute;
  padding: 10px;
  bottom: 5px;
  right: 5px;
  background-color: darkgray;
}
</style>