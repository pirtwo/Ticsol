<template>
  <app-main
    :scrollbar="true"
    :loading="isLoading"
    padding="p-2"
  >
    <template slot="toolbar">
      <ts-datescroller
        v-if="!id"
        v-model="week"
        @change="onDateChange"
        range="Week"
      />
    </template>

    <template slot="drawer">
      <ul class="v-menu">
        <li class="menu-title">
          Actions
        </li>
        <li v-if="canEdit()">
          <button
            class="btn"
            @click="onSave"
          >
            Save
          </button>
        </li>
        <li v-if="canEdit()">
          <button
            class="btn"
            @click="approverModal = true"
          >
            Submit
          </button>
        </li>
        <li v-if="canEdit()">
          <button
            type="button"
            class="btn"
            @click="generateModal = true"
          >
            Generate
          </button>
        </li>
        <li v-if="canApprove()">
          <button
            type="button"
            class="btn"
            @click="approve"
          >
            Approve
          </button>
        </li>
        <li v-if="canApprove()">
          <button
            type="button"
            class="btn"
            @click="reject"
          >
            Reject
          </button>
        </li>
        <li>
          <button
            class="btn"
            @click="onCancel"
          >
            Cancel
          </button>
        </li>
        <li class="menu-title">
          Links
        </li>
        <li v-if="this.timesheet">
          <router-link
            class="btn btn-link"
            :to="{ name: 'commentList', params : { entity: 'timesheet', id: this.timesheet.id } }"
          >
            Comments
            <ts-badge class="badge-light ml-auto">
              {{ this.timesheet.commentsCount }}
            </ts-badge>
          </router-link>
        </li>
      </ul>
    </template>

    <template slot="content">
      <!-- Validation Summary -->
      <div
        v-if="$v.gridData.$error"
        class="alert alert-danger"
        role="alert"
      >
        <b class="alert-heading">Fix these problems:</b>
        <ul>
          <li v-if="!$v.gridData.required">
            Timesheets are required.
          </li>
          <template v-for="(item, index) in $v.gridData.$each.$iter">
            <li
              v-if="item.$invalid"
              :key="index"
            >
              <div>Row #{{ 1 + (+index) }}:</div>
              <div v-if="!item.date.required">
                Day is required.
              </div>
              <div v-if="!item.job.required">
                Job is required.
              </div>
              <div v-if="!item.startTime.required">
                Start time is required.
              </div>
              <div v-if="!item.endTime.required">
                End time is required.
              </div>
              <div v-if="!item.break_length.required">
                Break length is required.
              </div>
            </li>
          </template>
        </ul>
        <hr>
        <p
          class="mb-0"
        >
          Your timesheet list have some errors. Fix them to be able to save the timesheet.
        </p>
      </div>

      <!-- Timesheets Grid View -->
      <ts-grid
        v-model="gridData"
        :columns="columns"
        :has-toolbar="false"
      >
        <template slot-scope="{ item }">
          <td>
            <template v-if="item.date">
              {{ item.date.value }}
            </template>
          </td>
          <td>
            <router-link
              v-if="item.job"
              class="btn btn-sm btn-link"
              :to="{ name : 'jobDetails', params : { id: item.job.key } }"
            >
              <span>{{ item.job.value }}</span>
            </router-link>
          </td>
          <td>{{ item.billable ? 'Yes' : 'No' }}</td>
          <td>
            <template v-if="item.startTime">
              {{ item.startTime }}
            </template>
          </td>
          <td>
            <template v-if="item.endTime">
              {{ item.endTime }}
            </template>
          </td>
          <td>
            <template v-if="item.break_length">
              {{ item.break_length.slice(0,5) }}
            </template>
          </td>
          <td>{{ workHours(item).slice(0,5) }}</td>
        </template>
        <template
          slot="grid-modal"
          slot-scope="{ item }"
        >
          <div class="p-2">
            <!-- Day -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">
                  Day
                  <i class="field-required">*</i>
                </label>
                <div class="col-sm-10">
                  <ts-select
                    class="form-control"
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

            <!-- Job -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">
                  Job
                  <i class="field-required">*</i>
                </label>
                <div class="col-sm-10">
                  <ts-select
                    class="form-control"
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

            <!-- Start -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">
                  Start
                  <i class="field-required">*</i>
                </label>
                <div class="col-sm-10">
                  <input
                    class="form-control"
                    v-model="item.startTime"
                    type="time"
                  >
                </div>
              </div>
            </div>

            <!-- End -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">
                  End
                  <i class="field-required">*</i>
                </label>
                <div class="col-sm-10">
                  <input
                    class="form-control"
                    v-model="item.endTime"
                    type="time"
                  >
                </div>
              </div>
            </div>

            <!-- Break -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">
                  Break
                  <i class="field-required">*</i>
                </label>
                <div class="col-sm-10">
                  <ts-timepicker
                    class="form-control"
                    v-model="item.break_length"
                  />
                </div>
              </div>
            </div>

            <!-- Billable -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">Billable</label>
                <div class="col-sm-10">
                  <div class="custom-control custom-checkbox">
                    <input
                      v-model="item.billable"
                      type="checkbox"
                      class="custom-control-input"
                      id="billable"
                      value="billable"
                    >
                    <label
                      class="custom-control-label"
                      for="billable"
                    >Billable</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>

        <template slot="footer">
          <tr>
            <td
              v-if="gridData.length > 0"
              colspan="8"
              class="table-status"
            >
              <div class="ts-status d-flex flex-column">
                <div class="d-flex flex-row justify-content-end">
                  <div class="ts-status__lable">
                    STATUS
                  </div>
                  <div class="ts-status__val">
                    {{ timesheetStatus }}
                  </div>
                </div>
                <div class="d-flex flex-row justify-content-end">
                  <div class="ts-status__lable">
                    TOTAL
                  </div>
                  <div
                    class="ts-status__val"
                  >
                    {{ this.totalHours.slice(0,5) == '00:00' ? '' : this.totalHours.slice(0,5) }}
                  </div>
                </div>
              </div>
            </td>
          </tr>
        </template>
      </ts-grid>

      <!-- Approver Modal -->
      <ts-modal
        title="Select Approver"
        :show.sync="approverModal"
        @hidden="comment = ''"
      >
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-12 col-form-lable">Approver</label>
            <div class="col-sm-12">
              <ts-select
                class="form-control"
                v-model="approver"
                :data="users"
                :class="[{'is-invalid' : $v.approver.$error } ,'form-control']"
                id="assigned_id"
                placeholder="Select approver..."
                search-placeholder="search..."
              />
              <div
                class="invalid-feedback"
                v-if="!$v.approver.required"
              >
                Approver is required.
              </div>
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
          <button
            type="button"
            class="btn btn-light"
            @click="approverModal = false"
          >
            Cancel
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="onSubmit"
          >
            Submit
          </button>
        </template>
      </ts-modal>

      <!-- Generate Modal -->
      <ts-modal
        title="Select Schedules"
        :show.sync="generateModal"
      >
        <ts-table
          class="table-responsive"
          v-model="selectedSchedules"
          :data="mapedScheduleElements"
          :headers="scheduleColumns"
          :selection="true"
          orderby="Day"
          order="asc"
        >
          <template
            slot="header"
            slot-scope="{item}"
          >
            <div>{{ item.value }}</div>
          </template>
          <template
            slot="body"
            slot-scope="{item}"
          >
            <td>
              <template v-if="item.date">
                {{ item.date.value }}
              </template>
            </td>
            <td>
              <router-link
                v-if="item.job"
                class="btn btn-sm btn-link"
                :to="{ name : 'jobDetails', params : { id: item.job.key } }"
              >
                <span>{{ item.job.value }}</span>
              </router-link>
            </td>
            <td>{{ item.billable ? 'Yes' : 'No' }}</td>
            <td>
              <template v-if="item.startTime">
                {{ item.startTime }}
              </template>
            </td>
            <td>
              <template v-if="item.endTime">
                {{ item.endTime }}
              </template>
            </td>
          </template>
        </ts-table>
        <template slot="footer">
          <button
            type="button"
            class="btn btn-light"
            @click="generateModal = false"
          >
            Cancel
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="onGenerate"
          >
            Generate
          </button>
        </template>
      </ts-modal>
    </template>
  </app-main>
