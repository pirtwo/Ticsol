<template>
  <nav-view :scrollbar="true" :loading="loading" padding="p-2">
    <template slot="toolbar">
      <date-picker v-model="weekStart" range="Week"/>
    </template>

    <template slot="drawer">
      <ul class="v-menu">
        <li class="menu-title">
          Actions
        </li>
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
      </ul>
    </template>

    <template slot="content">
      <ts-grid 
        v-model="timesheetItems" 
        :columns="columns" 
        :has-toolbar="false">
        <template slot-scope="{ item }">
          <td>{{ item.date.value }}</td>
          <td>
            <router-link 
              v-if="item.request !== undefined"
              class="btn btn-sm btn-link" 
              :to="{ name : 'jobDetails', params : { id: item.request.id } }">
              <i class="material-icons">request</i>
            </router-link>
          </td>
          <td>
            <router-link 
              class="btn btn-sm btn-link" 
              :to="{ name : 'jobDetails', params : { id: item.job.key } }">
              <span>{{ item.job.value }}</span>
            </router-link>            
          </td>
          <td>{{ item.startTime }}</td>
          <td>{{ item.endTime }}</td>
          <td>{{ item.break_length }}</td>
          <td>{{ totalTime(item) }}</td>
        </template>
        <template 
          slot="grid-modal" 
          slot-scope="{ item }">

          <div class="p-2">
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">Day</label>
                <div class="col-sm-10">
                  <vb-select
                    v-model="item.date"
                    :data="weekDays"
                    id="parent_id"
                    name="jobParent"
                    placeholder="Select day..."
                    search-placeholder="search..."
                  />
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">Job</label>
                <div class="col-sm-10">
                  <vb-select
                    v-model="item.job"
                    :data="jobs"
                    id="parent_id"
                    name="jobParent"
                    placeholder="Select job..."
                    search-placeholder="search..."
                  />
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">Start</label>
                <div class="col-sm-10">
                  <input
                    class="form-control" 
                    v-model="item.startTime"
                    type="time"
                  >
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">End</label>
                <div class="col-sm-10">
                  <input
                    class="form-control" 
                    v-model="item.endTime"
                    type="time"
                  >
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">Break</label>
                <div class="col-sm-10">
                  <ts-timepicker 
                    class="form-control" 
                    v-model="item.break_length" />
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
import TsTimepickerVue from '../../../Base/TsTimepicker.vue';

export default {
  name: "TimesheetCreate",

  mixins: [pageMixin],

  components: {
    "nav-view": NavView,
    "date-picker": DatePicker,
    "ts-timepicker": TsTimepickerVue
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
      this.fetchLists();
    }
  },

  computed: {
    ...mapGetters({
      userId: "user/getId",
      getList: "resource/getList"
    }),

    jobs: function(){
      return this.getList("job").map(item => { return { key: item.id, value: item.title } });
    },

    weekDays: function() {
      let day = this.weekStart;
      let days = [];      
      for (let i = 0; i < 7; i++) {
        let item = {};           
        item.key = i;
        item.value = day.toString("ddd dd MMM");
        item.day = day;               
        days.push(item);
        day = day.addDays(1);
      }
      return days;
    },

    showTotalTime: function() {
      let total = "00:00:00";
      let itemTotal = "";
      this.timesheetItems.forEach(item => {
        itemTotal = this.subTime(
          this.subTime(item.endTime, item.startTime),
          item.break_length
        );
        total = this.addTime(total, itemTotal);
      });
      return total;
    }
  },

  created() {
    this.weekStart = 
      DayPilot.Date.today().firstDayOfWeek();
    this.weekEnd = 
      this.weekStart.addDays(7);
  },

  mounted() {  
    this.loadingStart();
    this.fetchList({resource: "job"}).then(()=>{
      this.loadingStop();
    });
  },

  methods: {
    ...mapActions({
      fetchList: "resource/list",
      create: "resource/create"
    }),

    fetchLists() {
      this.loadingStart();
      this.scheduleItems = [];
      this.timesheetItems = [];

      let p1 = this.fetchList({
        resource: "timesheet",
        query: {
          inRange: `${this.weekStart},${this.weekEnd}`,
          with: "job,request"
        }
      }).then(data => {
        console.log("p1 finished...");
        this.genFromTimesheet(data);
        this.loadingStop();
      });

      let p2 = this.fetchList({
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
          this.loadingStop();
        })
        .catch(error => {
          console.log(error);
        });
    },

    onSubmit(event) {
      let timesheets = this.timesheetItems.map(item => {
        return {
          job_id: item.job.key,
          user_id: item.user_id,
          type: "timesheet",
          status: "submitted",
          break_length: item.break_length,
          start: item.date.day.value.slice(0, 10) + "T" + item.startTime,
          end: item.date.day.value.slice(0, 10) + "T" + item.endTime
        };
      });
      console.log(timesheets);
      console.log(this.weekDays);
    },

    onSave(event) {
      let timesheets = this.timesheetItems.map(item => {
        return {
          job_id: item.job.key,
          user_id: item.user.id,
          type: "timesheet",
          break_length: item.break_length,
          start: item.date.day.toString().slice(0, 10) + "T" + item.startTime,
          end: item.date.day.toString().slice(0, 10) + "T" + item.endTime
        };
      });

      this.create({
        resource: "timesheet",
        data: { status: "draft", timesheets: timesheets }
      })
        .then(() => {
          this.showMessage(
            `Draft created successfuly.`,
            "success"
          );
        })
        .catch(error => {
           this.showMessage(error.message, "danger");  
        });

      console.log(timesheets);
    },

    onCancel() {
      this.$router.go(-1);
    },

    genFromTimesheet(data) {
      let day = null;
      for (let i = 0; i < 7; i++) {
        day = this.weekDays[i].day;
        data.forEach(item => {
          if (item.start >= day.addDays(-1) && item.end <= day) {
            let timesheet = {};
            timesheet.id = uuid();
            timesheet.date = this.weekDays[i];    
            timesheet.job = this.jobs.find(job => job.key == item.job.id);            
            timesheet.request = item.request;                   
            timesheet.startTime = item.start.slice(11, 16);            
            timesheet.endTime = item.end.slice(11, 16);
            timesheet.break_length = item.break_length;            
            this.timesheetItems.push(timesheet);
          }
        });
      }
    },

    genFromSchedule(data) {
      let day = null;
      for (let i = 0; i < 7; i++) {
        day = this.weekDays[i].day;
        data.forEach(item => {
          if (item.start <= day && item.end >= day) {
            let timesheet = {};
            timesheet.id = uuid();
            timesheet.date = this.weekDays[i];       
            timesheet.job = this.jobs.find(job => job.key == item.job.id);          
            timesheet.request = item.request;                
            timesheet.startTime = item.start.slice(11, 16);            
            timesheet.endTime = item.end.slice(11, 16);
            timesheet.break_length = item.break_length;            
            this.timesheetItems.push(timesheet);
          }
        });
      }
    },

    totalTime(item) {
      let workHour = this.subTime(item.endTime, item.startTime);
      item.total = this.subTime(workHour, item.break_length);
      return item.total;
    },

    subTime(a, b) {      
      let time1 = {},
        time2 = {};
      let hour, min, sec;

      if(!a || !b){
        a = '00:00:00';
        b = '00:00:00';
      }

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

      if(!a || !b){
        a = '00:00:00';
        b = '00:00:00';
      }

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