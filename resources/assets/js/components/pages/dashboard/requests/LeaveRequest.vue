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

        <li v-if="!request">
          <button
            class="btn"
            @click="clearForm"
          >
            New
          </button>
        </li>

        <li v-if="!request">
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
            @click="approve"
          >
            Approve
          </button>
        </li>

        <li v-if="canApprove()">
          <button
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
        <li>
          <router-link :to="{ name: 'inbox' }">
            Inbox
          </router-link>
        </li>
      </ul>
    </template>

    <template slot="content">
      <!-- Leave Request From -->
      <form>
        <!-- Leave Type -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Leave Type <i class="field-required">*</i></label>
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
                Type is required.
              </div>
            </div>
          </div>
        </div>

        <!-- Display -->
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

        <!-- From Date -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">From <i class="field-required">*</i></label>
            <div class="col">
              <input
                v-model="form.fromDate"
                type="date"
                name="meta-from"
                :class="[{'is-invalid' : $v.form.fromDate.$error || $v.from.$error } ,'form-control']"
              >
              <div
                class="invalid-feedback"
                v-if="!$v.form.fromDate.required"
              >
                From date is required.
              </div>
              <div
                class="invalid-feedback"
                v-if="!$v.from.before"
              >
                From date must be before Till date.
              </div>
            </div>
            <div class="col">
              <input
                v-model="form.fromTime"
                type="time"
                name="meta-fromTime"
                :class="['form-control']"
                :disabled="form.display === 'days'"
              >
            </div>
          </div>
        </div>

        <!-- Till Date -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Till <i class="field-required">*</i></label>
            <div class="col">
              <input
                v-model="form.tillDate"
                type="date"
                name="meta-till"
                :disabled="form.display === 'hours'"
                :class="[{'is-invalid' : $v.form.tillDate.$error || $v.till.$error } ,'form-control']"
              >
              <div
                class="invalid-feedback"
                v-if="!$v.form.tillDate.required"
              >
                Till date is required.
              </div>
              <div
                class="invalid-feedback"
                v-if="!$v.till.after"
              >
                Till date must be after From date.
              </div>
            </div>
            <div class="col">
              <input
                v-model="form.tillTime"
                type="time"
                name="meta-tillTime"
                :class="['form-control']"
                :disabled="form.display === 'days'"
              >
            </div>
          </div>
        </div>

        <!-- Requested Days/Hours -->
        <div class="form-group">
          <div class="form-row">
            <label
              class="col-sm-2 col-form-label"
            >{{ form.display === 'days' ? 'Days Requested' : 'Hours Requested' }}</label>
            <div class="col-sm-10">
              <input
                :value="requested"
                type="text"
                class="form-control"
                disabled
              >
            </div>
          </div>
        </div>

        <!-- Remaining Days/Hours -->
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

        <!-- Approver -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Approver <i class="field-required">*</i></label>
            <div class="col-sm-10">
              <ts-select
                v-model="form.approver"
                :data="users"
                :multi-select="false"
                :class="[{'is-invalid' : $v.form.approver.$error } ,'form-control']"
                id="assigned_id"
                placeholder="please select the approver..."
                search-placeholder="search..."
              />
              <div
                class="invalid-feedback"
                v-if="!$v.form.approver.required"
              >
                Approver is required.
              </div>
            </div>
          </div>
        </div>

        <!-- Attachments -->
        <div class="form-group">          
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Attachments</label>
            <div class="col-sm-10">
              <div class="custom-file">
                <input
                  name="attachments"
                  id="attachments"
                  type="file"
                  class="custom-file-input"       
                  multiple           
                  @change="onAttachment"
                >
                <label
                  class="custom-file-label"
                  for="attachments"
                >choose files</label>
              </div>
            </div>            
          </div>          
        </div>

        <!-- Current Attachments -->
        <div 
          v-if="request" 
          class="form-group"
        >
          <div class="form-row">            
            <div class="col-sm-10 offset-2">              
              <button
                v-for="(item, index) in request.meta.attachments"
                :key="item.id"
                type="button"
                class="btn btn-primary mr-2"
                @click="downloadAttachment(item)"
              >
                <div class="d-flex flex-row align-items-center">
                  <ts-icon icon="cloud_download" />
                  <dir class="my-0 px-2 py-1">
                    Attachment-{{ index + 1 }}.{{ item.extension }}
                  </dir>                  
                </div>                
              </button>              
            </div>
          </div>
        </div>
      </form>
    </template>
  </app-main>
