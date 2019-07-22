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
            <label class="col-sm-2 col-form-label">Title <i class="field-required">*</i></label>
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
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Schedule</label>
            <div class="col-sm-10">
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

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Xero</label>
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
                >Can Maintain Xero Integrations</label>
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
      if(!this.id) return null;
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

      this.create({ resource: "role", data: this.form })
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

      this.update({ resource: "role", id: this.id, data: this.form })
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