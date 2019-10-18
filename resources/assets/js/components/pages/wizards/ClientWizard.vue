<template>
  <div class="wrap-wizard md-layout md-alignment-center-center">
    <!-- Client Wizard -->
    <stepper>
      <template slot="nav">
        <step-icon v-model="currentStep" :step-number="1" :lable="'Step 1'" />
        <step-icon v-model="currentStep" :step-number="2" :lable="'Step 2'" />
        <step-icon v-model="currentStep" :step-number="3" :lable="'Step 3'" />
        <step-icon v-model="currentStep" :step-number="4" :lable="'Step 4'" />
        <step-icon v-model="currentStep" :step-number="5" :lable="'Step 5'" />
      </template>

      <template slot="body">
        <!-- step 0 -->
        <step-body v-model="currentStep" :step-number="0">
          <div class="mt-4">
            <h3>Welcome to TicSol.</h3>
            <p class="text-justify">
              Hi
              <b>{{ userFirstname }}</b>,
              This wizard will guide you through the initial setup to get you up and running as quickly as possible.
            </p>
          </div>
        </step-body>

        <!-- step 1 - Intigerations -->
        <step-body v-model="currentStep" :step-number="1">
          <div class="mt-4">
            <h3>Integrations</h3>
            <p class="text-justify">
              Integrating TicSol with other products can help automate many activities,
              saving time and money, whilst ensuring all your systems are up to date.
            </p>

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-12 col-form-lable">Select your integration method</label>
                <div class="col-sm-12">
                  <select v-model="integration" class="custom-select">
                    <option selected disabled />
                    <option value="qbs" :disabled="!qbsAuth">QuickBooks</option>
                    <option value="xero" :disabled="!xeroAuth">Xero</option>
                  </select>
                </div>
              </div>
            </div>

            <button type="button" class="btn btn-link mt-4" @click="redirectToQBs">Setup QuickBooks</button>

            <button type="button" class="btn btn-link mt-4" @click="redirectToQBs">Setup Xero</button>
          </div>
        </step-body>

        <!-- step 2 - Teams -->
        <step-body v-model="currentStep" :step-number="2">
          <div class="mt-4">
            <!-- validation summary -->
            <div v-if="$v.teams.$error" class="alert alert-danger" role="alert">
              <b class="alert-heading">whoops:</b>
              <p class="mb-0">Your teams list have some errors. Fix them to be able to continue.</p>
            </div>

            <h3>Step 1 - Teams</h3>
            <p class="text-justify">
              Teams are any easy way to group and manage employees. The schedule or employee
              lists can be easily filtered to show just a single team. An employee can belong
              to one or many teams. Teams can be set up for different locations, states or
              front and back of house. Please create some teams now. New teams can always be
              added later.
            </p>
            <ts-grid v-model="teams" :columns="[{key: 0, value:'Team Name'}]" :has-toolbar="false">
              <template slot-scope="{ item }">
                <td>{{ item.name }}</td>
              </template>
              <template slot="grid-modal" slot-scope="{ item }">
                <!-- team name -->
                <div class="form-group">
                  <div class="form-row">
                    <label class="col-sm-2 col-form-lable">
                      Name
                      <i class="field-required">*</i>
                    </label>
                    <div class="col-sm-10">
                      <input
                        v-model="item.name"
                        type="text"
                        placeholder="team name"
                        class="form-control"
                        id="name"
                      />
                    </div>
                  </div>
                </div>
              </template>
            </ts-grid>
          </div>
        </step-body>

        <!-- step 3 - Roles -->
        <step-body v-model="currentStep" :step-number="3">
          <div class="mt-4">
            <!-- validation summary -->
            <div v-if="$v.roles.$error" class="alert alert-danger" role="alert">
              <b class="alert-heading">whoops:</b>
              <p class="mb-0">Your roles list have some errors. Fix them to be able to continue.</p>
            </div>

            <h3>Step 2 - Roles</h3>
            <p class="text-justify">
              Roles control the permissions a user has.
              New roles and changes to roles can always be made later by clicking on the Roles icon (show image).
            </p>
            <ts-grid v-model="roles" :columns="[{key: 0, value:'Role Name'} ]" :has-toolbar="false">
              <template slot-scope="{ item }">
                <td>{{ item.name }}</td>
              </template>
              <template slot="grid-modal" slot-scope="{ item }">
                <!-- role name -->
                <div class="form-group">
                  <div class="form-row">
                    <label class="col-sm-2 col-form-lable">
                      Name
                      <i class="field-required">*</i>
                    </label>
                    <div class="col-sm-10">
                      <input
                        v-model="item.name"
                        type="text"
                        placeholder="role name"
                        class="form-control"
                        id="name"
                      />
                    </div>
                  </div>
                </div>

                <!-- Permissions -->
                <span
                  class="d-none"
                >{{ item.permissions = Array.isArray(item.permissions) ? item.permissions : [] }}</span>
                <div class="form-group">
                  <div class="form-row">
                    <label class="col-sm-3 col-form-label">Jobs</label>
                    <div class="col-sm-9">
                      <div class="custom-control custom-checkbox mt-2">
                        <input
                          v-model="item.permissions"
                          type="checkbox"
                          class="custom-control-input"
                          id="create-profile"
                          value="full-job_profile"
                        />
                        <label
                          class="custom-control-label"
                          for="create-profile"
                        >Can Create/Maintain Job Profile</label>
                      </div>

                      <div class="custom-control custom-checkbox">
                        <input
                          v-model="item.permissions"
                          type="checkbox"
                          class="custom-control-input"
                          id="full-job"
                          value="full-job"
                        />
                        <label class="custom-control-label" for="full-job">Can Create/Maintain Job</label>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="form-row">
                    <label class="col-sm-3 col-form-label">Timesheets</label>
                    <div class="col-sm-9">
                      <div class="custom-control custom-checkbox mt-2">
                        <input
                          v-model="item.permissions"
                          type="checkbox"
                          class="custom-control-input"
                          id="approve-timesheet"
                          value="approve-timesheet"
                        />
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
                    <label class="col-sm-3 col-form-label">Requests</label>
                    <div class="col-sm-9">
                      <div class="custom-control custom-checkbox mt-2">
                        <input
                          v-model="item.permissions"
                          type="checkbox"
                          class="custom-control-input"
                          id="approve-leave"
                          value="approve-leave"
                        />
                        <label
                          class="custom-control-label"
                          for="approve-leave"
                        >Can Approve Leave Request</label>
                      </div>

                      <div class="custom-control custom-checkbox">
                        <input
                          v-model="item.permissions"
                          type="checkbox"
                          class="custom-control-input"
                          id="approve-reimbursement"
                          value="approve-reimbursement"
                        />
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
                    <label class="col-sm-3 col-form-label">Users</label>
                    <div class="col-sm-9">
                      <div class="custom-control custom-checkbox mt-2">
                        <input
                          v-model="item.permissions"
                          type="checkbox"
                          class="custom-control-input"
                          id="full-user"
                          value="full-user"
                        />
                        <label
                          class="custom-control-label"
                          for="full-user"
                        >Can Invite/Maintain Users</label>
                      </div>

                      <div class="custom-control custom-checkbox">
                        <input
                          v-model="item.permissions"
                          type="checkbox"
                          class="custom-control-input"
                          id="full-role"
                          value="full-role"
                        />
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
                    <label class="col-sm-3 col-form-label">Schedule</label>
                    <div class="col-sm-9">
                      <div class="custom-control custom-checkbox mt-2">
                        <input
                          v-model="item.permissions"
                          type="checkbox"
                          class="custom-control-input"
                          id="full-schedule"
                          value="full-schedule"
                        />
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
                    <label class="col-sm-3 col-form-label">Xero</label>
                    <div class="col-sm-9">
                      <div class="custom-control custom-checkbox mt-2">
                        <input
                          v-model="item.permissions"
                          type="checkbox"
                          class="custom-control-input"
                          id="full-xero"
                          value="full-xero"
                        />
                        <label
                          class="custom-control-label"
                          for="full-xero"
                        >Can Maintain Xero Integrations</label>
                      </div>
                    </div>
                  </div>
                </div>
              </template>
            </ts-grid>
          </div>
        </step-body>

        <!-- step 4 - Users -->
        <step-body v-model="currentStep" :step-number="4">
          <div class="mt-4">
            <!-- validation summary -->
            <div v-if="$v.users.$error" class="alert alert-danger" role="alert">
              <b class="alert-heading">whoops:</b>
              <p class="mb-0">Your users list have some errors. Fix them to be able to continue.</p>
            </div>

            <h3>Step 3 - Users</h3>
            <p class="text-justify">
              Please provide the details of any employees you would like to invite. They will
              automatically receive an email invitation to create their account in TicSol. New
              users can always be added later. Users will be deleted if they have not accepted
              the invitation within 7 days.
            </p>
            <ts-grid
              v-model="users"
              :columns="[{key: 0, value:'First Name'}, {key: 1, value:'Last Name'}, {key: 3, value:'E-Mail'}]"
              :has-toolbar="false"
            >
              <template slot-scope="{ item }">
                <td>{{ item.firstname }}</td>
                <td>{{ item.lastname }}</td>
                <td>{{ item.email }}</td>
              </template>
              <template slot="grid-modal" slot-scope="{ item }">
                <!-- firstname -->
                <div class="form-group">
                  <div class="form-row">
                    <label class="col-sm-3 col-form-lable">
                      First Name
                      <i class="field-required">*</i>
                    </label>
                    <div class="col-sm-9">
                      <input
                        v-model="item.firstname"
                        type="text"
                        placeholder="user first name"
                        class="form-control"
                        id="firstname"
                      />
                    </div>
                  </div>
                </div>

                <!-- lastname -->
                <div class="form-group">
                  <div class="form-row">
                    <label class="col-sm-3 col-form-lable">
                      Last Name
                      <i class="field-required">*</i>
                    </label>
                    <div class="col-sm-9">
                      <input
                        v-model="item.lastname"
                        type="text"
                        placeholder="user last name"
                        class="form-control"
                        id="lastname"
                      />
                    </div>
                  </div>
                </div>

                <!-- e-mail -->
                <div class="form-group">
                  <div class="form-row">
                    <label class="col-sm-3 col-form-lable">
                      E-Mail
                      <i class="field-required">*</i>
                    </label>
                    <div class="col-sm-9">
                      <input
                        v-model="item.email"
                        type="text"
                        placeholder="user e-mail"
                        class="form-control"
                        id="email"
                      />
                    </div>
                  </div>
                </div>

                <span class="d-none">{{ item.teams = Array.isArray(item.teams) ? item.teams : [] }}</span>
                <span class="d-none">{{ item.roles = Array.isArray(item.roles) ? item.roles : [] }}</span>

                <!-- teams -->
                <div class="form-group">
                  <div class="form-row">
                    <label class="col-sm-3 col-form-lable">Teams</label>
                    <div class="col-sm-9">
                      <ts-select
                        v-model="item.teams"
                        :data="teamList"
                        :multi="true"
                        id="userTeams"
                        name="userTeams"
                        placeholder="user teams"
                        search-placeholder="search..."
                      />
                    </div>
                  </div>
                </div>

                <!-- roles -->
                <div class="form-group">
                  <div class="form-row">
                    <label class="col-sm-3 col-form-lable">Roles</label>
                    <div class="col-sm-9">
                      <ts-select
                        v-model="item.roles"
                        :data="roleList"
                        :multi="true"
                        id="userRoles"
                        name="userRoles"
                        placeholder="user roles"
                        search-placeholder="search..."
                      />
                    </div>
                  </div>
                </div>

                <template v-if="integration === 'qbs'">
                  <!-- vendor ID -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-3 col-form-lable">Vendor ID</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" v-model="item.vendor_id">
                      </div>
                    </div>
                  </div>

                  <!-- budgeted cost rate -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-3 col-form-lable">Budgeted cost rate</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" v-model="item.budget_cost_rate">
                      </div>
                    </div>
                  </div>
                </template> 
              </template>
            </ts-grid>
          </div>
        </step-body>

        <!-- step 5 - Profiles -->
        <step-body v-model="currentStep" :step-number="5">
          <div class="mt-4">
            <!-- validation summary -->
            <div v-if="$v.profiles.$error" class="alert alert-danger" role="alert">
              <b class="alert-heading">whoops:</b>
              <p class="mb-0">Your profiles list have some errors. Fix them to be able to continue.</p>
            </div>

            <h3>Step 4 - Profiles</h3>
            <p class="text-justify">
              Job profiles are the shells against which jobs are created. They define the additional
              information that should be captured for a new job using that profile. An example of a Job
              profile could be Client or Store. New profiles can be created later.
            </p>
            <ts-grid
              v-model="profiles"
              :columns="[{key: 0, value:'Profile Name'}]"
              :has-toolbar="false"
            >
              <template slot-scope="{ item }">
                <td>{{ item.name }}</td>
              </template>
              <template slot="grid-modal" slot-scope="{ item }">
                <!-- profile name -->
                <div class="form-group">
                  <div class="form-row">
                    <label class="col-sm-2 col-form-lable">
                      Name
                      <i class="field-required">*</i>
                    </label>
                    <div class="col-sm-10">
                      <input
                        v-model="item.name"
                        type="text"
                        placeholder="profile name"
                        class="form-control"
                        id="name"
                      />
                    </div>
                  </div>
                </div>
              </template>
            </ts-grid>
          </div>
        </step-body>

        <!-- step 6 - Settings-->
        <step-body v-model="currentStep" :step-number="6">
          <div class="mt-4">
            <!-- validation summary -->
            <div v-if="$v.settings.$error" class="alert alert-danger" role="alert">
              <b class="alert-heading">whoops:</b>
              <p class="mb-0">Your settings have some errors. Fix them to be able to continue.</p>
            </div>
            <h3>Step 5 - Settings</h3>

            <!-- business hours -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-12 col-form-lable">Business Hours</label>
                <div class="col-sm-12">
                  <div
                    v-for="(item, index) in settings.businessHours"
                    :key="item.day"
                    class="form-row mt-1"
                  >
                    <!-- day label -->
                    <div class="col-md-2">{{ getDayName(item.day) }}</div>

                    <!-- isopen -->
                    <div class="col-md-2">
                      <div class="custom-control custom-checkbox">
                        <input
                          v-model="item.isopen"
                          type="checkbox"
                          :id="`isopen-${index}`"
                          class="custom-control-input"
                        />
                        <label :for="`isopen-${index}`" class="custom-control-label">Open</label>
                      </div>
                    </div>

                    <!-- start -->
                    <div class="col mr-md-2">
                      <div class="form-row">
                        <label class="col-sm-3">From</label>
                        <input
                          v-model="item.start"
                          type="time"
                          class="form-control col"
                          :disabled="!item.isopen"
                        />
                      </div>
                    </div>

                    <!-- end -->
                    <div class="col ml-md-2">
                      <div class="form-row">
                        <label class="col-sm-3">Till</label>
                        <input
                          v-model="item.end"
                          type="time"
                          class="form-control col"
                          :disabled="!item.isopen"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- schedule view -->
            <div class="form-group mt-4">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">Schedule View</label>
                <div class="col-sm-9">
                  <div class="custom-control custom-radio custom-control-inline">
                    <input
                      v-model="settings.scheduleView"
                      type="radio"
                      id="view1"
                      name="views"
                      value="employee"
                      class="custom-control-input"
                    />
                    <label class="custom-control-label" for="view1">Employee</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input
                      v-model="settings.scheduleView"
                      type="radio"
                      id="view2"
                      name="views"
                      value="job"
                      class="custom-control-input"
                    />
                    <label class="custom-control-label" for="view2">Jobs</label>
                  </div>
                </div>
              </div>
            </div>

            <!-- schedule range -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">Schedule Range</label>
                <div class="col-sm-9">
                  <div class="custom-control custom-radio custom-control-inline">
                    <input
                      v-model="settings.scheduleRange"
                      type="radio"
                      id="range1"
                      name="ranges"
                      value="week"
                      class="custom-control-input"
                    />
                    <label class="custom-control-label" for="range1">Week</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input
                      v-model="settings.scheduleRange"
                      type="radio"
                      id="range2"
                      name="ranges"
                      value="month"
                      class="custom-control-input"
                    />
                    <label class="custom-control-label" for="range2">Month</label>
                  </div>
                </div>
              </div>
            </div>

            <!-- hour per day -->
            <div class="form-group mt-4">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">Hour Per Day</label>
                <div class="col-sm-9">
                  <input v-model="settings.hourPerDay" type="text" class="form-control" />
                </div>
              </div>
            </div>
          </div>
        </step-body>
      </template>

      <template slot="footer">
        <div class="wrap-buttons ml-auto">
          <router-link to="/home" class="btn btn-light">Escape</router-link>
          <button type="button" class="btn btn-primary" @click="onNext">
            {{ currentStep === lastStep ? 'Finish' : 'Next' }}
          </button>
        </div>
      </template>
    </stepper>

    <!-- loading screen -->
    <div class="wrap-loading" v-show="isLoading">
      <div class="loading-box shadow-sm">
        <div>
          <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>
        <div class="caption">{{ loadingMsg }}</div>
      </div>
    </div>
  </div>
