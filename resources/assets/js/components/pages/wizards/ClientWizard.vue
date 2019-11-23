<template>
  <div class="wrap-wizard md-layout md-alignment-center-center">
    <!-- Client Wizard -->
    <stepper>
      <template slot="nav">
        <h4
          class="mt-3"
          v-show="currentStep != 0"
        >
          STEP {{ currentStep }} OF {{ lastStep }}
        </h4>
      </template>

      <template slot="body">
        <!-- Errors -->
        <template v-for="(value, key) in errors">
          <div
            class="alert alert-danger d-flex"
            role="alert"
            :key="key"
          >
            <div>
              <b class="alert-heading">whoops:</b>
              <p class="mb-0">
                {{ value }}
              </p>
            </div>
            <button
              type="button"
              class="close ml-auto"
              @click="errors.splice(key,1)"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </template>

        <!-- step 0 -->
        <step-body
          v-model="currentStep"
          :step-number="0"
        >
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
        <step-body
          v-model="currentStep"
          :step-number="1"
        >
          <div class="mt-4">
            <h3>Step 1 - Integrations</h3>
            <p class="text-justify">
              Integrating TicSol with other products can help automate many activities,
              saving time and money, whilst ensuring all your systems are up to date.
            </p>

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-12 col-form-lable">Select your integration method</label>
                <div class="col-sm-12">
                  <select
                    v-model="integration"
                    class="custom-select"
                  >
                    <option
                      selected
                      disabled
                    />
                    <option
                      value="qbs"
                      :disabled="!qbsAuth"
                    >
                      QuickBooks
                    </option>
                  </select>
                </div>
              </div>

              <button
                type="button"
                class="btn btn-link mt-4"
                @click="redirectToQBs"
              >
                Setup QuickBooks
              </button>
            </div>
          </div>
        </step-body>

        <!-- step 2 - Teams -->
        <step-body
          v-model="currentStep"
          :step-number="2"
        >
          <div class="mt-4">
            <!-- validation summary -->
            <div
              v-if="$v.teams.$error"
              class="alert alert-danger"
              role="alert"
            >
              <b class="alert-heading">whoops:</b>
              <p class="mb-0">
                Your teams list have some errors. Fix them to be able to continue.
              </p>
            </div>

            <h3>Step 2 - Teams</h3>
            <p class="text-justify">
              Teams are any easy way to group and manage employees. The schedule or employee
              lists can be easily filtered to show just a single team. An employee can belong
              to one or many teams. Teams can be set up for different locations, states or
              front and back of house. Please create some teams now. New teams can always be
              added later.
            </p>
            <ts-grid
              v-model="teams"
              :columns="[{key: 0, value:'Team Name'}]"
              :has-toolbar="false"
            >
              <template slot-scope="{ item }">
                <td>{{ item.name }}</td>
              </template>
              <template
                slot="grid-modal"
                slot-scope="{ item }"
              >
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
                      >
                    </div>
                  </div>
                </div>
              </template>
            </ts-grid>
          </div>
        </step-body>

        <!-- step 3 - Roles -->
        <step-body
          v-model="currentStep"
          :step-number="3"
        >
          <div class="mt-4">
            <!-- validation summary -->
            <div
              v-if="$v.roles.$error"
              class="alert alert-danger"
              role="alert"
            >
              <b class="alert-heading">whoops:</b>
              <p class="mb-0">
                Your roles list have some errors. Fix them to be able to continue.
              </p>
            </div>

            <h3>Step 3 - Roles</h3>
            <p class="text-justify">
              Roles control the permissions a user has.
              New roles and changes to roles can always be made later by clicking on the Roles icon.
            </p>
            <ts-grid
              v-model="roles"
              :columns="[{key: 0, value:'Role Name'} ]"
              :has-toolbar="false"
            >
              <template slot-scope="{ item }">
                <td>{{ item.name }}</td>
              </template>
              <template
                slot="grid-modal"
                slot-scope="{ item }"
              >
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
                      >
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
                        >
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
                    <label class="col-sm-3 col-form-label">Timesheets</label>
                    <div class="col-sm-9">
                      <div class="custom-control custom-checkbox mt-2">
                        <input
                          v-model="item.permissions"
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
                    <label class="col-sm-3 col-form-label">Requests</label>
                    <div class="col-sm-9">
                      <div class="custom-control custom-checkbox mt-2">
                        <input
                          v-model="item.permissions"
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
                          v-model="item.permissions"
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
                    <label class="col-sm-3 col-form-label">Users</label>
                    <div class="col-sm-9">
                      <div class="custom-control custom-checkbox mt-2">
                        <input
                          v-model="item.permissions"
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
                          v-model="item.permissions"
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
                    <label class="col-sm-3 col-form-label">Schedule</label>
                    <div class="col-sm-9">
                      <div class="custom-control custom-checkbox mt-2">
                        <input
                          v-model="item.permissions"
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
                    <label class="col-sm-3 col-form-label">Xero</label>
                    <div class="col-sm-9">
                      <div class="custom-control custom-checkbox mt-2">
                        <input
                          v-model="item.permissions"
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
              </template>
            </ts-grid>
          </div>
        </step-body>

        <!-- step 4 - Users -->
        <step-body
          v-model="currentStep"
          :step-number="4"
        >
          <div class="mt-4">
            <!-- validation summary -->
            <div
              v-if="$v.users.$error"
              class="alert alert-danger"
              role="alert"
            >
              <b class="alert-heading">whoops:</b>
              <p class="mb-0">
                Your users list have some errors. Fix them to be able to continue.
              </p>
            </div>

            <h3>Step 4 - Users</h3>
            <p class="text-justify">
              Please provide the details of any employees you would like to invite. They will
              automatically receive an email invitation to create their account in TicSol. New
              users can always be added later.
            </p>
            <ts-grid
              v-model="users"
              :columns="integration === 'qbs' ?[{key: 0, value:'First Name'}, {key: 1, value:'Last Name'}, {key: 2, value:'E-Mail'}, {key: 3, value:'Vendor'}, {key: 4, value:'Budgeted Cost Rate'}] : [{key: 0, value:'First Name'}, {key: 1, value:'Last Name'}, {key: 2, value:'E-Mail'}]"
              :has-toolbar="false"
            >
              <template slot-scope="{ item }">
                <td>{{ item.firstname }}</td>
                <td>{{ item.lastname }}</td>
                <td>{{ item.email }}</td>
                <template v-if="integration === 'qbs'">
                  <td>{{ item.vendor ? item.vendor.value : '' }}</td>
                  <td>{{ item.budgetCostRate }}</td>
                </template>
              </template>
              <template
                slot="grid-modal"
                slot-scope="{ item }"
              >
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
                      >
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
                      >
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
                      >
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
                  <!-- vendor -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-3 col-form-lable">Vendor</label>
                      <div class="col-sm-9">
                        <ts-select
                          v-model="item.vendor"
                          :data="vendorAccountList"
                          :multi="false"
                          id="vendorAcc"
                          name="vendorAcc"
                          placeholder="Select vendor account"
                          search-placeholder="search..."
                        />
                      </div>
                    </div>
                  </div>

                  <!-- budgeted cost rate -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-3 col-form-lable">Budgeted cost rate</label>
                      <div class="col-sm-9">
                        <input
                          type="text"
                          class="form-control"
                          v-model="item.budgetCostRate"
                        >
                      </div>
                    </div>
                  </div>
                </template>
              </template>
            </ts-grid>
          </div>
        </step-body>

        <!-- step 5 - Profiles -->
        <step-body
          v-model="currentStep"
          :step-number="5"
        >
          <div class="mt-4">
            <!-- validation summary -->
            <div
              v-if="$v.profiles.$error"
              class="alert alert-danger"
              role="alert"
            >
              <b class="alert-heading">whoops:</b>
              <p class="mb-0">
                Your profiles list have some errors. Fix them to be able to continue.
              </p>
            </div>

            <h3>Step 5 - Profiles</h3>
            <p class="text-justify">
              Job profiles are the shells against which jobs are created. They define the additional
              information that should be captured for a new job using that profile. An example of a Job
              profile could be Client or Store. New profiles can be created later.
            </p>
            <ts-grid
              v-model="profiles"
              :columns="[{key: 0, value:'Profile Name'}, {key: 1, value:'Billable'}]"
              :has-toolbar="false"
            >
              <template slot-scope="{ item }">
                <td>{{ item.name }}</td>
                <td>{{ item.billable ? 'YES' : 'NO' }}</td>
              </template>
              <template
                slot="grid-modal"
                slot-scope="{ item }"
              >
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
                      >
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
                        >
                        <label
                          class="custom-control-label"
                          for="billable"
                        >Billable</label>
                      </div>
                    </div>
                  </div>
                </div>
              </template>
            </ts-grid>
          </div>
        </step-body>

        <!-- step 6 - Jobs -->
        <step-body
          v-model="currentStep"
          :step-number="6"
        >
          <div class="mt-4">
            <!-- validation summary -->
            <div
              v-if="$v.jobs.$error"
              class="alert alert-danger"
              role="alert"
            >
              <b class="alert-heading">whoops:</b>
              <p class="mb-0">
                Your projects list have some errors. Fix them to be able to continue.
              </p>
            </div>

            <h3>Step 6 - Clients & Projects</h3>
            <p class="text-justify">
              TicSol has automatically your active customers from QuickBooks.
              New clients can be added now or later. As new clients are created
              in TicSol, they will be automatically created in QuickBooks Online.
            </p>
            <ts-grid
              v-model="jobs"
              :columns="[{key: 0, value:'Title'}, {key: 1, value:'Code'}, {key: 2, value:'Profile'}, {key: 3, value:'Parent'}]"
              :has-toolbar="false"
            >
              <template slot-scope="{ item }">
                <td>{{ item.title }}</td>
                <td>{{ item.code }}</td>
                <td>{{ item.profile ? item.profile.value : '' }}</td>
                <td>{{ item.parent ? item.parent.value : '' }}</td>
              </template>
              <template
                slot="grid-modal"
                slot-scope="{ item }"
              >
                <!-- title -->
                <div class="form-group">
                  <div class="form-row">
                    <label class="col-sm-2 col-form-lable">
                      Title
                      <i class="field-required">*</i>
                    </label>
                    <div class="col-sm-10">
                      <input
                        type="text"
                        id="title"
                        class="form-control"
                        v-model="item.title"
                        placeholder="Enter job title"
                      >
                    </div>
                  </div>
                </div>

                <!-- code -->
                <div class="form-group">
                  <div class="form-row">
                    <label class="col-sm-2 col-form-lable">
                      Code
                      <i class="field-required">*</i>
                    </label>
                    <div class="col-sm-10">
                      <input
                        type="text"
                        id="code"
                        class="form-control"
                        v-model="item.code"
                        placeholder="Enter job code"
                      >
                    </div>
                  </div>
                </div>

                <!-- profile -->
                <div class="form-group">
                  <div class="form-row">
                    <label class="col-sm-2 col-form-lable">Profile</label>
                    <div class="col-sm-10">
                      <ts-select
                        v-model="item.profile"
                        :data="profileList"
                        :multi="false"
                        id="profile"
                        name="profile"
                        placeholder="Select job profile"
                        search-placeholder="search..."
                      />
                    </div>
                  </div>
                </div>

                <!-- parent -->
                <div class="form-group">
                  <div class="form-row">
                    <label class="col-sm-2 col-form-lable">Parent</label>
                    <div class="col-sm-10">
                      <ts-select
                        v-model="item.parent"
                        :data="jobList"
                        :multi="false"
                        id="parent"
                        name="parent"
                        placeholder="Select job parent"
                        search-placeholder="search..."
                      />
                    </div>
                  </div>
                </div>
              </template>
            </ts-grid>
          </div>
        </step-body>

        <!-- step 7 - Billing-Settings -->
        <step-body
          v-model="currentStep"
          :step-number="7"
        >
          <div class="mt-4">
            <!-- validation summary -->
            <div
              v-if="$v.billingSettings.$error"
              class="alert alert-danger"
              role="alert"
            >
              <b class="alert-heading">whoops:</b>
              <p class="mb-0">
                Your settings have some errors. Fix them to be able to continue.
              </p>
            </div>
            <h3>Step 7 - Billing Settings</h3>

            <!-- hourly billing -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-12 col-form-lable">
                  Hourly Billing
                  <i class="field-required">*</i>
                </label>
                <div class="col-md-12">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <label
                        class="input-group-text"
                        for="hoursRound"
                      >
                        Round Hours
                        <i class="field-required">*</i>
                      </label>
                    </div>
                    <!-- round -->
                    <select
                      v-model="billingSettings.hoursRound"
                      class="custom-select"
                      id="hoursRound"
                      name="hoursRound"
                    >
                      <option
                        selected
                        disabled
                      >
                        Choose Rounding...
                      </option>
                      <option value="up">
                        Up
                      </option>
                      <option value="nearest">
                        To Nearest
                      </option>
                    </select>
                    <!-- interval -->
                    <select
                      v-model="billingSettings.hoursInterval"
                      class="custom-select"
                      id="hoursInterval"
                      name="hoursInterval"
                    >
                      <option
                        selected
                        disabled
                      >
                        Choose interval...
                      </option>
                      <option value="6">
                        6
                      </option>
                      <option value="15">
                        15
                      </option>
                      <option value="30">
                        30
                      </option>
                      <option value="60">
                        60
                      </option>
                    </select>
                    <div class="input-group-append">
                      <label
                        class="input-group-text"
                        for="hoursInterval"
                      >Minutes</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- daily billing -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-12 col-form-lable">
                  Daily Billing
                  <i class="field-required">*</i>
                </label>
                <div class="col-sm-12">
                  <div class="input-group mb-3">
                    <!-- round -->
                    <div class="input-group-prepend">
                      <label
                        class="input-group-text"
                        for="daysRound"
                      >Round Days</label>
                    </div>
                    <select
                      v-model="billingSettings.daysRound"
                      class="custom-select"
                      id="daysRound"
                      name="daysRound"
                    >
                      <option
                        selected
                        disabled
                      >
                        Choose Rounding...
                      </option>
                      <option value="up">
                        Up
                      </option>
                      <option value="nearest">
                        To Nearest
                      </option>
                    </select>

                    <!-- interval -->
                    <select
                      v-model="billingSettings.daysInterval"
                      class="custom-select"
                      id="daysInterval"
                      name="daysInterval"
                    >
                      <option
                        selected
                        disabled
                      >
                        Choose interval...
                      </option>
                      <option value="0.25">
                        0.25
                      </option>
                      <option value="0.5">
                        0.5
                      </option>
                      <option value="1">
                        1
                      </option>
                    </select>
                    <div class="input-group-append">
                      <label
                        class="input-group-text"
                        for="daysInterval"
                      >Day</label>
                    </div>
                    <!-- hours in day -->
                    <div class="input-group-prepend">
                      <span class="input-group-text">1 Day Equals</span>
                    </div>
                    <input
                      v-model="billingSettings.hoursInDay"
                      type="text"
                      class="form-control"
                    >
                    <div class="input-group-append">
                      <span class="input-group-text">Hours</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <fieldset :disabled="integration !== 'qbs'">
              <!-- revenue accounts -->
              <div class="form-group">
                <div class="form-row">
                  <label class="col-md-6 col-form-lable">
                    Revenue Accounts
                    <i
                      v-show="integration === 'qbs'"
                      class="field-required"
                    >*</i>
                  </label>
                  <div class="col-md-6">
                    <ts-chipsbox
                      v-model="billingSettings.revenueAccounts"
                      :data="revenueAccList"
                      :multi="true"
                      :disabled="integration !== 'qbs'"
                      id="revenueAccounts"
                      name="revenueAccounts"
                      placeholder="Select revenue accounts"
                      search-placeholder="search here..."
                    />
                  </div>
                </div>
              </div>

              <!-- allow prepiad jobs -->
              <div class="form-group">
                <div class="form-row">
                  <label class="col-md-6 col-form-lable">
                    Allow Prepiad Jobs?
                    <i
                      v-show="integration === 'qbs'"
                      class="field-required"
                    >*</i>
                  </label>
                  <div class="col-md-6">
                    <div class="custom-control custom-switch">
                      <input
                        type="checkbox"
                        v-model="billingSettings.allowPrepaidJobs"
                        class="custom-control-input"
                        id="allowPrepaidJobs"
                      >
                      <label
                        class="custom-control-label"
                        for="allowPrepaidJobs"
                      />
                    </div>
                  </div>
                </div>
              </div>

              <!-- income in advance accounts -->
              <div class="form-group">
                <div class="form-row">
                  <label class="col-md-6 col-form-lable">Income In Advance Account</label>
                  <div class="col-md-6">
                    <ts-chipsbox
                      v-model="billingSettings.incomeInAdvAccount"
                      :disabled="!billingSettings.allowPrepaidJobs"
                      :data="incomeInAdvAccList"
                      :multi="false"
                      id="incomeInAdvanceAccount"
                      name="incomeInAdvanceAccount"
                      placeholder="Select income in advance account"
                      search-placeholder="search here..."
                    />
                  </div>
                </div>
              </div>
            </fieldset>
          </div>
        </step-body>

        <!-- step 8 - Billing-Defaults -->
        <step-body
          v-model="currentStep"
          :step-number="8"
        >
          <div class="mt-4">
            <!-- validation summary -->
            <div
              v-if="$v.billingDefaults.$error"
              class="alert alert-danger"
              role="alert"
            >
              <b class="alert-heading">whoops:</b>
              <p class="mb-0">
                Your settings have some errors. Fix them to be able to continue.
              </p>
            </div>

            <h3>Step 8 - Billing Defaults</h3>
            <p class="text-justify">
              These default settings will be applied to all new billable jobs.
              They can then be individually altered as required.
            </p>
          </div>

          <!-- payment type -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-md-6 col-form-lable">
                Prepaid or Paid in arrears?
                <i class="field-required">*</i>
              </label>
              <div class="col-md-6">
                <div class="custom-control custom-radio custom-control-inline">
                  <input
                    v-model="billingDefaults.paymentType"
                    type="radio"
                    id="prepaid"
                    name="paymentType"
                    value="prepaid"
                    class="custom-control-input"
                  >
                  <label
                    class="custom-control-label"
                    for="prepaid"
                  >Prepaid</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input
                    v-model="billingDefaults.paymentType"
                    type="radio"
                    id="inArrears"
                    name="paymentType"
                    value="inArrears"
                    class="custom-control-input"
                  >
                  <label
                    class="custom-control-label"
                    for="inArrears"
                  >In Arrears</label>
                </div>
              </div>
            </div>
          </div>

          <fieldset :disabled="billingDefaults.paymentType !== 'prepaid'">
            <!-- over billing -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-md-6 col-form-lable">Allow Billing above total job value?</label>
                <div class="col-md-6">
                  <div class="custom-control custom-switch">
                    <input
                      v-model="billingDefaults.allowOverbilling"
                      type="checkbox"
                      class="custom-control-input"
                      id="overBilling"
                    >
                    <label
                      class="custom-control-label"
                      for="overBilling"
                    />
                  </div>
                </div>
              </div>
            </div>
          </fieldset>

          <fieldset
            :disabled="billingDefaults.paymentType !== 'prepaid' || !billingDefaults.allowOverbilling"
          >
            <!-- job fallback rate -->
            <div class="form-group">
              <div class="form-row">
                <label
                  class="col-md-6 col-form-lable"
                >When billing above total job value apply same job rate or company default rate?</label>
                <div class="col-md-6">
                  <div class="custom-control custom-radio custom-control-inline">
                    <input
                      v-model="billingDefaults.jobFallbackRate"
                      value="sameRate"
                      type="radio"
                      id="sameRate"
                      name="jobFallbackRate"
                      class="custom-control-input"
                    >
                    <label
                      class="custom-control-label"
                      for="sameRate"
                    >Same Rate</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input
                      v-model="billingDefaults.jobFallbackRate"
                      value="companyDefault"
                      type="radio"
                      id="companyDefault"
                      name="jobFallbackRate"
                      class="custom-control-input"
                    >
                    <label
                      class="custom-control-label"
                      for="companyDefault"
                    >Company Default</label>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- unit type -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-md-6 col-form-lable">
                Billing Unit Type
                <i class="field-required">*</i>
              </label>
              <div class="col-md-6">
                <select
                  v-model="billingDefaults.billingUnitType"
                  name="unitType"
                  id="unitType"
                  class="custom-select"
                >
                  <option
                    selected
                    disabled
                  >
                    Choose Unit Type...
                  </option>
                  <option value="minutes">
                    Minutes
                  </option>
                  <option value="days">
                    Days
                  </option>
                </select>
              </div>
            </div>
          </div>

          <!-- revenue account -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-md-6 col-form-lable">
                Default Revenue Account
                <i
                  v-show="integration === 'qbs'"
                  class="field-required"
                >*</i>
              </label>
              <div class="col-md-6">
                <ts-chipsbox
                  v-model="billingDefaults.revenueAccount"
                  :data="revenueAccList"
                  :multi="false"
                  :disabled="integration !== 'qbs'"
                  id="revenueAccounts"
                  name="revenueAccounts"
                  placeholder="Select revenue account"
                  search-placeholder="search here..."
                />
              </div>
            </div>
          </div>

          <!-- company rate -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-md-6 col-form-lable">
                Company Default Rate
                <i class="field-required">*</i>
              </label>
              <div class="col-md-6">
                <input
                  v-model="billingDefaults.companyRate"
                  type="text"
                  name="companyRate"
                  id="companyRate"
                  class="form-control"
                >
              </div>
            </div>
          </div>
        </step-body>

        <!-- step 9 - Reimbirsment-Settings-->
        <step-body
          v-model="currentStep"
          :step-number="9"
        >
          <div class="mt-4">
            <!-- validation summary -->
            <div
              v-if="$v.reimbSettings.$error"
              class="alert alert-danger"
              role="alert"
            >
              <b class="alert-heading">whoops:</b>
              <p class="mb-0">
                Your settings have some errors. Fix them to be able to continue.
              </p>
            </div>
            <h3>Step 9 - Reimbursement Settings</h3>

            <!-- rate and measure -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-md-12 col-form-lable">
                  Travel Claims
                  <i class="field-required">*</i>
                </label>
                <div class="col-md-12">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <label
                        class="input-group-text"
                        for="reimbRate"
                      >
                        Reimburse Car Travel At
                        <i class="field-required">*</i>
                      </label>
                    </div>
                    <input
                      v-model="reimbSettings.rate"
                      type="text"
                      name="reimbRate"
                      id="reimbRate"
                      class="form-control"
                    >
                    <div class="input-group-prepend input-group-append">
                      <label
                        class="input-group-text"
                        for="reimbMeasure"
                      >
                        Cents Per
                        <i class="field-required">*</i>
                      </label>
                    </div>
                    <select
                      v-model="reimbSettings.measure"
                      class="custom-select"
                      id="reimbMeasure"
                      name="reimbMeasure"
                    >
                      <option
                        selected
                        disabled
                      >
                        Choose Measure...
                      </option>
                      <option value="kilometres">
                        Kilometres
                      </option>
                      <option value="miles">
                        Miles
                      </option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- reimb expence account -->
            <div class="form-group mt-3">
              <div class="form-row">
                <label class="col-md-3 col-form-lable">Expense Car Travel To</label>
                <div class="col-md-9">
                  <ts-chipsbox
                    v-model="reimbSettings.expenceAccount"
                    :data="expenseAccList"
                    :multi="false"
                    :disabled="integration !== 'qbs'"
                    id="reimbExpenceAccount"
                    name="reimbExpenceAccount"
                    placeholder="Select expence account"
                    search-placeholder="search here..."
                  />
                </div>
              </div>
            </div>
          </div>
        </step-body>

        <!-- step 10 - Settings -->
        <step-body
          v-model="currentStep"
          :step-number="10"
        >
          <div class="mt-4">
            <!-- validation summary -->
            <div
              v-if="$v.settings.$error"
              class="alert alert-danger"
              role="alert"
            >
              <b class="alert-heading">whoops:</b>
              <p class="mb-0">
                Your settings have some errors. Fix them to be able to continue.
              </p>
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
                    <div class="col-md-2">
                      {{ getDayName(item.day) }}
                    </div>

                    <!-- isopen -->
                    <div class="col-md-2">
                      <div class="custom-control custom-checkbox">
                        <input
                          v-model="item.isopen"
                          type="checkbox"
                          :id="`isopen-${index}`"
                          class="custom-control-input"
                        >
                        <label
                          :for="`isopen-${index}`"
                          class="custom-control-label"
                        >Open</label>
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
                        >
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
                        >
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
                    >
                    <label
                      class="custom-control-label"
                      for="view1"
                    >Employee</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input
                      v-model="settings.scheduleView"
                      type="radio"
                      id="view2"
                      name="views"
                      value="job"
                      class="custom-control-input"
                    >
                    <label
                      class="custom-control-label"
                      for="view2"
                    >Jobs</label>
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
                    >
                    <label
                      class="custom-control-label"
                      for="range1"
                    >Week</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input
                      v-model="settings.scheduleRange"
                      type="radio"
                      id="range2"
                      name="ranges"
                      value="month"
                      class="custom-control-input"
                    >
                    <label
                      class="custom-control-label"
                      for="range2"
                    >Month</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </step-body>
      </template>

      <template slot="footer">
        <div class="wrap-buttons w-100 d-flex">
          <router-link
            :to="{name: 'home'}"
            class="btn btn-link"
          >
            Escape
          </router-link>
          <button
            type="button"
            class="btn btn-link"
            @click="onBack"
          >
            Back
          </button>
          <div class="ml-auto">
            <button
              type="button"
              class="btn btn-primary"
              @click="onNext"
            >
              {{ currentStep === lastStep ? 'Finish' : 'Next' }}
            </button>
          </div>
        </div>
      </template>
    </stepper>

    <!-- loading screen -->
    <loading-screen
      :show="isLoading"
      :message="loadingMsg"
    />
  </div>