</template>

<script>
import moment from "moment";
import { api } from "../../../../api/http";
import { required, requiredIf } from "vuelidate/lib/validators";
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
      week: {
        start: moment(),
        end: moment()
      },
      gridData: [],
      approver: null,
      approverModal: false,
      generateModal: false,
      selectedSchedules: [],
      comment: "",
      columns: [
        { key: "day", value: "Day" },
        { key: "job", value: "Job" },
        { key: "billable", value: "Billable" },
        { key: "start-time", value: "Start" },
        { key: "end-time", value: "End" },
        { key: "break", value: "Break" },
        { key: "total", value: "Total" }
      ],
      scheduleColumns: [
        {
          value: "Day",
          orderby: f => {
            return f.date.key;
          }
        },
        {
          value: "Job",
          orderby: f => {
            return f.job.value;
          }
        },
        {
          value: "Billable",
          orderby: f => {
            return f.billable;
          }
        },
        {
          value: "Start",
          orderby: f => {
            return f.startTime;
          }
        },
        {
          value: "End",
          orderby: f => {
            return f.endTime;
          }
        }
      ]
    };
  },

  validations: {
    approver: {
      required: requiredIf(function() {
        return this.approverModal;
      })
    },
    gridData: {
      required,
      $each: {
        date: { required },
        job: { required },
        startTime: { required },
        endTime: { required },
        break_length: { required }
      }
    }
  },

  watch: {
    timesheet: function(val) {
      this.gridData = this.mapedTimesheetElements;
      if (val)
        this.approver = this.users.find(
          item => item.key == val.request.assigned_id
        );
    }
  },

  computed: {
    ...mapGetters({
      userId: "user/getId",
      userCan: "user/can",
      getList: "resource/getList"
    }),

    jobs: function() {
      return this.getList("job").map(item => {
        return { key: item.id, value: item.title };
      });
    },

    users: function() {
      return this.getList("user").map(item => {
        return { key: item.id, value: item.fullname };
      });
    },

    schedules: function() {
      return this.getList("schedule");
    },

    timesheet: function() {
      return this.getList("timesheet")[0];
    },

    timesheetStatus: function() {
      return this.timesheet
        ? this.timesheet.request.status.toUpperCase()
        : "--";
    },

    mapedTimesheetElements: function() {
      if (!this.timesheet) return [];
      return this.mapElementsForView(this.timesheet.schedules);
    },

    mapedScheduleElements: function() {
      if (this.schedules.length == 0) return [];
      return this.mapElementsForView(this.schedules);
    },

    weekDays: function() {
      let day = this.start
        ? moment(this.start, ["YYYY-MM-DD"])
        : this.week.start.clone();
      let days = [];
      for (let i = 0; i < 7; i++) {
        let item = {};
        item.key = i;
        item.value = day.format("ddd DD MMM");
        item.day = day.clone();
        days.push(item);
        day.add(1, "d");
      }
      return days;
    },

    weekYear: function() {
      if (this.start) return moment(this.start, ["YYYY-MM-DD"]).year();
      return this.week.start.year();
    },

    weekNumber() {
      if (this.start) return moment(this.start, ["YYYY-MM-DD"]).week();
      return this.week.start.week();
    },

    weekStart: function() {
      if (this.start)
        return moment(this.start, ["YYYY-MM-DD"])
          .startOf("d")
          .format("YYYY-MM-DDTHH:mm:ss");
      return this.week.start.startOf("d").format("YYYY-MM-DDTHH:mm:ss");
    },

    weekEnd: function() {
      if (this.end)
        return moment(this.end, ["YYYY-MM-DD"])
          .endOf("d")
          .format("YYYY-MM-DDTHH:mm:ss");
      return this.week.end.endOf("d").format("YYYY-MM-DDTHH:mm:ss");
    },

    totalHours: function() {
      let total = "00:00:00";
      let itemTotal = "";
      this.gridData.forEach(item => {
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
      vm.initView(vm);
    });
  },

  beforeRouteLeave(to, from, next) {
    this.clear("job");
    this.clear("user");
    this.clear("schedule");
    this.clear("timesheet");
    next();
  },

  methods: {
    ...mapActions({
      clear: "resource/clearResource",
      list: "resource/list",
      show: "resource/show",
      create: "resource/create",
      update: "resource/update"
    }),

    async initView(vm) {
      vm.startLoading();
      vm.status = "-";
      vm.approver = null;

      await vm.loadAssets();
      await vm.loadTimesheet();
      vm.stopLoading();
    },

    loadAssets() {
      return new Promise((resolve, reject) => {
        let loadJobs = this.list({ resource: "job" });
        let loadUsers = this.list({
          resource: "user",
          query: { in: "user.roles.permissions.name,approve-timesheet" }
        });

        Promise.all([loadJobs, loadUsers])
          .then(() => {
            console.log("assets loaded.");
            resolve();
          })
          .catch(error => reject(error));
      });
    },

    loadTimesheet() {
      return new Promise((resolve, reject) => {
        let p1 = this.list({
          resource: "timesheet",
          query: this.$queryBuilder(
            null,
            null,
            ["request", "schedules.job"],
            this.id
              ? [{ opt: "eq", col: "id", val: this.id }]
              : [
                  { opt: "eq", col: "creator_id", val: this.userId },
                  { opt: "eq", col: "week_number", val: this.weekNumber }
                ]
          )
        });

        let p2 = this.list({
          resource: "schedule",
          query: this.$queryBuilder(
            null,
            null,
            ["job"],
            [
              { opt: "eq", col: "user_id", val: this.userId },
              { opt: "eq", col: "event_type", val: "scheduled" },
              {
                opt: "inRange",
                col: "",
                val: `${this.weekStart},${this.weekEnd}`
              }
            ]
          )
        });

        Promise.all([p1, p2])
          .then(() => {
            this.gridData = this.mapedTimesheetElements;
            resolve();
          })
          .catch(error => {
            reject(error);
          });
      });
    },

    /**
     * Maps timesheet or schedule items for current grid view.
     */
    mapElementsForView(list) {
      let day = null;
      let items = [];

      // iterate throw week days.
      for (let i = 0; i < 7; i++) {
        day = this.weekDays[i].day;
        let dayStart = day.clone().startOf("day");
        let dayEnd = day.clone().endOf("day");

        // for each elm in list check:
        // if elm is in the current day range, then:
        // create new timesheet based on elm and add it to the list.
        list.forEach(item => {
          let start = moment(item.start, ["YYYY-MM-DD HH:mm:ss"]);
          let end = moment(item.end, ["YYYY-MM-DD HH:mm:ss"]);

          if (
            start.isBetween(dayStart, dayEnd, null, "[]") ||
            end.isBetween(dayStart, dayEnd, null, "[]") ||
            (dayStart.isBetween(start, end, null, "[]") ||
              dayEnd.isBetween(start, end, null, "[]"))
          ) {
            let timesheet = {};
            timesheet.id = uuid();
            timesheet.date = this.weekDays[i];
            timesheet.job = this.jobs.find(job => job.key == item.job.id);
            timesheet.startTime = item.start.slice(11, 16);
            timesheet.endTime = item.end.slice(11, 16);
            timesheet.billable = item.billable;
            timesheet.break_length = item.break_length;
            items.push(timesheet);
          }
        });
      }

      return items;
    },

    onDateChange() {
      // clear validation messages
      this.$v.gridData.$reset();

      this.startLoading();
      this.loadTimesheet().then(() => this.stopLoading());
    },

    canEdit() {
      if (!this.id) return true;
      if (this.timesheet) {
        return this.timesheet.creator_id == this.userId;
      } else return false;
    },

    canApprove() {
      if (!this.timesheet) {
        return false;
      } else
        return (
          this.timesheet.request.assigned_id == this.userId &&
          this.userCan("timesheet", ["full", "approve"])
        );
    },

    approve(e) {
      e.preventDefault();
      e.target.disabled = true;

      api
        .update({ url: `/api/timesheet/approve/${this.id}` })
        .then(() => {
          this.showMessage(`Timesheet <b>approved</b> successfuly.`, "success");
        })
        .catch(error => {
          this.showMessage(error.message, "danger");
        })
        .finally(() => {
          e.target.disabled = false;
        });
    },

    reject(e) {
      api
        .update({ url: `/api/timesheet/reject/${this.id}` })
        .then(() => {
          this.showMessage(`Timesheet <b>rejected</b> successfuly.`, "success");
        })
        .catch(error => {
          this.showMessage(error.message, "danger");
        })
        .finally(() => {
          e.target.disabled = false;
        });
    },

    onGenerate() {
      this.generateModal = false;

      let list = [...this.gridData.slice(), ...this.selectedSchedules.slice()];

      let newList = list.map(item => Object.assign({}, item));
      newList = newList.map(item => {
        item.id = uuid();
        return item;
      });

      //console.log(JSON.stringify(newList));

      this.selectedSchedules = null;
      this.gridData = newList;
    },

    onSubmit(e) {
      e.preventDefault();
      e.target.disabled = true;

      this.$v.$touch();
      if (this.$v.$invalid) {
        e.target.disabled = false;
        return;
      }

      let p = null;
      let timesheetId = null;
      if (this.timesheet) {
        p = this.updateTimesheet("submitted", this.comment).then(data => {
          timesheetId = data.id;
        });
      } else {
        p = this.createTimesheet("submitted", this.comment).then(data => {
          timesheetId = data.id;
        });
      }

      p.then(() => {
        if (this.comment && timesheetId) {
          this.createComment(timesheetId, this.comment);
        }
      }).finally(() => {
        e.target.disabled = false;
        this.approverModal = false;
      });
    },

    onSave(e) {
      e.preventDefault();
      e.target.disabled = true;

      this.$v.$touch();
      if (this.$v.$invalid) {
        e.target.disabled = false;
        return;
      }

      let p = null;
      if (this.timesheet) {
        p = this.updateTimesheet("draft");
      } else {
        p = this.createTimesheet("draft");
      }

      p.finally(() => {
        e.target.disabled = false;
      });
    },

    onCancel() {
      this.$router.go(-1);
    },

    createComment(timesheetId, comment) {
      return new Promise((resolve, reject) => {
        this.create({
          resource: "comment",
          data: {
            entity: "timesheet",
            parent_id: this.parentId,
            body: comment,
            timesheet_id: timesheetId
          }
        })
          .then(() => {
            console.log("comment creataed successfuly...");
            this.comment = "";
            resolve();
          })
          .catch(error => {
            console.log(error);
            this.showMessage(error.message, "danger");
            reject();
          });
      });
    },

    createTimesheet(status) {
      return new Promise((resolve, reject) => {
        this.create({
          resource: "timesheet",
          data: {
            locale: window.navigator.language,
            year: this.weekYear,
            status: status,
            assigned_id: this.approver ? this.approver.key : null,
            week_start: this.weekStart.slice(0, 10),
            week_end: this.weekEnd.slice(0, 10),
            week_number: this.weekNumber,
            total_hours: this.totalHours,
            items: this.getTimesheetItems()
          }
        })
          .then(data => {
            this.showMessage(
              `${
                status == "draft"
                  ? "Draft created successfuly."
                  : "Timesheet submitted successfuly."
              }`,
              "success"
            );

            resolve(data);
          })
          .catch(error => {
            this.showMessage(error.message, "danger");
            reject(error);
          });
      });
    },

    updateTimesheet(status) {
      return new Promise((resolve, reject) => {
        this.update({
          resource: "timesheet",
          id: this.timesheet.id,
          data: {
            status: status,
            assigned_id: this.approver ? this.approver.key : null,
            total_hours: this.totalHours,
            items: this.getTimesheetItems()
          }
        })
          .then(data => {
            this.showMessage(`Timesheet updated successfuly.`, "success");
            resolve(data);
          })
          .catch(error => {
            this.showMessage(error.message, "danger");
            reject(error);
          });
      });
    },

    updateRequest(status) {
      this.update({
        resource: "request",
        id: this.timesheet.request.id,
        data: {
          status: status
        }
      }).then(() => {
        this.showMessage(`Timesheet ${status} successfuly.`, "success");
      });
    },

    getTimesheetItems() {
      let items = this.gridData.map(item => {
        return {
          job_id: item.job.key,
          user_id: this.userId,
          type: "timesheet",
          event_type: "scheduled",
          status: "tentative",
          billable: item.billable ? true : false,
          break_length: item.break_length,
          start: `${item.date.day.format("YYYY-MM-DD")} ${item.startTime}`,
          end: `${item.date.day.format("YYYY-MM-DD")} ${item.endTime}`
        };
      });
      console.log(items);
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