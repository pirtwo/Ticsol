<template>
  <app-main :scrollbar="true" :loading="isLoading" padding="p-2">
    <template slot="toolbar">
      <ts-datescroller v-if="!id" v-model="weekStart" @input="fetchLists" range="Week"/>
    </template>

    <template slot="drawer">
      <ul class="v-menu">
        <li class="menu-title">Actions</li>
        <li>
          <button class="btn btn-light" @click="onSave">Save</button>
        </li>
        <li>
          <button class="btn btn-light" @click="approverModal = true">Submit</button>
        </li>
        <li>
          <button
            type="button"
            class="btn btn-light"
            @click="genFromSchedule(scheduleItems)"
          >Generate</button>
        </li>
        <li>
          <button class="btn btn-light" @click="onCancel">Cancel</button>
        </li>
        <li class="menu-title">Links</li>
        <li v-if="this.timesheet">
          <router-link
            class="btn btn-link"
            :to="{ name: 'commentList', params : { entity: 'timesheet', id: this.timesheet.id } }"
          >Comments</router-link>
        </li>
      </ul>
    </template>

    <template slot="content">
      <ts-grid v-model="timesheetItems" :columns="columns" :has-toolbar="false">
        <template slot-scope="{ item }">
          <td>{{ item.date ? item.date.value : "" }}</td>
          <td>
            <router-link
              v-if="item.job"
              class="btn btn-sm btn-link"
              :to="{ name : 'jobDetails', params : { id: item.job.key } }"
            >
              <span>{{ item.job.value }}</span>
            </router-link>
          </td>
          <td>{{ item.startTime }}</td>
          <td>{{ item.endTime }}</td>
          <td>{{ item.break_length.slice(0,5) }}</td>
          <td>{{ workHours(item).slice(0,5) }}</td>
        </template>
        <template slot="grid-modal" slot-scope="{ item }">
          <div class="p-2">
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">Day</label>
                <div class="col-sm-10">
                  <ts-select
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
                  <ts-select
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
                  <input class="form-control" v-model="item.startTime" type="time">
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">End</label>
                <div class="col-sm-10">
                  <input class="form-control" v-model="item.endTime" type="time">
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">Break</label>
                <div class="col-sm-10">
                  <ts-timepicker class="form-control" v-model="item.break_length"/>
                </div>
              </div>
            </div>
          </div>
        </template>
        <template slot="footer">
          <tr>
            <td v-if="timesheetItems.length > 0" colspan="7" class="table-status">
              <div class="ts-status d-flex flex-column">
                <div class="d-flex flex-row justify-content-end">
                  <div class="ts-status__lable">STATUS</div>
                  <div class="ts-status__val">{{ this.status.toUpperCase() }}</div>
                </div>
                <div class="d-flex flex-row justify-content-end">
                  <div class="ts-status__lable">TOTAL</div>
                  <div
                    class="ts-status__val"
                  >{{ this.totalHours.slice(0,5) == '00:00' ? '' : this.totalHours.slice(0,5) }}</div>
                </div>
              </div>
            </td>
          </tr>
        </template>
      </ts-grid>

      <!-- Approver Modal -->
      <ts-modal title="Chose Approver" :show.sync="approverModal">
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-12 col-form-lable">Approver</label>
            <div class="col-sm-12">
              <ts-select
                v-model="approver"
                :data="users"
                id="assigned_id"
                placeholder="Select approver..."
                search-placeholder="search..."
              />
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-12 col-form-lable">Comment</label>
            <div class="col-sm-12">
              <editor
                v-model="comment"
                :init="{height: 200, menubar: false, statusbar: false}"
                api-key="he51k5qf4qe8668k9rgkie9c13j01h43fh61m72chuvv93ip"
                plugins="bbcode code"
                toolbar="newdocument | undo redo | cut copy paste pastetext | selectall | code"
              />
            </div>
          </div>
        </div>
        <template slot="footer">
          <button type="button" class="btn btn-primary" @click="onSubmit">Submitt</button>
          <button type="button" class="btn btn-primary" @click="approverModal = false">Cancel</button>
        </template>
      </ts-modal>
    </template>
  </app-main>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import pageMixin from "../../../../mixins/page-mixin";
import Editor from "@tinymce/tinymce-vue";
import uuid from "../../../../utils/uuid";