</template>

<script>
const DEFAULT_LOADING_MSG = "Loading, please wait...";

import { api } from "../../../api/http";
import Stepper from "../../base/formStepper/Stepper";
import StepBody from "../../base/formStepper/StepBody";
import StepIcon from "../../base/formStepper/StepIcon";
import { mapGetters, mapActions } from "vuex";
import { required, email } from "vuelidate/lib/validators";

export default {
  name: "ClientWizard",

  props: ["step"],

  components: {
    stepper: Stepper,
    "step-body": StepBody,
    "step-icon": StepIcon
  },

  data() {
    return {
      isLoading: false,
      loadingMsg: "",
      qbsAuth: false,
      xeroAuth: false,
      integration: "",
      currentStep: 0,
      firstStep: 0,
      lastStep: 6,
      teams: [],
      roles: [],
      users: [],
      profiles: [],
      settings: {
        businessHours: [
          { day: 1, start: "00:00", end: "00:00", isopen: false },
          { day: 2, start: "00:00", end: "00:00", isopen: false },
          { day: 3, start: "00:00", end: "00:00", isopen: false },
          { day: 4, start: "00:00", end: "00:00", isopen: false },
          { day: 5, start: "00:00", end: "00:00", isopen: false },
          { day: 6, start: "00:00", end: "00:00", isopen: false },
          { day: 0, start: "00:00", end: "00:00", isopen: false }
        ],
        scheduleView: "",
        scheduleRange: "",
        hourPerDay: 0
      }
    };
  },

  validations: {
    teams: {
      $each: {
        name: { required }
      }
    },

    roles: {
      $each: {
        name: { required }
      }
    },

    users: {
      $each: {
        firstname: { required },
        lastname: { required },
        email: { required, email }
      }
    },

    profiles: {
      $each: {
        name: { required }
      }
    },

    settings: {
      scheduleView: { required },
      scheduleRange: { required }
    }
  },

  computed: {
    ...mapGetters({
      clientId: "user/getClientId",
      userName: "user/getFullname",
      userFirstname: "user/getFirstname",
      getList: "resource/getList"
    }),

    roleList: function() {
      return this.getList("role").map(item => {
        return { key: item.id, value: item.name };
      });
    },

    teamList: function() {
      return this.getList("team").map(item => {
        return { key: item.id, value: item.name };
      });
    }
  },

  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.loadAssets();
    });
  },

  beforeRouteLeave(to, from, next) {
    next();
  },

  methods: {
    ...mapActions({
      list: "resource/list",
      create: "resource/create",
      update: "resource/update",
      clear: "resource/clear"
    }),

    loadAssets() {
      this.isLoading = true;
      this.loadingMsg = DEFAULT_LOADING_MSG;

      let p1 = this.list({ resource: "team" });
      let p2 = this.list({ resource: "role" });
      let p3 = this.checkQBsAuth().then(res => {
        console.log(res);
        this.qbsAuth = res.data.isTokenValid;
      });

      Promise.all([p1, p2, p3]).then(() => {
        this.isLoading = false;
        if (this.step) this.currentStep = this.step;
      });
    },

    validateCurrentStep() {
      switch (this.currentStep) {
        case 2:
          this.$v.teams.$touch();
          return !this.$v.teams.$invalid;
        case 3:
          this.$v.roles.$touch();
          return !this.$v.roles.$invalid;
        case 4:
          this.$v.users.$touch();
          return !this.$v.users.$invalid;
        case 5:
          this.$v.profiles.$touch();
          return !this.$v.profiles.$invalid;
        case 6:
          this.$v.settings.$touch();
          return !this.$v.settings.$invalid;
        default:
          return true;
      }
    },

    processCurrentStep() {
      switch (this.currentStep) {
        case 2:
          return this.createTeams();
        case 3:
          return this.createRoles();
        case 4:
          return this.createUsers();
        case 5:
          return this.createProfiles();
        case 6:
          return this.saveSettings();
        default:
          return Promise.resolve(true);
      }
    },

    loadCurrentStep() {
      switch (this.currentStep) {
        case 2:
          return this.saveClassesAndDepts();
        case 4:
          return this.loadEmployees();
        default:
          return Promise.resolve(true);
      }
    },

    async checkQBsAuth() {
      return api.get({ url: "/api/quickbooks/hasvalidtoken" });
    },

    async loadEmployees(){
      if(this.integration === 'qbs'){
        this.loadingMsg = "Fetching employees from QuickBooks...";        
        await api.get({url:'/api/quickbooks/employee'}).then(res => {
          let list = res.data;
          if(list !== null){
            list.forEach(employee => {
              this.users.push({
                firstname: employee.GivenName,
                lastname: employee.FamilyName,
                email: employee.PrimaryEmailAddr ?  employee.PrimaryEmailAddr.Address : ""
              });
            });
          }
          return Promise.resolve(true);
        }).catch(err=>{
          console.log(err);
          return Promise.reject(true);
        });
      }

      return Promise.resolve(true);
    },
    
    async saveClassesAndDepts() {
      let classes = [];
      let departments = [];

      if (this.integration !== "qbs") return Promise.resolve(true);

      this.loadingMsg = "Fetching QuickBooks classes ...";

      await api
        .get({ url: "/api/quickbooks/class" })
        .then(res => {
          classes = res.data !== null ? res.data : [];
          console.log(res);
        })
        .catch(err => {
          console.log(err);
          return Promise.reject(true);
        });

      this.loadingMsg = "Fetching QuickBooks locations...";

      await api
        .get({ url: "/api/quickbooks/department" })
        .then(res => {
          departments = res.data !== null ? res.data : [];
          console.log(res);
        })
        .catch(err => {
          console.log(err);
          return Promise.reject(true);
        });

      this.loadingMsg = "Saving classes and locations...";

      await this.update({
        resource: "client",
        id: this.clientId,
        data: { qbs_classes: classes, qbs_departments: departments }
      })
        .then(res => {
          console.log("updated qbs settings successfuly");
          return Promise.resolve(true);
        })
        .catch(err => {
          return Promise.reject(true);
        });
    },

    async createTeams() {
      for (let i = 0; i < this.teams.length; i++) {
        console.log(this.teams[i]);
        await this.createResource("team", this.teams[i]).catch(() => {
          return Promise.reject(false);
        });
      }
      return Promise.resolve(true);
    },

    async createRoles() {
      for (let i = 0; i < this.roles.length; i++) {
        console.log(this.roles[i]);
        await this.createResource("role", this.roles[i]).catch(() => {
          return Promise.reject(false);
        });
      }
      return Promise.resolve(true);
    },

    async createUsers() {
      
      for (let i = 0; i < this.users.length; i++) {
        // setup the form data
        let payload = {};
        payload.firstname = this.users[i].firstname;
        payload.lastname = this.users[i].lastname;
        payload.email = this.users[i].email;
        payload.teams = this.users[i].teams.map(item => item.key);
        payload.roles = this.users[i].roles.map(item => item.key);

        if(this.integration === 'qbs'){          
          payload.buget_cost_rate = this.users[i].buget_cost_rate;

          // check vendor id
          // if vendor_id != null
          //    set vendor_id
          // else 
          //    create vendor in qbs
          //    set vendor_id
        }

        console.log(payload);

        await this.createResource("user", payload).catch(() => {
          return Promise.reject(false);
        });
      }
      return Promise.resolve(true);
    },

    async createProfiles() {
      for (let i = 0; i < this.profiles.length; i++) {
        // setup the form data
        let payload = {};
        payload.name = this.profiles[i].name;
        payload.schema = [];

        await this.createResource("form", payload).catch(() => {
          return Promise.reject(false);
        });
      }
      return Promise.resolve(true);
    },

    async saveSettings() {
      let form = {};
      form.business_hours = this.settings.businessHours;
      form.schedule_view = this.settings.scheduleView;
      form.schedule_range = this.settings.scheduleRange;
      form.hour_per_day = this.settings.hourPerDay;

      console.log(form);

      await this.update({ resource: "client", id: this.clientId, data: form })
        .then(data => {
          return Promise.resolve(true);
        })
        .catch(error => {
          return Promise.reject(false);
        });
    },

    createResource(resource, payload) {
      return new Promise((resolve, reject) => {
        this.create({ resource: resource, data: payload })
          .then(res => resolve(res))
          .catch(err => {
            reject(err);
          });
      });
    },

    getDayName(dayNum) {
      switch (dayNum) {
        case 0:
          return "Sunday";
        case 1:
          return "Monday";
        case 2:
          return "Tuesday";
        case 3:
          return "Wednesday";
        case 4:
          return "Thursday";
        case 5:
          return "Friday";
        case 6:
          return "Saturday";
        default:
          return "";
      }
    },

    redirectToQBs() {
      this.$router.push({ name: "quickbooskWizard" });
    },

    onNext(e) {
      e.preventDefault();
      e.target.disabled = true;

      if (this.validateCurrentStep()) {
        this.isLoading = true;

        this.processCurrentStep()
          .then(() => {
            console.log("step processed.");
            if (this.currentStep === this.lastStep) this.$router.push("/home");

            this.currentStep =
              this.currentStep < this.lastStep
                ? this.currentStep + 1
                : this.currentStep;

            this.loadCurrentStep()
              .then(() => {
                console.log("step loaded.");
                this.isLoading = false;
                e.target.disabled = false;
              })
              .catch(err => {
                console.log("can not load current step.");
              });
          })
          .catch(err => {
            console.log("can not load current step.");
          });
      }
    },

    onBack(e) {
      e.preventDefault();
      this.currentStep =
        this.currentStep > this.firstStep
          ? this.currentStep - 1
          : this.currentStep;
    }
  }
};
</script>

<style scoped>
.wrap-wizard {
  background-color: rgba(255, 255, 255, 0.9);
  padding: 30px;
  width: 50%;
  height: 70%;
  border-radius: 2px;
  position: relative;
}

.wrap-buttons {
  width: max-content;
}

.wrap-loading {
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  position: absolute;
  background-color: rgba(0, 0, 0, 0.15);
  z-index: 9;
}

.loading-box {
  left: 50%;
  top: 50%;
  width: 17vw;
  height: auto;
  padding: 1vw;
  position: relative;
  display: flex;
  align-items: center;
  border-radius: 0px;
  transform: translate(-50%, -50%);
  background-color: white;
}

.loading-box .spinner-border {
  width: 2vw;
  height: 2vw;
  border: 0.4vw solid currentColor;
  border-right-color: transparent;
}

.wrap-loading .caption {
  margin-left: 10px;
  display: inline;
  text-align: left;
  vertical-align: super;
}
</style>
