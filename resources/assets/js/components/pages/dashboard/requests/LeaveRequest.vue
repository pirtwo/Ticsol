<template>
  <app-main
    :scrollbar="true"
    :loading="isLoading"
    padding="p-5"
  >
    <template slot="toolbar" />

    <template slot="drawer">
      <ul class="v-menu">
        <li class="menu-title">
          Actions
        </li>

        <li v-if="!currentReq">
          <button 
            class="btn"
            @click="clearForm"
          >
            New
          </button>
        </li>
        
        <li v-if="!currentReq">
          <button
            class="btn"
            @click="onSubmit"
          >
            Submit
          </button>
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
            @click="onSave($event, 'suspended')"
          >
            Suspend
          </button>
        </li>

        <li v-if="canApprove()">
          <button
            class="btn"
            @click="onSave($event, 'approved')"
          >
            Approve
          </button>
        </li>

        <li v-if="canApprove()">
          <button
            class="btn"
            @click="onSave($event, 'rejected')"
          >
            Reject
          </button>
        </li>

        <li>
          <button class="btn">
            Cancel
          </button>
        </li>

        <li>
          <button class="btn">
            Print
          </button>
        </li>

        <li class="menu-title">
          Links
        </li>
        <li>
          <router-link :to="{ name: 'jobList' }">
            Request History
          </router-link>
        </li>
      </ul>
    </template>

    <template slot="content">
      <form>
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Leave Type</label>
            <div class="col-sm-10">
              <select
                v-model="form.leaveType"
                name="meta-leave_type"
                :class="[{'is-invalid' : $v.form.leaveType.$error } ,'custom-select']"
              >
                <option selected>
                  please select leave type
                </option>
                <option value="annual">
                  Annual
                </option>
                <option value="long service">
                  Long Service
                </option>
                <option value="sick">
                  Sick
                </option>
                <option value="bereavement">
                  Bereavement
                </option>
                <option value="maternity/paternity">
                  Maternity/Paternity
                </option>
                <option value="study">
                  Study
                </option>
                <option value="other">
                  Other
                </option>
              </select>
              <div
                class="invalid-feedback"
                v-if="!$v.form.leaveType.required"
              >
                Please enter a type.
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Display</label>
            <div class="col-sm-10">
              <div class="custom-control custom-radio custom-control-inline">
                <input
                  v-model="form.display"
                  value="days"
                  type="radio"
                  id="display1"
                  name="display"
                  class="custom-control-input"
                >
                <label
                  class="custom-control-label"
                  for="display1"
                >Days</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input
                  v-model="form.display"
                  value="hours"
                  type="radio"
                  id="display2"
                  name="display"
                  class="custom-control-input"
                >
                <label
                  class="custom-control-label"
                  for="display2"
                >Hours</label>
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">From</label>
            <div class="col">
              <input
                v-model="form.fromDate"
                type="date"
                name="meta-from"
                :class="[{'is-invalid' : $v.form.fromDate.$error } ,'form-control']"
              >
              <div
                class="invalid-feedback"
                v-if="!$v.form.fromDate.required"
              >
                Please enter a from date.
              </div>
            </div>
            <div class="col">
              <input
                v-model="form.fromTime"
                type="time"
                name="meta-fromTime"
                :class="['form-control']"
              >
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Till</label>
            <div class="col">
              <input
                v-model="form.tillDate"
                type="date"
                name="meta-till"
                :disabled="form.display === 'hours'"
                :class="[{'is-invalid' : $v.form.tillDate.$error } ,'form-control']"
              >
              <div
                class="invalid-feedback"
                v-if="!$v.form.tillDate.required"
              >
                Please enter a till date.
              </div>
            </div>
            <div class="col">
              <input
                v-model="form.tillTime"
                type="time"
                name="meta-tillTime"
                :class="['form-control']"
              >
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label
              class="col-sm-2 col-form-label"
            >{{ form.display === 'days' ? 'Days Requested' : 'Hours Requested' }}</label>
            <div class="col-sm-10">
              <input
                :value="requested"
                type="number"
                min="0"
                class="form-control"
                disabled
              >
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Days Remaining</label>
            <div class="col-sm-10">
              <input
                type="text"
                class="form-control"
              >
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Approver</label>
            <div class="col-sm-10">
              <ts-select
                v-model="form.approver"
                :data="users"
                :multi-select="false"
                :class="[{'is-invalid' : $v.form.approver.$error } ,'form-control']"
                id="assigned_id"
                placeholder="please selet the approver..."
                search-placeholder="search..."
              />
              <div
                class="invalid-feedback"
                v-if="!$v.form.approver.required"
              >
                Please select a approver.
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Attachments</label>
            <div class="col-sm-10">
              <div class="custom-file">
                <input
                  name="Attachments"
                  id="customFile"
                  type="file"
                  class="custom-file-input"
                >
                <label
                  class="custom-file-label"
                  for="customFile"
                >choose files</label>
              </div>
            </div>
          </div>
        </div>
      </form>
    </template>
  </app-main>
</template>

<script>
import moment from "moment";
import { mapActions, mapGetters } from "vuex";
import { required } from "vuelidate/lib/validators";
import pageMixin from "../../../../mixins/page-mixin";