export default {
  name: "TimesheetModify",

  mixins: [pageMixin],

  components: {
    editor: Editor
  },

  props: ["id", "start", "end"],

  data() {
    return {
      approverModal: false,
      isReady: false,
      status: "",
      weekEnd: "",
      weekStart: "",
      timesheet: null,
      approver: {},
      scheduleItems: [],
      timesheetItems: [],
      comment: "",
      columns: [
        { key: "day", value: "Day" },
        { key: "job", value: "Job" },
        { key: "start-time", value: "Start" },
        { key: "end-time", value: "End" },
        { key: "break", value: "Break" },
        { key: "total", value: "Total" }
      ]
    };
  },

  watch: {
    
  },

  computed: {
    ...mapGetters({
      userId: "user/getId",
      getList: "resource/getList"
    }),

    jobs: function() {
      return this.getList("job").map(item => {
        return { key: item.id, value: item.title };
      });
    },

    users: function() {
      return this.getList("user").map(item => {
        return { key: item.id, value: item.name };
      });
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

    totalHours: function() {
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

  beforeRouteEnter(to, from, next) {    
    next(vm => {
      vm.clearForm();
    });
  },

  created() {
    this.weekStart = DayPilot.Date.today().firstDayOfWeek();
  },

  mounted() {
    this.startLoading();
    let loadJobs = this.fetchList({ resource: "job" });
    let loadUsers = this.fetchList({ resource: "user" });
    Promise.all([loadJobs, loadUsers]).then(() => {
      console.log("assets loading finished...");
      this.stopLoading();
      this.fetchLists();
      this.isReady = true;
    });
  },

  methods: {
    ...mapActions({
      fetchList: "resource/list",
      show: "resource/show",
      create: "resource/create",
      update: "resource/update"
    }),

    fetchLists() {
      this.startLoading();
      this.status = "-";
      this.approver = {};
      this.timesheet = null;
      this.scheduleItems = [];
      this.timesheetItems = [];
      this.weekEnd = this.weekStart.addDays(7);

      let query = this.id
        ? []
        : [
            { opt: "eq", col: "week_start", val: this.weekStart.toString() },
            {
              opt: "eq",
              col: "week_end",
              val: this.weekEnd.toString()
            }
          ];

      let p1 = this.show({
        resource: "timesheet",
        id: this.id || "",
        query: this.$queryBuilder(
          null,
          null,
          ["request", "schedules.job"],
          query
        )
      }).then(data => {
        console.log("Loading timesheet finished...");
        if (data) {
          this.timesheet = data;
          this.status = data.request.status;
          this.approver = this.users.find(
            item => item.key === data.request.assigned_id
          );
          this.weekStart = new DayPilot.Date(this.timesheet.week_start);
          this.weekEnd = new DayPilot.Date(this.timesheet.week_end);
          this.genFromTimesheet(data.schedules);
        }
        this.stopLoading();
      });

      let p2 = this.fetchList({
        resource: "schedule",
        query: {
          eq: `user_id,${this.userId}`,
          with: "job",
          inRange: `${this.start || this.weekStart},${this.end || this.weekEnd}`
        }
      }).then(data => {
        console.log("loading schedules finished...");
        this.scheduleItems = data;
      });

      Promise.all([p1, p2])
        .then(() => {
          this.stopLoading();
        })
        .catch(error => {
          console.log(error);
        });
    },

    onSubmit(e) {
      if (this.timesheet) {
        this.updateTimesheet("submitted");
      } else {
        this.createTimesheet("submitted");
      }     

      this.approverModal = false;
    },

    onSave(e) {
      if (this.timesheet) {
        this.updateTimesheet("draft");
      } else {
        this.createTimesheet("draft");
      }
    },

    onCancel() {
      this.$router.go(-1);
    },

    createComment(timesheetId) {
      this.create({
        resource: "comment",
        data: {
          entity: "timesheet",
          parent_id: this.parentId,
          body: this.comment,
          timesheet_id: timesheetId
        }
      })
        .then(() => {
          console.log("comment creataed successfuly...");
          this.comment = "";
        })
        .catch(error => {
          console.log(error);
          this.showMessage(error.message, "danger");
        });
    },

    createTimesheet(status) {
      this.create({
        resource: "timesheet",
        data: {
          status: status,
          assigned_id:
            this.approver.key === undefined ? null : this.approver.key,
          week_start: this.weekStart,
          week_end: this.weekEnd,
          total_hours: this.totalHours,
          items: this.getTimesheetItems()
        }
      })
        .then((data) => {
          this.showMessage(
            `${
              status == "draft"
                ? "Draft created successfuly."
                : "Timesheet submitted successfuly."
            }`,
            "success"
          );

          if (this.comment) {
            this.createComment(data.id);
          }
        })
        .catch(error => {
          this.showMessage(error.message, "danger");
        });
    },

    updateTimesheet(status) {
      this.update({
        resource: "timesheet",
        id: this.timesheet.id,
        data: {
          status: status,
          assigned_id:
            this.approver.key === undefined ? null : this.approver.key,
          total_hours: this.totalHours,
          items: this.getTimesheetItems()
        }
      })
        .then(() => {
          this.showMessage(`Timesheet updated successfuly.`, "success");
        })
        .catch(error => {
          this.showMessage(error.message, "danger");
        });
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
          if (item.start >= day.addDays(-1) && item.end <= day) {
            let timesheet = {};
            timesheet.id = uuid();
            timesheet.date = this.weekDays[i];
            timesheet.job = this.jobs.find(job => job.key == item.job.id);
            timesheet.startTime = item.start.slice(11, 16);
            timesheet.endTime = item.end.slice(11, 16);
            timesheet.break_length = item.break_length;
            this.timesheetItems.push(timesheet);
          }
        });
      }
    },

    getTimesheetItems() {
      let items = this.timesheetItems.map(item => {
        return {
          job_id: item.job.key,
          user_id: this.userId,
          type: "timesheet",
          event_type: "scheduled",
          status: "tentative",
          break_length: item.break_length,
          start: item.date.day.value.slice(0, 10) + "T" + item.startTime,
          end: item.date.day.value.slice(0, 10) + "T" + item.endTime
        };
      });
      return items;
    },

    workHours(item) {
      let workHour = this.subTime(item.endTime, item.startTime);
      item.total = this.subTime(workHour, item.break_length);
      return item.total;
    },

    subTime(a, b) {
      let time1 = {},
        time2 = {};
      let hour, min, sec;

      if (!a || !b) {
        a = "00:00:00";
        b = "00:00:00";
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

      if (!a || !b) {
        a = "00:00:00";
        b = "00:00:00";
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
.ts-status {
  margin-right: 25px;
}

.ts-status__lable {
  text-align: left;
  min-width: 70px;
  padding: 2px;
}

.ts-status__val {
  font-weight: bold;
  text-align: center;
  min-width: 150px;
  padding: 2px;
  margin-bottom: 1px;
  background-color: rgba(0, 0, 0, 0.05);
}
</style>