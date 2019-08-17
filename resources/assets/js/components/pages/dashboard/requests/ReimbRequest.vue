<template>
  <app-main
    :scrollbar="true"
    :loading="isLoading"
    padding="p-5"
  >
    <template slot="toolbar" />

    <template slot="drawer">
      <ul class="v-menu">
        <!-- Actions -->
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
          <button class="btn">
            Print
          </button>
        </li>

        <!-- Links -->
        <li class="menu-title">
          Links
        </li>
        <li>
          <router-link :to="{ name: 'inbox' }">
            History
          </router-link>
        </li>
      </ul>
    </template>

    <template slot="content">
      <!-- Reimbursement Form -->
      <form>
        <!-- Details -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Details <i class="field-required">*</i></label>
            <div class="col-sm-10">
              <textarea
                v-model="form.details"
                name="meta-details"
                id="meta-details"
                :class="[{'is-invalid' : $v.form.details.$error } ,'form-control']"
                rows="5"
              />
              <div
                class="invalid-feedback"
                v-if="!$v.form.details.required"
              >
                Description is required.
              </div>
              <div
                class="invalid-feedback"
                v-if="!$v.form.details.maxLength"
              >
                Description is too long, must be between 1-{{ $v.form.details.$params.maxLength.max }} characters.
              </div>
            </div>
          </div>
        </div>

        <!-- Amount -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Amount <i class="field-required">*</i></label>
            <div class="col-sm-10">
              <input
                v-model="form.amount"
                name="meta-amount"
                id="meta-amount"
                type="text"
                :class="[{'is-invalid' : $v.form.amount.$error } ,'form-control']"
              >
              <div
                class="invalid-feedback"
                v-if="!$v.form.amount.required"
              >
                Amount is required.
              </div>
              <div
                class="invalid-feedback"
                v-if="!$v.form.amount.decimal"
              >
                Value must be a number.
              </div>
              <div
                class="invalid-feedback"
                v-if="!$v.form.amount.between"
              >
                Value must be btween
                {{ $v.form.amount.$params.between.min }} and
                {{ $v.form.amount.$params.between.max }}
              </div>
            </div>
          </div>
        </div>

        <!-- Tax -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Incl/Excl <i class="field-required">*</i></label>
            <div class="col-sm-10">
              <div class="custom-control custom-radio custom-control-inline">
                <input
                  v-model="form.tax"
                  type="radio"
                  id="meta-tax1"
                  name="meta-tax"
                  value="Incl"
                  class="custom-control-input"
                  checked
                >
                <label
                  class="custom-control-label"
                  for="meta-tax1"
                >Incl</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input
                  v-model="form.tax"
                  type="radio"
                  id="meta-tax2"
                  name="meta-tax"
                  value="Excl"
                  class="custom-control-input"
                >
                <label
                  class="custom-control-label"
                  for="meta-tax2"
                >Excl</label>
              </div>
            </div>
          </div>
        </div>

        <!-- Date -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Date <i class="field-required">*</i></label>
            <div class="col-sm-10">
              <input
                v-model="form.date"
                name="meta-date"
                id="meta-date"
                type="date"
                :class="[{'is-invalid' : $v.form.date.$error } ,'form-control']"
              >
              <div
                class="invalid-feedback"
                v-if="!$v.form.date.required"
              >
                Date is required.
              </div>
            </div>
          </div>
        </div>

        <!-- Job -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Expense To <i class="field-required">*</i></label>
            <div class="col-sm-10">
              <ts-select
                v-model="form.job"
                :data="jobs"
                :class="[{'is-invalid' : $v.form.job.$error } ,'form-control']"
                id="job_id"
                placeholder="please select the job..."
                search-placeholder="search..."
              />
              <div
                class="invalid-feedback"
                v-if="!$v.form.job.required"
              >
                Job is required.
              </div>
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
                :class="[{'is-invalid' : $v.form.approver.$error } ,'form-control']"
                id="assigned_id"
                placeholder="please selet the approver..."
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
import { api } from "../../../../api/http";
import { mapActions, mapGetters } from "vuex";
import { required, decimal, between, maxLength } from "vuelidate/lib/validators";
import pageMixin from "../../../../mixins/page-mixin";
import downloadjs from "downloadjs";
import bsCustomFileInput from "bs-custom-file-input";