</template>

<script>
import moment from "moment";
import downloadjs from "downloadjs";
import { api } from "../../../../api/http";
import { mapActions, mapGetters } from "vuex";
import { required, helpers } from "vuelidate/lib/validators";
import pageMixin from "../../../../mixins/page-mixin";
import bsCustomFileInput from "bs-custom-file-input";

const before = (valueA, valueB) =>
  helpers.withParams(
    { type: "before", valueA: valueA, valueB: valueB },
    value => valueA < valueB
  );

const after = (valueA, valueB) =>
  helpers.withParams(
    { type: "after", valueA: valueA, valueB: valueB },
    value => valueA > valueB
  );

export default {
  name: "LeaveRequest",

  mixins: [pageMixin],

  props: ["id"],

  data() {
    return {
      form: {
        display: "days",
        daysRequested: "",
        approver: null,
        leaveType: "",
        fromDate: moment().format("YYYY-MM-DD"),
        fromTime: "00:00",
        tillDate: moment().format("YYYY-MM-DD"),
        tillTime: "00:00",
        attachments: []
      }
    };
  },

  validations() {
    return {
      from: {
        required,
        before: before(this.from, this.till)
      },
      till: {
        required,
        after: after(this.till, this.from)
      },
      form: {
        approver: { required },
        leaveType: { required },
        fromDate: { required },
        fromTime: { required },
        tillDate: { required },
        tillTime: { required }
      }
    };
  },

  watch: {
    request: function(val) {
      this.populateForm(val);
    },

    ["form.display"]: function(val) {
      if (val === "hours") {
        let from = this.form.fromDate ? moment(this.form.fromDate) : moment();
        this.form.fromDate = this.form.tillDate = from.format("YYYY-MM-DD");
      } else {
        this.form.fromTime = "00:00";
        this.form.tillTime = "00:00";
      }
    },

    ["form.fromDate"]: function(val) {
      if (this.form.display === "hours") {
        let from = moment(val);
        this.form.tillDate = from.format("YYYY-MM-DD");
      }
    },

    ["form.tillDate"]: function(val) {},

    ["form.daysRequested"]: function(val) {
      let from = this.form.fromDate ? moment(this.form.fromDate) : moment();
      this.form.tillDate = from.add(this.requested, "d").format("YYYY-MM-DD");
    }
  },

  computed: {
    ...mapGetters({
      userId: "user/getId",
      userCan: "user/can",
      getList: "resource/getList"
    }),

    request: function() {
      if (!this.id) return null;
      return this.getList("request")[0];
    },

    /**
     * List of the users with approve permission.
     */
    users: function() {
      return this.getList("user").map(item => {
        return { key: item.id, value: item.fullname };
      });
    },

    from: function() {
      return moment(`${this.form.fromDate} ${this.form.fromTime}`).format(
        "YYYY-MM-DD HH:mm"
      );
    },

    till: function() {
      return moment(`${this.form.tillDate} ${this.form.tillTime}`).format(
        "YYYY-MM-DD HH:mm"
      );
    },

    diff: function(){
        let from = moment(this.from);
        let till = moment(this.till).add(this.form.daysRequested, "d");
        return till.diff(from, "d", true);
    },

    /**
     * Calculated requested days/hours.
     */
    requested: function() {
      if (this.from && this.till) {
        let diff = 0;
        let from = moment(this.from);
        let till = moment(this.till).add(this.form.daysRequested, "d");

        if (this.form.display === "days") {
          diff = till.diff(from, "d", true);
          if (diff <= 0) return 0;
          return `${diff} Day`;
        } else {
          diff = till.diff(from, "hours", true);
          if (diff <= 0) return 0;
          let hour = Math.floor(diff);
          let mins = Math.floor((((diff - hour) * 60) / 100) * 100);
          return `${hour} Hour ${mins} Minute`;
        }
      } else return 0;
    }
  },

  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.clearForm();
      vm.loadAssets();
    });
  },

  beforeRouteLeave(to, from, next) {
    this.clear("user");
    this.clear("request");
    next();
  },

  mounted(){
    bsCustomFileInput.init();
  },

  methods: {
    ...mapActions({
      clear: "resource/clearResource",
      list: "resource/list",
      create: "resource/create",
      update: "resource/update"
    }),

    async loadAssets() {
      this.startLoading();

      let p1 = await this.list({
        resource: "user",
        query: { in: "user.roles.permissions.name,approve-leave" }
      });
      let p2 = this.id
        ? this.list({ resource: "request", query: { eq: `id,${this.id}` } })
        : new Promise(resolve => resolve());

      Promise.all([p1, p2]).finally(() => {
        this.stopLoading();
      });
    },

    populateForm(request) {
      if (!request) return;
      this.form.leaveType = request.meta.leave_type;
      this.form.fromDate = request.meta.from.slice(0, 10);
      this.form.fromTime = request.meta.from.slice(11, 16);
      this.form.tillDate = request.meta.till.slice(0, 10);
      this.form.tillTime = request.meta.till.slice(11, 16);
      this.form.approver = this.users.find(
        item => item.key === request.assigned_id
      );

      this.form.display = this.diff < 1 ? "hours" : "days";
    },

    /**
     * Check if current user can edit this request.
     */
    canEdit() {
      if (!this.request) return false;
      return this.request.user_id === this.userId;
    },

    /**
     * Check if current user can approve this request.
     */
    canApprove() {
      if (!this.request) return false;
      return this.request.assigned_id === this.userId && this.userCan('leave', ['full', 'approve']);
    },

    onAttachment(e) {
      this.form.attachments = [];
      let files = e.target.files;
      for (let i = 0; i < files.length; i++) {
        this.form.attachments.push(files[i]);
      }
    },

    async onSubmit(e) {
      e.preventDefault();
      e.target.disabled = true;

      // validate form
      this.$v.$touch();
      if (this.$v.$invalid) {
        e.target.disabled = false;
        return;
      }

      // populate form data
      let form = new FormData();  
      form.append("type", "leave");
      form.append("status", "submitted");
      form.append("assigned_id", this.form.approver.key);
      form.append("leave_type", this.form.leaveType);
      form.append("from", this.from);
      form.append("till", this.till);

      for (let i = 0; i < this.form.attachments.length; i++) {
        let file = this.form.attachments[i];
        form.append(`attachments[${i}]`, file);
      }

      this.create({ resource: "request", data: form, hasAttachments: true })
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

    onSave(e, status = "submitted") {
      e.preventDefault();
      e.target.disabled = true;

      // validate form
      this.$v.$touch();
      if (this.$v.$invalid) {
        e.target.disabled = false;
        return;
      }

      // populate form data
      let form = new FormData();          
      form.append("_method", "PUT");
      form.append("type", "leave");
      form.append("status", status);
      form.append("assigned_id", this.form.approver.key);
      form.append("leave_type", this.form.leaveType);
      form.append("from", this.from);
      form.append("till", this.till);

      for (let i = 0; i < this.form.attachments.length; i++) {
        let file = this.form.attachments[i];
        form.append(`attachments[${i}]`, file);
      }

      this.update({ resource: "request", id: this.id, data: form, method: "POST", hasAttachments: true })
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

    approve(e){
      e.preventDefault();
      e.target.disabled = true;

      api.update({url:`/api/request/approve/${this.id}`}).then(()=>{
        this.showMessage(`Request approved successfuly.`, "success");        
      })
      .catch((error)=>{
        this.showMessage(error.message, "danger");
      })
      .finally(()=>{
        e.target.disabled = false;
      });
    },

    reject(e){
      e.preventDefault();
      e.target.disabled = true;

      api.update({url:`/api/request/reject/${this.id}`}).then(()=>{
        this.showMessage(`Request rejected successfuly.`, "success");        
      })
      .catch((error)=>{
        this.showMessage(error.message, "danger");
      })
      .finally(()=>{
        e.target.disabled = false;
      });
    },

    clearForm() {
      this.form.display = "days";
      this.form.approver = null;
      this.form.daysRequested = "";
      this.form.leaveType = "";
      this.form.fromDate = moment().format("YYYY-MM-DD");
      this.form.fromTime = "00:00";
      this.form.tillDate = moment().format("YYYY-MM-DD");
      this.form.tillTime = "00:00";
      this.$v.form.$reset();
    },

    onCancel(e) {
      this.$router.go(-1);
    },

    downloadAttachment(file){
      api.get({url:`/api/request/attachment/${this.request.id}/${file.id}`, responseType: "blob" })
      .then(res => {
        downloadjs(res.data, `${file.id}.${file.extension}`);
      });
    }
  }
};
</script>

<style>
</style>
