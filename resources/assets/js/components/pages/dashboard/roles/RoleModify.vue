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
        <li v-if="role">
          <button
            class="btn"
            @click="onSave"
          >
            Save
          </button>
        </li>
        <li v-if="!role">
          <button
            class="btn"
            @click="onSubmit"
          >
            Submit
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
          <router-link
            class="btn"
            :to="{ name: 'roleList' }"
          >
            Roles
          </router-link>
        </li>
        <li v-if="role">
          <router-link
            class="btn"
            :to="{ name: 'roleUsers', param:{ id: this.id } }"
          >
            Assigned Users
          </router-link>
        </li>
      </ul>
    </template>

    <template slot="content">
      <!-- Role Form -->
      <form>
        <!-- Title -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">
              Title
              <i class="field-required">*</i>
            </label>
            <div class="col-sm-10">
              <input
                v-model="form.name"
                id="name"
                type="text"
                :class="[{'is-invalid' : $v.form.name.$error } ,'form-control']"
                placeholder="role name"
              >
              <div
                class="invalid-feedback"
                v-if="!$v.form.name.required"
              >
                Role name is required.
              </div>
            </div>
          </div>
        </div>

        <!-- Permissions -->

        <!-- Profile & Job -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Jobs</label>
            <div class="col-sm-10">
              <div class="custom-control custom-checkbox">
                <input
                  v-model="form.permissions"
                  type="checkbox"
                  class="custom-control-input"
                  id="create-profile"
                  value="full-job_profile"
                >
                <label
                  class="custom-control-label"
                  for="create-profile"
                >Can Create/Maintain Job Profile</label>
              </div>

              <div class="custom-control custom-checkbox">
                <input
                  v-model="form.permissions"
                  type="checkbox"
                  class="custom-control-input"
                  id="full-job"
                  value="full-job"
                >
                <label
                  class="custom-control-label"
                  for="full-job"
                >Can Create/Maintain Job</label>
              </div>
            </div>
          </div>
        </div>

        <!-- Timesheet -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Timesheets</label>
            <div class="col-sm-10">
              <div class="custom-control custom-checkbox">
                <input
                  v-model="form.permissions"
                  type="checkbox"
                  class="custom-control-input"
                  id="approve-timesheet"
                  value="approve-timesheet"
                >
                <label
                  class="custom-control-label"
                  for="approve-timesheet"
                >Can Approve Timesheets</label>
              </div>
            </div>
          </div>
        </div>

        <!-- Request -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Requests</label>
            <div class="col-sm-10">
              <div class="custom-control custom-checkbox">
                <input
                  v-model="form.permissions"
                  type="checkbox"
                  class="custom-control-input"
                  id="approve-leave"
                  value="approve-leave"
                >
                <label
                  class="custom-control-label"
                  for="approve-leave"
                >Can Approve Leave Request</label>
              </div>

              <div class="custom-control custom-checkbox">
                <input
                  v-model="form.permissions"
                  type="checkbox"
                  class="custom-control-input"
                  id="approve-reimbursement"
                  value="approve-reimbursement"
                >
                <label
                  class="custom-control-label"
                  for="approve-reimbursement"
                >Can Approve Reimbursement</label>
              </div>
            </div>
          </div>
        </div>

        <!-- Users -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Users</label>
            <div class="col-sm-10">
              <div class="custom-control custom-checkbox">
                <input
                  v-model="form.permissions"
                  type="checkbox"
                  class="custom-control-input"
                  id="full-user"
                  value="full-user"
                >
                <label
                  class="custom-control-label"
                  for="full-user"
                >Can Invite/Maintain Users</label>
              </div>

              <div class="custom-control custom-checkbox">
                <input
                  v-model="form.permissions"
                  type="checkbox"
                  class="custom-control-input"
                  id="full-role"
                  value="full-role"
                >
                <label
                  class="custom-control-label"
                  for="full-role"
                >Can Create/Maintain Roles</label>
              </div>

              <div class="custom-control custom-checkbox">
                <input
                  v-model="form.permissions"
                  type="checkbox"
                  class="custom-control-input"
                  id="full-team"
                  value="full-team"
                >
                <label
                  class="custom-control-label"
                  for="full-team"
                >Can Create/Maintain Teams</label>
              </div>
            </div>
          </div>
        </div>

        <!-- Schedule -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Schedule</label>
            <div class="col-sm-10">
              <div class="custom-control custom-checkbox">
                <input
                  v-model="form.permissions"
                  type="checkbox"
                  class="custom-control-input"
                  id="own-schedule"
                  value="create-schedule,update-schedule,delete-schedule"
                >
                <label
                  class="custom-control-label"
                  for="own-schedule"
                >Can Maintain Own Schedule</label>
              </div>

              <div class="custom-control custom-checkbox">
                <input
                  v-model="form.permissions"
                  type="checkbox"
                  class="custom-control-input"
                  id="full-schedule"
                  value="full-schedule"
                >
                <label
                  class="custom-control-label"
                  for="full-schedule"
                >Can Maintain Other Users Schedule</label>
              </div>
            </div>
          </div>
        </div>

        <!-- Reports -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Activity Reports</label>
            <div class="col-sm-10">
              <div class="custom-control custom-checkbox">
                <input
                  v-model="form.permissions"
                  type="checkbox"
                  class="custom-control-input"
                  id="activity"
                  value="list_all-activity,view_all-activity"
                >
                <label
                  class="custom-control-label"
                  for="activity"
                >Can View Other Users Activity Reports</label>
              </div>
            </div>
          </div>
        </div>

        <!--  3rd party integrations -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Integrations</label>
            <div class="col-sm-10">
              <div class="custom-control custom-checkbox">
                <input
                  v-model="form.permissions"
                  type="checkbox"
                  class="custom-control-input"
                  id="full-xero"
                  value="full-xero"
                >
                <label
                  class="custom-control-label"
                  for="full-xero"
                >Can Maintain 3rd Party Integrations</label>
              </div>
            </div>
          </div>
        </div>
      </form>
    </template>
  </app-main>