export default {
  name: "ReimbRequest",

  mixins: [pageMixin],

  props: ["id"],

  data() {
    return {
      form: {
        job: {},
        approver: {},
        tax: "Incl",
        date: "",
        details: "",
        amount: "",
        attachments: []
      }
    };
  },

  validations: {
    form: {
      job: { required },
      approver: { required },
      tax: { required },
      date: { required },
      details: { required, maxLength: maxLength(1000) },
      amount: { required, decimal, between: between(0, 1000000) }
    }
  },

  watch: {
    request: function(val) {
      this.populateForm(val);
    }
  },

  computed: {
    ...mapGetters({
      userId: "user/getId",
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
        return { key: item.id, value: item.name };
      });
    },

    jobs: function() {
      return this.getList("job").map(item => {
        return { key: item.id, value: item.title };
      });
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
    this.clear("job");
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
      let p1 = await this.list({ resource: "job" });
      let p2 = await this.list({
        resource: "user",
        query: { in: "user.roles.permissions.name,approve-reimbursement" }
      });
      let p3 = this.id
        ? this.list({ resource: "request", query: { eq: `id,${this.id}` } })
        : new Promise(resolev => resolev());

      Promise.all([p1, p2, p3]).finally(() => {
        this.stopLoading();
      });
    },

    populateForm(request) {
      if (!request) return;
      this.form.job = this.jobs.find(item => item.key === request.job_id);
      this.form.approver = this.users.find(
        item => item.key === request.assigned_id
      );
      this.form.tax = request.meta.tax;
      this.form.amount = request.meta.amount;
      this.form.date = request.meta.date;
      this.form.details = request.meta.details;
    },

    clearForm() {
      this.form.tax = "Incl";
      this.form.amount = "";
      this.form.date = "";
      this.form.details = "";
      this.form.job = null;
      this.form.approver = null;
      this.$v.form.$reset();
    },

    canEdit() {
      if (!this.request) return false;
      return this.request.user_id === this.userId;
    },

    canApprove() {
      if (!this.request) return false;
      return this.request.assigned_id === this.userId;
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
      let form = new FormData();
      form.append("type", "reimbursement");
      form.append("status", "submitted");
      form.append("job_id", this.form.job.key);
      form.append("assigned_id", this.form.approver.key);
      form.append("tax", this.form.tax);
      form.append("amount", this.form.amount);
      form.append("date", this.form.date);
      form.append("details", this.form.details);

      for (let i = 0; i < this.form.attachments.length; i++) {
        let file = this.form.attachments[i];
        form.append(`attachments[${i}]`, file);
      }

      this.create({ resource: "request", data: form })
        .then(() => {
          this.showMessage(
            `Reimbursement request created successfuly.`,
            "success"
          );
        })
        .catch(error => {
          console.log(error);
          this.showMessage(error.message, "danger");
          this.$formFeedback(error.response.data.errors);
        })
        .finally(() => {
          e.target.disabled = false;
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
      form.append("type", "reimbursement");
      form.append("status", status);
      form.append("job_id", this.form.job.key);
      form.append("assigned_id", this.form.approver.key);
      form.append("tax", this.form.tax);
      form.append("amount", this.form.amount);
      form.append("date", this.form.date);
      form.append("details", this.form.details);

      for (let i = 0; i < this.form.attachments.length; i++) {
        let file = this.form.attachments[i];
        form.append(`attachments[${i}]`, file);
      }

      this.update({ resource: "request", id: this.id, data: form, method: "POST", attachments: true })
        .then(respond => {
          this.showMessage(
            `Reimbursement request ${status} successfuly.`,
            "success"
          );
        })
        .catch(error => {
          console.log(error.response);
          this.showMessage(error.message, "danger");
          this.$formFeedback(error.response.data.errors);
        })
        .finally(() => {
          e.target.disabled = false;
        });
    },

    onCancel(e) {
      e.preventDefault();
      this.$router.go(-1);
    },

    onAttachment(e) {
      this.form.attachments = [];
      let files = e.target.files;
      for (let i = 0; i < files.length; i++) {
        this.form.attachments.push(files[i]);
      }
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

<style scoped>
</style>
