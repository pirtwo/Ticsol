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
                v-model="form.meta.leave_type" 
                name="meta-leave_type" 
                :class="[{'is-invalid' : $v.form.meta.leave_type.$error } ,'custom-select']"
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
                v-if="!$v.form.meta.leave_type.required"
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
                  type="radio"
                  id="display1"
                  name="display"
                  class="custom-control-input"
                  checked
                >
                <label
                  class="custom-control-label"
                  for="display1"
                >Days</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input
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
            <div class="col-sm-10">
              <input
                v-model="form.meta.from"
                type="date"
                name="meta-from"
                :class="[{'is-invalid' : $v.form.meta.from.$error } ,'form-control']"
              >
              <div
                class="invalid-feedback"
                v-if="!$v.form.meta.from.required"
              >
                Please enter a from date.
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Return</label>
            <div class="col-sm-10">
              <input
                v-model="form.meta.till"
                type="date"
                name="meta-till"
                :class="[{'is-invalid' : $v.form.meta.till.$error } ,'form-control']"
              >
              <div
                class="invalid-feedback"
                v-if="!$v.form.meta.till.required"
              >
                Please enter a till date.
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Days Requested</label>
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
import { mapActions, mapGetters } from "vuex";
import { required } from "vuelidate/lib/validators";
import pageMixin from "../../../../mixins/page-mixin";

export default {
  name: "LeaveRequest",

  mixins: [pageMixin],

  props: ["id"],

  data() {
    return {
      currentReq: {},
      form: {
        approver: {},
        attachments: "",
        meta: {
          leave_type: "",
          from: "",
          till: ""
        }
      }
    };
  },

  validations: {
    form: {
      approver:{ required},
      meta: {
        leave_type: {required},
        from: {required},
        till: {required}
      }
    }
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    users: function() {
      return this.getList("user").map(item => {
        return { key: item.id, value: item.name };
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
          this.form.meta.leave_type = this.currentReq.meta.leave_type;
          this.form.meta.from = this.currentReq.meta.from;
          this.form.meta.till = this.currentReq.meta.till;
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
      list: "resource/list",
      show: "resource/show",
      create: "resource/create"
    }),

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
      let form = {};      
      form.type = "leave";
      form.status = "submitted";
      form.assigned_id = this.form.approver.key;
      form.meta = this.form.meta;

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

    clearForm() {
      this.currentReq = {};
      this.form.approver = {};
      this.form.meta = {};
      this.$v.form.$reset();
    }
  }
};
</script>

<style>
</style>