</template>

<script>
// seperates the rules with comma
const regex = /([a-zA-Z-_]+)/g;

import { mapActions, mapGetters } from "vuex";
import { required } from "vuelidate/lib/validators";
import pageMixin from "../../../../mixins/page-mixin";

export default {
  name: "RoleModify",

  mixins: [pageMixin],

  props: ["id"],

  data() {
    return {
      form: {
        name: "",
        permissions: []
      }
    };
  },

  validations: {
    form: {
      name: { required }
    }
  },

  watch: {
    role: function(val) {
      this.populateForm(val);
    }
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    role: function() {
      if (!this.id) return null;
      return this.getList("role")[0];
    }
  },

  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.loadAssets();
    });
  },

  beforeRouteLeave(to, from, next) {
    this.clear("role");
    next();
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
      let p1 = this.id
        ? this.list({
            resource: "role",
            query: { eq: `id,${this.id}`, with: "permissions" }
          })
        : new Promise(resolve => resolve());

      Promise.all([p1]).finally(() => {
        this.stopLoading();
      });
    },

    populateForm(role) {
      this.form.name = role.name;
      this.form.permissions = role.permissions.map(item => item.name);

      // combine create-schedule, update-schedule and delete-schedule into one rule
      if (
        this.form.permissions.indexOf("create-schedule") > -1 &&
        this.form.permissions.indexOf("update-schedule") > -1 &&
        this.form.permissions.indexOf("delete-schedule") > -1
      ) {
        this.form.permissions.splice(
          this.form.permissions.indexOf("create-schedule"),
          1
        );
        this.form.permissions.splice(
          this.form.permissions.indexOf("update-schedule"),
          1
        );
        this.form.permissions.splice(
          this.form.permissions.indexOf("delete-schedule"),
          1
        );
        this.form.permissions.push("create-schedule,update-schedule,delete-schedule");
      }

      // combine list_all-activity, view_all-activity into one rule
      if (
        this.form.permissions.indexOf("list_all-activity") > -1 &&
        this.form.permissions.indexOf("view_all-activity") > -1
      ) {
        this.form.permissions.splice(
          this.form.permissions.indexOf("list_all-activity"),
          1
        );
        this.form.permissions.splice(
          this.form.permissions.indexOf("view_all-activity"),
          1
        );
        this.form.permissions.push("list_all-activity,view_all-activity");
      }
    },

    onSubmit(e) {
      e.preventDefault();
      e.target.disabled = true;

      // validate
      this.$v.$touch();
      if (this.$v.$invalid) {
        e.target.disabled = false;
        return;
      }

      let form = {};
      form.name = this.form.name;
      form.permissions = [];
      this.form.permissions.forEach(
        i => (form.permissions = form.permissions.concat(i.match(regex)))
      );

      console.log(form);

      this.create({ resource: "role", data: form })
        .then(respond => {
          e.target.disabled = false;
          this.showMessage(
            `Role <b>${this.form.name}</b> created successfuly.`,
            "success"
          );
        })
        .catch(error => {
          e.target.disabled = false;
          this.showMessage(error.message, "danger");
          this.$formFeedback(error.response.data.errors);
        });
    },

    onSave(e) {
      e.preventDefault();
      e.target.disabled = true;

      // validate
      this.$v.$touch();
      if (this.$v.$invalid) {
        e.target.disabled = false;
        return;
      }

      let form = {};
      form.name = this.form.name;
      form.permissions = [];
      this.form.permissions.forEach(
        i => (form.permissions = form.permissions.concat(i.match(regex)))
      );

      console.log(form);

      this.update({ resource: "role", id: this.id, data: form })
        .then(respond => {
          e.target.disabled = false;
          this.showMessage(
            `Role <b>${this.form.name}</b> updated successfuly.`,
            "success"
          );
        })
        .catch(error => {
          e.target.disabled = false;
          this.showMessage(error.message, "danger");
          this.$formFeedback(error.response.data.errors);
        });
    },

    clearForm() {
      this.$v.form.$reset();
    },

    onCancel(e) {
      e.preventDefault();
      this.$router.push({ name: "roleList" });
    }
  }
};
</script>

<style scoped>
</style>