export default {
  name: "LeaveRequest",

  mixins: [pageMixin],

  props: ["id"],

  data() {
    return {
      currentReq: null,
      form: {
        display: "days",
        daysRequested: "",
        approver: null,
        leaveType: "",
        fromDate: moment().format("YYYY-MM-DD"),
        fromTime: "00:00",
        tillDate: moment().format("YYYY-MM-DD"),
        tillTime: "00:00",
        attachments: ""
      }
    };
  },

  validations: {
    form: {
      approver: { required },
      leaveType: { required },
      fromDate: { required },
      fromTime: { required },
      tillDate: { required },
      tillTime: { required }
    }
  },

  watch: {
    ["form.display"]: function(val) {
      console.log(val);
      if (val === "hours") {
        let from = this.form.fromDate ? moment(this.form.fromDate) : moment();
        this.form.fromDate = this.form.tillDate = from.format("YYYY-MM-DD");
      }
    },

    ["form.fromDate"]: function(val) {
      if (this.form.display === "hours") {
        let from = moment(val);
        this.form.tillDate = from.format("YYYY-MM-DD");
      }
    },

    ["form.tillDate"]: function(val) {
    },

    ["form.daysRequested"]: function(val) {
      let from = this.form.fromDate ? moment(this.form.fromDate) : moment();
      this.form.tillDate = from.add(this.requested, "d").format("YYYY-MM-DD");
    }
  },

  computed: {
    ...mapGetters({
      userId: "user/getId",
      getList: "resource/getList"
    }),

    users: function() {
      return this.getList("user").map(item => {
        return { key: item.id, value: item.name };
      });
    },

    from: function() {
      return `${this.form.fromDate} ${
        this.form.fromTime ? this.form.fromTime : "00:00"
      }`;
    },

    till: function() {
      return `${this.form.tillDate} ${
        this.form.tillTime ? this.form.tillTime : "00:00"
      }`;
    },

    requested: function() {
      if (this.from && this.till) {
        let from = moment(this.from);
        let till = moment(this.till).add(this.form.daysRequested, "d");
        return this.form.display === "days"
          ? till.diff(from, "d")
          : till.diff(from, "hours");
      } else return 0;
    }
  },

  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.clearForm();
    });
  },

  beforeRouteLeave(to, from, next) {
    this.clear("user");
    this.clear("request");
    next();
  },

  mounted() {
    this.startLoading();
    let p1;
    let p2;
    if (this.id) {
      p1 = this.show({ resource: "request", id: this.id }).then(data => {
        this.currentReq = data;
      });
    } else {
      p1 = new Promise(resolve => resolve());
    }
    p2 = this.list({ resource: "user" });

    Promise.all([p1, p2])
      .then(() => {
        if (this.id) {
          this.form.leaveType = this.currentReq.meta.leave_type;
          this.form.fromDate = this.currentReq.meta.from.slice(0, 10);
          this.form.fromTime = this.currentReq.meta.from.slice(11, 16);
          this.form.tillDate = this.currentReq.meta.till.slice(0, 10);
          this.form.tillTime = this.currentReq.meta.till.slice(11, 16);
          this.form.approver = this.users.find(
            item => item.key === this.currentReq.assigned_id
          );
        }
        this.stopLoading();
      })
      .catch(error => {
        console.log(error);
        this.stopLoading();
      });
  },

  methods: {
    ...mapActions({
      clear: "resource/clearResource",
      list: "resource/list",
      show: "resource/show",
      create: "resource/create",
      update: "resource/update",
    }),

    canEdit(){
      if(!this.currentReq) return false;
      return this.currentReq.user_id === this.userId;
    },

    canApprove(){
      if(!this.currentReq) return false;
      return this.currentReq.assigned_id === this.userId;
    },

    onSubmit(e) {
      e.preventDefault();
      e.target.disabled = true;

      // validate form
      this.$v.$touch();
      if (this.$v.$invalid) {
        e.target.disabled = false;
        return;
      }

      // populate form data
      let form = { meta: {} };
      form.type = "leave";
      form.status = "submitted";
      form.assigned_id = this.form.approver.key;
      form.meta.leave_type = this.form.leaveType;
      form.meta.from = this.from;
      form.meta.till = this.till;

      this.create({ resource: "request", data: form })
        .then(respond => {
          e.target.disabled = false;
          this.showMessage(`Leave request created successfuly.`, "success");
        })
        .catch(error => {
          console.log(error.response);
          e.target.disabled = false;
          this.showMessage(error.message, "danger");
          this.$formFeedback(error.response.data.errors);
        });
    },

    onSave(e, status = "submitted"){
      e.preventDefault();
      e.target.disabled = true;

      // validate form
      this.$v.$touch();
      if (this.$v.$invalid) {
        e.target.disabled = false;
        return;
      }

      // populate form data
      let form = { meta: {} };
      form.type = "leave";
      form.status = status;
      form.assigned_id = this.form.approver.key;
      form.meta.leave_type = this.form.leaveType;
      form.meta.from = this.from;
      form.meta.till = this.till;

      this.update({ resource: "request", id: this.id, data: form })
        .then(respond => {
          e.target.disabled = false;
          this.showMessage(`Leave request ${status} successfuly.`, "success");
        })
        .catch(error => {
          console.log(error.response);
          e.target.disabled = false;
          this.showMessage(error.message, "danger");
          this.$formFeedback(error.response.data.errors);
        });
    },

    clearForm() {
      this.form.display = "days";
      this.currentReq = null;
      this.form.approver = null;
      this.form.daysRequested = "";
      this.form.leaveType= "";
      this.form.fromDate= moment().format("YYYY-MM-DD");
      this.form.fromTime= "00:00";
      this.form.tillDate= moment().format("YYYY-MM-DD");
      this.form.tillTime= "00:00";
      this.$v.form.$reset();
    }
  }
};
</script>

<style>
</style>