</template>

<script>
const DEFAULT_LOADING_MSG = "Loading, please wait...";

import * as QBs from "../../../api/qbs-endpoints";
import { api } from "../../../api/http";
import Stepper from "../../base/formStepper/Stepper";
import StepBody from "../../base/formStepper/StepBody";
import LoadingScreen from "../../app/AppLoadingScreen";
import { mapGetters, mapActions } from "vuex";
import {
  required,
  requiredIf,
  email,
  decimal,
  between
} from "vuelidate/lib/validators";
import { isNumber } from 'util';

export default {
  name: "ClientWizard",

  props: ["startstep"],

  components: {
    stepper: Stepper,
    "step-body": StepBody,
    "loading-screen": LoadingScreen
  },

  data() {
    return {
      isLoading: false,
      loadingMsg: "",
      currentStep: +(parseInt(this.startstep) >= 0 ? this.startstep : 0),
      firstStep: 0,
      lastStep: 10,

      // Errors stack
      errors: [],

      // raw lists
      vendorAccounts: [],
      revenueAccounts: [],
      expenseAccounts: [],
      incomeInAdvAccounts: [],

      // wizard form data
      qbsAuth: false,
      xeroAuth: false,
      integration: "",
      teams: [],
      roles: [],
      users: [],
      jobs: [],
      profiles: [],
      billingSettings: {
        /**
         * could be: up, nearest.
         */
        hoursRound: "",

        /**
         * could be: 6, 15, 30, 60
         */
        hoursInterval: "",

        /**
         * could be: up, nearest.
         */
        daysRound: "",

        /**
         * could be: 0.25, 0.5, 1.
         */
        daysInterval: "",

        /**
         * could be between [1, 24].
         */
        hoursInDay: "",

        revenueAccounts: [],
        allowPrepaidJobs: false,
        incomeInAdvAccount: null
      },
      billingDefaults: {
        /**
         * could be: prepaid, inArrears
         */
        paymentType: "prepaid",

        /**
         * @type Boolean
         */
        allowOverbilling: false,

        /**
         * could be: sameRate, companyDefault
         */
        jobFallbackRate: "sameRate",

        /**
         * could be: minutes, days.
         */
        billingUnitType: "",

        /**
         * @type Object
         */
        revenueAccount: null,
        companyRate: ""
      },
      reimbSettings: {
        rate: "",

        /**
         * could be: kilometres, miles.
         */
        measure: "",
        expenceAccount: null
      },
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
        scheduleView: "employee",
        scheduleRange: "week"
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
        email: { required, email },
        budgetCostRate: {
          decimal
        }
      }
    },

    jobs: {
      $each: {
        title: { required },
        code: { required }
      }
    },

    profiles: {
      $each: {
        name: { required }
      }
    },

    billingSettings: {
      hoursRound: { required },
      hoursInterval: { required },
      daysRound: { required },
      daysInterval: { required },
      hoursInDay: { required, decimal, between: between(1, 24) },
      revenueAccounts: {
        required: requiredIf(function() {
          return this.integration === "qbs";
        })
      },
      allowPrepaidJobs: {},
      incomeInAdvAccount: {
        required: requiredIf(function() {
          return (
            this.integration === "qbs" && this.billingSettings.allowPrepaidJobs
          );
        })
      }
    },

    billingDefaults: {
      paymentType: { required },
      allowOverbilling: { required },
      jobFallbackRate: { required },
      billingUnitType: { required },
      revenueAccount: {},
      companyRate: { required, decimal }
    },

    reimbSettings: {
      rate: { required, decimal },
      measure: { required },
      expenceAccount: {}
    },

    settings: {
      businessHours: { required },
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
    },

    jobList: function() {
      return this.getList("job").map(item => {
        return { key: item.id, value: item.title };
      });
    },

    profileList: function() {
      return this.getList("form").map(item => {
        return { key: item.id, value: item.name };
      });
    },

    vendorAccountList: function() {
      return this.vendorAccounts.map(item => {
        return { key: item.Id, value: `${item.GivenName} ${item.FamilyName}` };
      });
    },

    revenueAccList: function() {
      return this.revenueAccounts.map(item => {
        return { key: item.Id, value: item.Name };
      });
    },

    incomeInAdvAccList: function() {
      return this.incomeInAdvAccounts.map(item => {
        return { key: item.Id, value: item.Name };
      });
    },

    expenseAccList: function() {
      return this.expenseAccounts.map(item => {
        return { key: item.Id, value: item.Name };
      });
    }
  },

  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.loadAssets();
    });
  },

  beforeRouteLeave(to, from, next) {
    this.clear("team");
    this.clear("role");
    this.clear("user");
    this.clear("job");
    this.clear("form");
    next();
  },

  methods: {
    ...mapActions({
      list: "resource/list",
      create: "resource/create",
      update: "resource/update",
      clear: "resource/clearResource"
    }),

    loadAssets() {
      this.isLoading = true;
      this.loadingMsg = DEFAULT_LOADING_MSG;

      let p1 = this.list({ resource: "team" });
      let p2 = this.list({ resource: "role" });
      let p3 = this.list({ resource: "job" });
      let p4 = this.list({ resource: "form" });
      let p5 = this.checkQBsAuth().then(res => {
        console.log(res);
        this.qbsAuth = res.data.isTokenValid;
      });

      Promise.all([p1, p2, p3, p4, p5])
        .then(() => {
          if (this.step) this.currentStep = this.step;
        })
        .catch(err => {
          this.errors.push("something went wrong, please try again.");
        })
        .finally(() => {
          this.isLoading = false;
        });
    },

    /** validate wizard current step */
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
          this.$v.jobs.$touch();
          return !this.$v.jobs.$invalid;
        case 7:
          this.$v.billingSettings.$touch();
          return !this.$v.billingSettings.$invalid;
        case 8:
          this.$v.billingDefaults.$touch();
          return !this.$v.billingDefaults.$invalid;
        case 9:
          this.$v.reimbSettings.$touch();
          return !this.$v.reimbSettings.$invalid;
        case 10:
          this.$v.settings.$touch();
          return !this.$v.settings.$invalid;
        default:
          return true;
      }
    },

    /** process wizard current step */
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
          return this.createJobs();
        case 7:
          return this.saveBillingSettings();
        case 8:
          return this.saveBillingDefaults();
        case 9:
          return this.saveReimbSettings();
        case 10:
          return this.saveSettings();
        default:
          return Promise.resolve(true);
      }
    },

    /** load assets of wizard current step */
    loadCurrentStep() {
      switch (this.currentStep) {
        case 2:
          if (this.integration === "qbs") return this.saveClassesAndDepts();
          else return Promise.resolve(true);
        case 4:
          if (this.integration === "qbs") return this.loadEmployees();
          else return Promise.resolve(true);
        case 6:
          if (this.integration === "qbs") return this.loadCustomers();
          else return Promise.resolve(true);
        case 7:
          if (this.integration === "qbs") return this.loadAccounts();
          else return Promise.resolve(true);
        default:
          return Promise.resolve(true);
      }
    },

    checkQBsAuth() {
      return api.get({ url: "/api/quickbooks/hasvalidtoken" });
    },

    async loadEmployees() {
      this.loadingMsg = "Loading employees from QuickBooks...";
      await api
        .get({ url: QBs.EMPLOYEE })
        .then(res => {
          let list = res.data;
          if (list !== null) {
            list.forEach(employee => {
              this.users.push({
                qbsId: employee.Id,
                firstname: employee.GivenName,
                lastname: employee.FamilyName,
                email: employee.PrimaryEmailAddr
                  ? employee.PrimaryEmailAddr.Address
                  : "",
                teams: [],
                roles: [],
                vendor: null
              });
            });
          }
          return Promise.resolve(true);
        })
        .catch(err => {
          console.log(err);
          this.errors.push("can't load employees, please try again.");
          return Promise.reject(true);
        });

      this.loadingMsg = "Loading vendors from QuickBooks...";
      await api
        .get({ url: QBs.VENDOR })
        .then(res => {
          this.vendorAccounts = res.data ? res.data : [];
        })
        .catch(err => {
          console.log(err);
          this.errors.push("can't load vendors, please try again.");
          return Promise.reject(true);
        });
    },

    async loadCustomers() {
      this.loadingMsg = "Loading customers from QuickBooks...";
      await api
        .get({ url: QBs.CUSTOMER })
        .then(res => {
          let list = res.data;
          if (list !== null) {
            list.forEach(customer => {
              this.jobs.push({
                qbsId: customer.Id,
                title: customer.DisplayName,
                code: this.generateCode(4),
                parent: null,
                profile: null
              });
            });
          }
          return Promise.resolve(true);
        })
        .catch(err => {
          console.log(err);
          this.errors.push("can't load customers, please try again.");
          return Promise.reject(true);
        });
    },

    async loadAccounts() {
      this.loadingMsg = "Loading QuickBooks accounts...";
      let p1 = api
        .get({ url: QBs.ACCOUNT, query: { classification: "Revenue" } })
        .then(res => {
          this.revenueAccounts = res.data ? res.data : [];
        });

      let p2 = api
        .get({
          url: QBs.ACCOUNT,
          query: { classification: "Expense" }
        })
        .then(res => {
          this.expenseAccounts = res.data ? res.data : [];
        });

      let p3 = api
        .get({
          url: QBs.ACCOUNT,
          query: { accountType: "Other Current Liability" }
        })
        .then(res => {
          this.incomeInAdvAccounts = res.data ? res.data : [];
        });

      return await Promise.all([p1, p2, p3]);
    },

    async createTeams() {
      this.loadingMsg = "Creating teams...";

      for (let i = 0; i < this.teams.length; i++) {
        console.log(this.teams[i]);
        await this.createResource("team", this.teams[i]).catch(() => {
          this.errors.push(
            `An error occurred while creating ${this.teams[i].name}. team`
          );
        });
      }
      return Promise.resolve(true);
    },

    async createRoles() {
      this.loadingMsg = "Creating roles...";

      for (let i = 0; i < this.roles.length; i++) {
        console.log(this.roles[i]);
        await this.createResource("role", this.roles[i]).catch(() => {
          this.errors.push(
            `An error occurred while creating ${this.role[i].name}. role`
          );
        });
      }
      return Promise.resolve(true);
    },

    async createUsers() {
      for (let i = 0; i < this.users.length; i++) {
        // setup the form data
        let fullname = `${this.users[i].firstname} ${this.users[i].lastname}`;
        let payload = {};
        payload.firstname = this.users[i].firstname;
        payload.lastname = this.users[i].lastname;
        payload.email = this.users[i].email;
        payload.teams = this.users[i].teams.map(item => item.key);
        payload.roles = this.users[i].roles.map(item => item.key);

        if (this.integration === "qbs") {
          payload.qbs_id = this.users[i].qbsId;
          payload.qbs_vendor_id = null;
          payload.qbs_budgeted_cost_rate = this.users[i].budgetCostRate
            ? this.users[i].budgetCostRate
            : null;

          // create vendor if not selected and not exists
          if (
            !this.users[i].vendor &&
            !this.vendorAccounts.find(item => item.DisplayName === fullname)
          ) {
            this.loadingMsg = `Creating vendor for ${fullname}...`;
            await api
              .post({
                url: QBs.VENDOR,
                data: {
                  GivenName: this.users[i].firstname,
                  FamilyName: this.users[i].lastname
                }
              })
              .then(res => {
                console.log(res.data);
                if (res.data) payload.vendor_id = res.data.Id;
                else
                  this.errors.push(
                    `Vendor already exists with this name: ${this.users[i].firstname} ${this.users[i].lastname}.`
                  );
              })
              .catch(err => {
                console.log(err);
                this.errors.push(
                  `Failed while creating vendor for ${this.users[i].firstname} ${this.users[i].lastname}.`
                );
              });
          } else if (this.users[i].vendor) {
            payload.qbs_vendor_id = this.users[i].vendor.key;
          }
        }

        console.log(payload);

        this.loadingMsg = `Creating user accounts...`;

        await this.createResource("user", payload).catch(err => {
          this.errors.push(
            `An error occurred while creating ${fullname} account.`
          );
        });
      }
      return Promise.resolve(true);
    },

    async createProfiles() {
      this.loadingMsg = "Creating profiles...";

      for (let i = 0; i < this.profiles.length; i++) {
        // setup the form data
        let payload = {};
        payload.name = this.profiles[i].name;
        payload.billable = this.profiles[i].billable ? true : false;
        payload.schema = [];

        await this.createResource("form", payload).catch(() => {
          this.errors.push(`Failed while creating ${this.profiles[i].name}.`);
          return Promise.reject(false);
        });
      }
      return Promise.resolve(true);
    },

    async createJobs() {
      this.loadingMsg = "Creating jobs...";

      for (let i = 0; i < this.jobs.length; i++) {
        // setup the form data
        let payload = {};
        payload.title = this.jobs[i].title;
        payload.code = this.jobs[i].code.toString();
        payload.parent_id = this.jobs[i].parent
          ? this.jobs[i].parent.key
          : null;
        payload.form_id = this.jobs[i].profile
          ? this.jobs[i].profile.key
          : null;
        payload.isactive = true;

        if (this.integration === "qbs") {
          payload.qbs_id = this.jobs[i].qbsId;
        }

        await this.createResource("job", payload).catch(() => {
          this.errors.push(
            `An error occurred while creating ${this.job[i].title} job.`
          );
        });
      }
      return Promise.resolve(true);
    },

    async saveBillingSettings() {
      this.loadingMsg = "Saving the billing settings...";

      let form = {};
      form.billing_hours_round = this.billingSettings.hoursRound;
      form.billing_hours_interval = this.billingSettings.hoursInterval;
      form.billing_days_round = this.billingSettings.daysRound;
      form.billing_days_interval = this.billingSettings.daysInterval;
      form.billing_hours_in_day = this.billingSettings.hoursInDay;
      form.billing_revenue_accounts = this.revenueAccounts.filter(item =>
        this.billingSettings.revenueAccounts.find(acc => acc.key === item.Id)
      );
      form.billing_allow_prepaid_jobs = this.billingSettings.allowPrepaidJobs;
      form.billing_income_in_adv_account_id = this.billingSettings
        .allowPrepaidJobs
        ? this.billingSettings.incomeInAdvAccount.key
        : null;

      console.log(form);

      await this.update({ resource: "client", id: this.clientId, data: form })
        .then(data => {
          return Promise.resolve(true);
        })
        .catch(error => {
          return Promise.reject(false);
        });

      return Promise.resolve(true);
    },

    async saveBillingDefaults() {
      this.loadingMsg = "Saving billing defaults...";

      let form = {};
      form.billing_defaults_payment_type = this.billingDefaults.paymentType;
      form.billing_defaults_allow_over_billing = this.billingDefaults.allowOverbilling;
      form.billing_defaults_job_fallback_rate = this.billingDefaults.jobFallbackRate;
      form.billing_defaults_unit_type = this.billingDefaults.billingUnitType;
      form.billing_defaults_revenue_account_id = this.billingDefaults
        .revenueAccount
        ? this.billingDefaults.revenueAccount.key
        : null;
      form.billing_defaults_company_rate = this.billingDefaults.companyRate;

      console.log(form);

      await this.update({ resource: "client", id: this.clientId, data: form })
        .then(data => {
          return Promise.resolve(true);
        })
        .catch(error => {
          return Promise.reject(false);
        });
    },

    async saveReimbSettings() {
      this.loadingMsg = "Saving reimbursement settings...";

      let form = {};
      form.reimbursement_rate = this.reimbSettings.rate;
      form.reimbursement_measure = this.reimbSettings.measure;
      form.reimbursement_expence_account_id = this.reimbSettings.expenceAccount
        ? this.reimbSettings.expenceAccount.key
        : null;

      console.log(form);

      await this.update({ resource: "client", id: this.clientId, data: form })
        .then(data => {
          return Promise.resolve(true);
        })
        .catch(error => {
          return Promise.reject(false);
        });
    },

    async saveSettings() {
      this.loadingMsg = "Saving settings...";

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

    async saveClassesAndDepts() {
      let classes = [];
      let departments = [];

      if (this.integration !== "qbs") return Promise.resolve(true);

      this.loadingMsg = "Loading QuickBooks classes...";

      await api
        .get({ url: "/api/quickbooks/class" })
        .then(res => {
          classes = res.data !== null ? res.data : [];
          console.log(res);
        })
        .catch(err => {
          console.log(err);
          this.errors.push("can't load classes, please try again.");
          return Promise.reject(true);
        });

      this.loadingMsg = "Loading QuickBooks locations...";

      await api
        .get({ url: "/api/quickbooks/department" })
        .then(res => {
          departments = res.data !== null ? res.data : [];
          console.log(res);
        })
        .catch(err => {
          console.log(err);
          this.errors.push("can't load locations, please try again.");
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
          this.errors.push(
            "failed while saving classes and locations, please try again."
          );
          return Promise.reject(true);
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
            if (this.currentStep === this.lastStep)
              this.$router.push({ name: "home" });

            this.currentStep =
              this.currentStep < this.lastStep
                ? this.currentStep + 1
                : this.currentStep;

            this.loadCurrentStep()
              .then(() => {
                console.log("step loaded.");
                e.target.disabled = false;
              })
              .catch(err => {
                this.errors.push(err);
                this.errors.push(
                  `Failed while loading step, please try again.`
                );
                console.log(err.response);
              })
              .finally(() => {
                this.isLoading = false;
              });
          })
          .catch(err => {
            console.log(err);
            this.errors.push(err);
            this.errors.push(`Failed while processing step, please try again.`);
          });
      } else {
        e.target.disabled = false;
      }
    },

    onBack(e) {
      e.preventDefault();
      this.currentStep =
        this.currentStep > this.firstStep
          ? this.currentStep - 1
          : this.currentStep;
    },

    // ====== Helpers ======

    generateCode(length) {
      let rand = Math.random();
      rand = rand < 0.1 ? rand + 0.1 : rand;
      return Math.floor(rand * 10 ** length);
    }
  }
};
</script>

<style scoped>
.wrap-wizard {
  background-color: rgba(255, 255, 255, 0.9);
  padding: 30px;
  width: 50%;
  height: 100%;
  border-radius: 2px;
  position: relative;
}

.wrap-buttons {
  width: max-content;
}
</style>
