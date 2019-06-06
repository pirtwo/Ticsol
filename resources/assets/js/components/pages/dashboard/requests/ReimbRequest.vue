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
        <li>
          <button class="btn btn-light">
            New
          </button>
        </li>
        <li>
          <button class="btn btn-light">
            Suspend
          </button>
        </li>
        <li>
          <button
            class="btn btn-light"
            @click="onSubmit"
          >
            Submit
          </button>
        </li>
        <li>
          <button class="btn btn-light">
            Cancel
          </button>
        </li>
        <li>
          <button class="btn btn-light">
            Print
          </button>
        </li>
        <li class="menu-title">
          Links
        </li>
        <li>
          <router-link :to="{ name: 'jobList' }">
            Anuual Leave
          </router-link>
        </li>
        <li>
          <router-link :to="{ name: 'jobList' }">
            Sick Leave
          </router-link>
        </li>
        <li>
          <router-link :to="{ name: 'jobList' }">
            Reimbursement
          </router-link>
        </li>
        <li>
          <router-link :to="{ name: 'jobList' }">
            History
          </router-link>
        </li>
      </ul>
    </template>

    <template slot="content">
      <form>
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Details</label>
            <div class="col-sm-10">
              <textarea
                v-model="form.meta.details"
                name="meta-details"
                id="meta-details"
                class="form-control"
                rows="5"
              />
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Amount</label>
            <div class="col-sm-10">
              <input
                v-model="form.meta.amount"
                name="meta-amount"
                id="meta-amount"
                type="text"
                class="form-control"
              >
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Incl/Excl</label>
            <div class="col-sm-10">
              <div class="custom-control custom-radio custom-control-inline">
                <input
                  v-model="form.meta.tax"
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
                  v-model="form.meta.tax"
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

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Date</label>
            <div class="col-sm-10">
              <input
                v-model="form.meta.date"
                name="meta-date"
                id="meta-date"
                type="date"
                class="form-control"
              >
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Expense To</label>
            <div class="col-sm-10">
              <ts-select
                v-model="form.job"
                :data="jobs"
                id="job_id"
                placeholder="please select the job..."
                search-placeholder="search..."
              />
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
                id="assigned_id"
                placeholder="please selet the approver..."
                search-placeholder="search..."
              />
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
import { mapActions, mapGetters } from "vuex";
import pageMixin from "../../../../mixins/page-mixin";

export default {
  name: "ReimbRequest",

  mixins: [pageMixin],

  props: ["id"],

  data() {
    return {
      currRequest: {},
      form: {
        job: {},
        approver: {},
        meta: {
          tax: "Incl",
          date: "",
          details: "",
          amount: ""
        }
      }
    };
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

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
    });
  },

  mounted() {
    this.startLoading();
    let p1 = this.list({ resource: "job" });
    let p2 = this.list({ resource: "user" });
    let p3 = new Promise(resolve => resolve());
    if (this.id)
      p3 = this.show({ resource: "request", id: this.id }).then(data => {
        this.currRequest = data;
      });

    Promise.all([p1, p2, p3])
      .then(() => {
        if (this.id) {
          this.form.job = this.jobs.find(
            item => item.key === this.currRequest.job_id
          );
          this.form.approver = this.users.find(
            item => item.key === this.currRequest.assigned_id
          );
          this.form.meta.tax = this.currRequest.meta.tax;
          this.form.meta.amount = this.currRequest.meta.amount;
          this.form.meta.date = this.currRequest.meta.date;
          this.form.meta.details = this.currRequest.meta.details;
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
      list: "resource/list",
      show: "resource/show",
      create: "resource/create"
    }),

    onSubmit(e) {
      let form = {};

      form.type = "reimbursement";
      form.status = "submitted";
      form.job_id = this.form.job.key;
      form.assigned_id = this.form.approver.key;
      form.meta = this.form.meta;

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
        });
    },

    clearForm(){
      
    }
  }
};
</script>

<style scoped>
</style>
