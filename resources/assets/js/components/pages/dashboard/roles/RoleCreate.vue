<template>
  <nav-view 
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
          <button 
            class="btn btn-light" 
            @click="onSave"
          >                        
            Save
          </button>
        </li>
        <li>
          <button 
            class="btn btn-light" 
            @click="onCancel"
          >                        
            Cancel
          </button>
        </li>
        <li class="menu-title">
          Links
        </li>        
      </ul>
    </template>

    <template slot="content">
      <form class="needs-validation">
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Title</label>
            <div class="col-sm-10">
              <input 
                v-model="form.name" 
                id="name" 
                type="text" 
                class="form-control" 
                placeholder="role name"
              >
            </div>
          </div>
        </div>

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
                >
                  Can Create/Maintain Job Profile</label>
              </div>

              <div class="custom-control custom-checkbox">
                <input 
                  v-model="form.permissions" 
                  type="checkbox" 
                  class="custom-control-input" 
                  id="create-job" 
                  value="create-job"
                >
                <label 
                  class="custom-control-label" 
                  for="create-job"
                >
                  Can Create Job</label>
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
                  id="full-timesheet" 
                  value="full-timesheet"
                >
                <label 
                  class="custom-control-label" 
                  for="full-timesheet"
                >
                  Can Approve Timesheets</label>
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
                  id="full-request_leave" 
                  value="full-request_leave"
                >
                <label 
                  class="custom-control-label" 
                  for="full-request_leave"
                >
                  Can Approve Leave Request</label>
              </div>

              <div class="custom-control custom-checkbox">
                <input 
                  v-model="form.permissions" 
                  type="checkbox" 
                  class="custom-control-input" 
                  id="full-request_reimb" 
                  value="full-request_reimb"
                >
                <label 
                  class="custom-control-label" 
                  for="full-request_reimb"
                >
                  Can Approve Reimbursement</label>
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
                >
                  Can Invite/Maintain Users</label>
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
                >
                  Can Create/Maintain Roles</label>
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
                >
                  Can Maintain Other Users Schedule</label>
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
                >
                  Can Maintain Xero Integrations</label>
              </div>
            </div>
          </div>
        </div>
      </form>
    </template>
  </nav-view>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import NavView from "../../../framework/NavView.vue";
import pageMixin from '../../../../mixins/page-mixin';

export default {
  name: "RoleCreate",

  mixins:[pageMixin],

  components: {
    "nav-view": NavView
  },

  data() {
    return {
      form: {
        name: "",
        permissions: []
      }
    };
  },

  watch: {
    "form.permissions": function(value) {
      console.log(value);
    }
  },

  methods: {
    ...mapActions({
      create: "resource/create"
    }),

    onSave(e) {
      e.preventDefault();
      e.target.disabled = true;
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
      e.preventDefault();
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