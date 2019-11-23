<template>
  <div class="wrap-wizard md-layout md-alignment-center-center">
    <!-- Client Wizard -->
    <stepper>
      <template slot="nav">
        <step-icon
          v-model="currentStep"
          :step-number="1"
          :lable="'Step 1'"
        />
        <step-icon
          v-model="currentStep"
          :step-number="2"
          :lable="'Step 2'"
        />
        <step-icon
          v-model="currentStep"
          :step-number="3"
          :lable="'Step 3'"
        />
        <step-icon
          v-model="currentStep"
          :step-number="4"
          :lable="'Step 4'"
        />
      </template>

      <template slot="body">
        <!-- step 0 -->
        <step-body
          v-model="currentStep"
          :step-number="0"
        >
          <div class="mt-4">
            <h3>Welcome to TicSol.</h3>
            <p>
              Hi
              <b>{{ userFirstname }}</b>,
              This wizard will guide you through the initial setup to get you up and running as quickly as possible.
            </p>
          </div>
        </step-body>

        <!-- step 1 - User Info -->
        <step-body
          v-model="currentStep"
          :step-number="1"
        >
          <div class="mt-4">
            <!-- validation summary -->
            <div
              v-if="$v.user.$error"
              class="alert alert-danger"
              role="alert"
            >
              <b class="alert-heading">whoops:</b>
              <p
                class="mb-0"
              >
                Your profile information have some errors. Fix them to be able to continue.
              </p>
            </div>

            <h3>Step 1 - User Info</h3>
            <p class="text-justify">
              Please fill the required information about your profile.
            </p>

            <!-- first name -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">
                  First Name
                  <i class="field-required">*</i>
                </label>
                <div class="col-sm-9">
                  <input
                    v-model="$v.user.firstname.$model"
                    id="firstname"
                    type="text"
                    :class="[{'is-invalid' : $v.user.firstname.$error } ,'form-control']"
                    placeholder="user first name"
                  >
                  <div
                    class="invalid-feedback"
                    v-if="!$v.user.firstname.required"
                  >
                    First name is required.
                  </div>
                </div>
              </div>
            </div>

            <!-- last name -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">
                  Last Name
                  <i class="field-required">*</i>
                </label>
                <div class="col-sm-9">
                  <input
                    v-model="$v.user.lastname.$model"
                    id="lastname"
                    type="text"
                    :class="[{'is-invalid' : $v.user.lastname.$error } ,'form-control']"
                    placeholder="user last name"
                  >
                  <div
                    class="invalid-feedback"
                    v-if="!$v.user.lastname.required"
                  >
                    Last name is required.
                  </div>
                </div>
              </div>
            </div>

            <!-- e-mail
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">
                  E-Mail
                  <i class="field-required">*</i>
                </label>
                <div class="col-sm-9">
                  <input
                    v-model="$v.user.email.$model"
                    id="email"
                    type="text"
                    :class="[{'is-invalid' : $v.user.email.$error } ,'form-control']"
                    placeholder="user e-mail"
                  >
                  <div
                    class="invalid-feedback"
                    v-if="!$v.user.email.required"
                  >
                    E-Mail is required.
                  </div>
                  <div
                    class="invalid-feedback"
                    v-if="!$v.user.email.email"
                  >
                    Should be a valid e-mail address.
                  </div>
                </div>
              </div>
            </div> -->
          </div>
        </step-body>

        <!-- step 2 - Contact -->
        <step-body
          v-model="currentStep"
          :step-number="2"
        >
          <div class="mt-4">
            <!-- validation summary -->
            <div
              v-if="$v.contact.$error"
              class="alert alert-danger"
              role="alert"
            >
              <b class="alert-heading">whoops:</b>
              <p class="mb-0">
                Your contact have some errors. Fix them to be able to continue.
              </p>
            </div>

            <h3>Step 2 - Contact</h3>
            <p
              class="text-justify"
            >
              Add your contact here with related addreses. You can add more contacts later from contacts section.
            </p>

            <!-- First Name -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">
                  First Name
                  <i class="field-required">*</i>
                </label>
                <div class="col-sm-9">
                  <input
                    v-model="$v.contact.firstname.$model"
                    type="text"
                    :class="[{'is-invalid' : $v.contact.firstname.$error } ,'form-control']"
                    id="firstname"
                    placeholder="contact first name"
                  >
                  <div
                    class="invalid-feedback"
                    v-if="!$v.contact.firstname.required"
                  >
                    First name is required.
                  </div>
                </div>
              </div>
            </div>

            <!-- Last Name -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">
                  Last Name
                  <i class="field-required">*</i>
                </label>
                <div class="col-sm-9">
                  <input
                    v-model="$v.contact.lastname.$model"
                    type="text"
                    :class="[{'is-invalid' : $v.contact.lastname.$error } ,'form-control']"
                    id="lastname"
                    placeholder="contact last name"
                  >
                  <div
                    class="invalid-feedback"
                    v-if="!$v.contact.lastname.required"
                  >
                    Last name is required.
                  </div>
                </div>
              </div>
            </div>

            <!-- Tel Num -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">Tel Number</label>
                <div class="col-sm-9">
                  <input
                    v-model="contact.telephone"
                    type="tel"
                    class="form-control"
                    id="telephone"
                    placeholder="contact tel number"
                  >
                </div>
              </div>
            </div>

            <!-- Mobile Num -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">
                  Mobile Number
                  <i class="field-required">*</i>
                </label>
                <div class="col-sm-9">
                  <input
                    v-model="$v.contact.mobilephone.$model"
                    type="tel"
                    :class="[{'is-invalid' : $v.contact.mobilephone.$error } ,'form-control']"
                    id="mobilephone"
                    placeholder="contact mobile number"
                  >
                  <div
                    class="invalid-feedback"
                    v-if="!$v.contact.mobilephone.required"
                  >
                    Mobile number is required.
                  </div>
                </div>
              </div>
            </div>

            <ts-grid
              v-model="contact.addresses"
              :columns="addressColumns"
              :has-toolbar="false"
              @inserted="place = {}"
              @modalHide="place = {}"
            >
              <template slot-scope="{ item }">
                <td>{{ item.number }}</td>
                <td>{{ item.street }}</td>
                <td>{{ item.state }}</td>
                <td>{{ item.country }}</td>
              </template>
              <template
                slot="grid-modal"
                slot-scope="{ item }"
              >
                <div class="p-2">
                  <!-- google places -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-3 col-form-lable">Find Address</label>
                      <div class="col-sm-9">
                        <goole-places
                          :value="place"
                          @input="placeChange($event, item)"
                        />
                      </div>
                    </div>
                  </div>
                  <hr>

                  <!-- Unit -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-2 col-form-lable">Unit</label>
                      <div class="col-sm-10">
                        <input
                          v-model="item.unit"
                          type="text"
                          placeholder="please enter unit..."
                          class="form-control"
                          id="unit"
                        >
                      </div>
                    </div>
                  </div>

                  <!-- Number -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-2 col-form-lable">
                        Number
                        <i class="field-required">*</i>
                      </label>
                      <div class="col-sm-10">
                        <input
                          v-model="item.number"
                          type="text"
                          placeholder="please enter number..."
                          class="form-control"
                          id="number"
                        >
                      </div>
                    </div>
                  </div>

                  <!-- Street -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-2 col-form-lable">
                        Street
                        <i class="field-required">*</i>
                      </label>
                      <div class="col-sm-10">
                        <input
                          v-model="item.street"
                          type="text"
                          placeholder="please enter street..."
                          class="form-control"
                          id="street"
                        >
                      </div>
                    </div>
                  </div>

                  <!-- Suburb -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-2 col-form-lable">
                        Suburb
                        <i class="field-required">*</i>
                      </label>
                      <div class="col-sm-10">
                        <input
                          v-model="item.suburb"
                          type="text"
                          placeholder="please enter suburb..."
                          class="form-control"
                          id="suburb"
                        >
                      </div>
                    </div>
                  </div>

                  <!-- State -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-2 col-form-lable">
                        State
                        <i class="field-required">*</i>
                      </label>
                      <div class="col-sm-10">
                        <input
                          v-model="item.state"
                          type="text"
                          placeholder="please enter state..."
                          class="form-control"
                          id="unit"
                        >
                      </div>
                    </div>
                  </div>

                  <!-- Country -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-2 col-form-lable">
                        Country
                        <i class="field-required">*</i>
                      </label>
                      <div class="col-sm-10">
                        <input
                          v-model="item.country"
                          type="text"
                          placeholder="please enter country..."
                          class="form-control"
                          id="country"
                        >
                      </div>
                    </div>
                  </div>

                  <!-- Post Code -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-2 col-form-lable">
                        Post Code
                        <i class="field-required">*</i>
                      </label>
                      <div class="col-sm-10">
                        <input
                          v-model="item.postcode"
                          type="text"
                          placeholder="please enter post code..."
                          class="form-control"
                          id="postcode"
                        >
                      </div>
                    </div>
                  </div>
                </div>
              </template>
            </ts-grid>
          </div>
        </step-body>

        <!-- step 3 - Emergency -->
        <step-body
          v-model="currentStep"
          :step-number="3"
        >
          <div class="mt-4">
            <!-- validation summary -->
            <div
              v-if="$v.emergency.$error"
              class="alert alert-danger"
              role="alert"
            >
              <b class="alert-heading">whoops:</b>
              <p
                class="mb-0"
              >
                Your emergency contact have some errors. Fix them to be able to continue.
              </p>
            </div>

            <h3>Step 3 - Emergency Contact</h3>
            <p class="text-justify">
              Add your emergency contact here with related addreses. You can add more
              contacts later from contacts section.
            </p>

            <!-- First Name -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">
                  First Name
                  <i class="field-required">*</i>
                </label>
                <div class="col-sm-9">
                  <input
                    v-model="$v.emergency.firstname.$model"
                    type="text"
                    :class="[{'is-invalid' : $v.emergency.firstname.$error } ,'form-control']"
                    id="firstname"
                    placeholder="contact first name"
                  >
                  <div
                    class="invalid-feedback"
                    v-if="!$v.emergency.firstname.required"
                  >
                    First name is required.
                  </div>
                </div>
              </div>
            </div>

            <!-- Last Name -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">
                  Last Name
                  <i class="field-required">*</i>
                </label>
                <div class="col-sm-9">
                  <input
                    v-model="$v.emergency.lastname.$model"
                    type="text"
                    :class="[{'is-invalid' : $v.emergency.lastname.$error } ,'form-control']"
                    id="lastname"
                    placeholder="contact last name"
                  >
                  <div
                    class="invalid-feedback"
                    v-if="!$v.emergency.lastname.required"
                  >
                    Last name is required.
                  </div>
                </div>
              </div>
            </div>

            <!-- Tel Num -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">Tel Number</label>
                <div class="col-sm-9">
                  <input
                    v-model="emergency.telephone"
                    type="tel"
                    class="form-control"
                    id="telephone"
                    placeholder="contact tel number"
                  >
                </div>
              </div>
            </div>

            <!-- Mobile Num -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">
                  Mobile Number
                  <i class="field-required">*</i>
                </label>
                <div class="col-sm-9">
                  <input
                    v-model="$v.emergency.mobilephone.$model"
                    type="tel"
                    :class="[{'is-invalid' : $v.emergency.mobilephone.$error } ,'form-control']"
                    id="mobilephone"
                    placeholder="contact mobile number"
                  >
                  <div
                    class="invalid-feedback"
                    v-if="!$v.emergency.mobilephone.required"
                  >
                    Mobile number is required.
                  </div>
                </div>
              </div>
            </div>

            <ts-grid
              v-model="emergency.addresses"
              :columns="addressColumns"
              :has-toolbar="false"
              @inserted="place = {}"
              @modalHide="place = {}"
            >
              <template slot-scope="{ item }">
                <td>{{ item.number }}</td>
                <td>{{ item.street }}</td>
                <td>{{ item.state }}</td>
                <td>{{ item.country }}</td>
              </template>
              <template
                slot="grid-modal"
                slot-scope="{ item }"
              >
                <div class="p-2">
                  <!-- google places -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-3 col-form-lable">Find Address</label>
                      <div class="col-sm-9">
                        <goole-places
                          :value="place"
                          @input="placeChange($event, item)"
                        />
                      </div>
                    </div>
                  </div>
                  <hr>

                  <!-- Unit -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-2 col-form-lable">Unit</label>
                      <div class="col-sm-10">
                        <input
                          v-model="item.unit"
                          type="text"
                          placeholder="please enter unit..."
                          class="form-control"
                          id="unit"
                        >
                      </div>
                    </div>
                  </div>

                  <!-- Number -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-2 col-form-lable">
                        Number
                        <i class="field-required">*</i>
                      </label>
                      <div class="col-sm-10">
                        <input
                          v-model="item.number"
                          type="text"
                          placeholder="please enter number..."
                          class="form-control"
                          id="number"
                        >
                      </div>
                    </div>
                  </div>

                  <!-- Street -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-2 col-form-lable">
                        Street
                        <i class="field-required">*</i>
                      </label>
                      <div class="col-sm-10">
                        <input
                          v-model="item.street"
                          type="text"
                          placeholder="please enter street..."
                          class="form-control"
                          id="street"
                        >
                      </div>
                    </div>
                  </div>

                  <!-- Suburb -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-2 col-form-lable">
                        Suburb
                        <i class="field-required">*</i>
                      </label>
                      <div class="col-sm-10">
                        <input
                          v-model="item.suburb"
                          type="text"
                          placeholder="please enter suburb..."
                          class="form-control"
                          id="suburb"
                        >
                      </div>
                    </div>
                  </div>

                  <!-- State -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-2 col-form-lable">
                        State
                        <i class="field-required">*</i>
                      </label>
                      <div class="col-sm-10">
                        <input
                          v-model="item.state"
                          type="text"
                          placeholder="please enter state..."
                          class="form-control"
                          id="unit"
                        >
                      </div>
                    </div>
                  </div>

                  <!-- Country -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-2 col-form-lable">
                        Country
                        <i class="field-required">*</i>
                      </label>
                      <div class="col-sm-10">
                        <input
                          v-model="item.country"
                          type="text"
                          placeholder="please enter country..."
                          class="form-control"
                          id="country"
                        >
                      </div>
                    </div>
                  </div>

                  <!-- Post Code -->
                  <div class="form-group">
                    <div class="form-row">
                      <label class="col-sm-2 col-form-lable">
                        Post Code
                        <i class="field-required">*</i>
                      </label>
                      <div class="col-sm-10">
                        <input
                          v-model="item.postcode"
                          type="text"
                          placeholder="please enter post code..."
                          class="form-control"
                          id="postcode"
                        >
                      </div>
                    </div>
                  </div>
                </div>
              </template>
            </ts-grid>
          </div>
        </step-body>

        <!-- step 4 - Unavailable -->
        <step-body
          v-model="currentStep"
          :step-number="4"
        >
          <div class="mt-4">
            <!-- validation summary -->
            <div
              v-if="$v.unavailables.$error"
              class="alert alert-danger"
              role="alert"
            >
              <b class="alert-heading">whoops:</b>
              <p
                class="mb-0"
              >
                Your unavailables list have some errors. Fix them to be able to continue.
              </p>
            </div>

            <h3>Step 4 - Unavailable Hours</h3>
            <p class="text-justify">
              Please provide your unavailable days/hours by selecting them on the calendar.
              You can add or edit your unavailable days/hours later from your profile.
            </p>

            <!-- Unavailable hours calendar -->
            <ts-datescroller
              v-model="week"
              range="Week"
              :week-start="'Sun'"
            />
            <calendar
              :start-date="weekStart"
              :events="unavailables"
              :view-type="'Weeks'"
              @range-selected="onRangeSelect"
            />

            <!-- Create Event Modal -->
            <ts-modal
              :show.sync="createEventModal"
              @onHide="clearModal"
              title="Assign Unavailable"
            >
              <form>
                <div class="form-row">
                  <div class="form-group col-sm-6">
                    <div class="form-row">
                      <label class="col-sm-12 col-form-lable">Start</label>
                      <div class="col">
                        <input
                          v-model="recurring.event.start"
                          class="form-control"
                          type="date"
                        >
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-sm-6">
                    <div class="form-row">
                      <label class="col-sm-12 col-form-lable">End</label>
                      <div class="col">
                        <input
                          v-model="recurring.event.end"
                          class="form-control"
                          type="date"
                        >
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-sm-6">
                    <div class="form-row">
                      <label class="col-sm-12 col-form-lable">Repeat</label>
                      <div class="col">
                        <select
                          class="custom-select"
                          v-model="recurring.repeat"
                          @change="()=>{ recurring.times = 1 }"
                        >
                          <option value="no repeat">
                            No Repeat
                          </option>
                          <option value="weekly">
                            Weekly
                          </option>
                          <option value="monthly">
                            Monthly
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-sm-6">
                    <div class="form-row">
                      <label
                        class="col-sm-12 col-form-lable"
                      >For {{ recurring.times }} {{ this.timesLable }}</label>
                      <div class="col">
                        <input
                          v-model="recurring.times"
                          type="range"
                          class="custom-range"
                          min="1"
                          :max="recurring.repeat === 'weekly' ? 20 : 5"
                          step="1"
                          :disabled="recurring.repeat === 'no repeat'"
                        >
                      </div>
                    </div>
                  </div>
                </div>
              </form>
              <template slot="footer">
                <button
                  type="button"
                  class="btn btn-primary"
                  @click="onSubmitEvents"
                >
                  Save
                </button>
                <button
                  type="button"
                  class="btn btn-light"
                  @click="()=>{ createEventModal = false }"
                >
                  Cancel
                </button>
              </template>
            </ts-modal>
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
import { mapGetters, mapActions } from "vuex";
import { required, email } from "vuelidate/lib/validators";

import moment from "moment";
import Stepper from "../../base/formStepper/Stepper";
import StepBody from "../../base/formStepper/StepBody";
import StepIcon from "../../base/formStepper/StepIcon";
import LoadingScreen from "../../app/AppLoadingScreen";
import GooglePlaces from "../../base/GooglePlaces";
import DPCalendarVue from "../../base/DPCalendar.vue";

export default {
  name: "UserWizard",

  components: {
    stepper: Stepper,
    "step-body": StepBody,
    "step-icon": StepIcon,
    "goole-places": GooglePlaces,    
    "loading-screen": LoadingScreen,
    calendar: DPCalendarVue
  },

  data() {
    return {
      place: {},
      isLoading: false,
      loadingMsg: "",
      currentStep: 0,
      firstStep: 0,
      lastStep: 4,

      // Errors stack
      errors: [],

      // calendar vars
      createEventModal: false,
      confirmDeleteModal: false,
      eventInfoModal: false,
      week: {
        start: moment(),
        end: moment()
      },
      recurring: {
        repeat: "no repeat",
        times: 1,
        event: {
          start: "",
          end: ""
        }
      },

      user: {
        firstname: "",
        lastname: "",
        //email: ""
      },
      contact: {
        group: "user",
        firstname: "",
        lastname: "",
        telephone: "",
        mobilephone: "",
        addresses: []
      },
      emergency: {
        group: "emergency",
        firstname: "",
        lastname: "",
        telephone: "",
        mobilephone: "",
        addresses: []
      },
      unavailables: [],
      addressColumns: [
        { key: 1, value: "Number" },
        { key: 2, value: "Street" },
        { key: 5, value: "State" },
        { key: 6, value: "Country" }
      ]
    };
  },

  validations: {
    user: {
      firstname: { required },
      lastname: { required },
      //email: { required, email }
    },

    contact: {
      firstname: { required },
      lastname: { required },
      telephone: {},
      mobilephone: { required },
      addresses: {
        $each: {
          number: { required },
          street: { required },
          suburb: { required },
          state: { required },
          country: { required },
          postcode: { required }
        }
      }
    },

    emergency: {
      firstname: { required },
      lastname: { required },
      telephone: {},
      mobilephone: { required },
      addresses: {
        $each: {
          number: { required },
          street: { required },
          suburb: { required },
          state: { required },
          country: { required },
          postcode: { required }
        }
      }
    },

    unavailables: {
      $each: {
        start: { required },
        end: { required }
      }
    }
  },

  computed: {
    ...mapGetters({
      userId: "user/getId",
      userFirstname: "user/getFirstname",
      getList: "resource/getList"
    }),

    weekStart: function() {
      return this.week.start.format("YYYY-MM-DD");
    },

    timesLable: function() {
      if (this.recurring.repeat == "weekly")
        return this.recurring.times > 1 ? "Weeks" : "Week";
      if (this.recurring.repeat == "monthly")
        return this.recurring.times > 1 ? "Months" : "Month";
      return "time";
    },

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
    this.clear("user");
    this.clear("contact");
    this.clear("schedule");
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
      this.loadingMsg = "Loading, Please wait...";

      this.list({ resource: "user", query: { eq: `id,${this.userId}` } }).then(
        data => {
          let user = data[0];
          this.user.firstname = user.firstname;
          this.user.lastname = user.lastname;
          //this.user.email = user.email;

          this.contact.firstname = user.firstname;
          this.contact.lastname = user.lastname;

          this.isLoading = false;
        }
      );
    },

    validateStep() {
      switch (this.currentStep) {
        case 0:
          return true;
        case 1:
          this.$v.user.$touch();
          return !this.$v.user.$invalid;
        case 2:
          this.$v.contact.$touch();
          return !this.$v.contact.$invalid;
        case 3:
          this.$v.emergency.$touch();
          return !this.$v.emergency.$invalid;
        case 4:
          this.$v.unavailables.$touch();
          return !this.$v.unavailables.$invalid;
        default:
          return false;
      }
    },

    processStep() {
      switch (this.currentStep) {        
        case 1:
          return this.saveInfo();
        case 2:
          return this.createContact();
        case 3:
          return this.createEmergency();
        case 4:
          return this.createUnavailables();
        default:
          return Promise.resolve(true);
      }
    },

    async saveInfo() {
      await this.update({ resource: "user", id: this.userId, data: this.user });
      return Promise.resolve(true);
    },

    async createContact() {
      await this.create({ resource: "contact", data: this.contact });
      return Promise.resolve(true);
    },

    async createEmergency() {
      await this.create({ resource: "contact", data: this.emergency });
      return Promise.resolve(true);
    },

    async createUnavailables() {      
      let form = {
        event_type: "unavailable",
        user_id: this.userId,
        unavailables: this.unavailables.map(item => {
          return { start: item.start, end: item.end };
        })
      };

      if(form.unavailables.length > 0) 
        await this.create({ resource: "schedule", data: form });
      return Promise.resolve(true);
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

    /**
     * Google place change event handler.
     */
    placeChange(place, item) {
      this.$set(item, "number", place.street_number);
      this.$set(item, "street", place.route);
      this.$set(item, "suburb", place.locality);
      this.$set(item, "state", place.administrative_area_level_1);
      this.$set(item, "country", place.country);
      this.$set(item, "postcode", place.postal_code);
    },

    /**
     * Calendar range select handler.
     */
    onRangeSelect(e) {
      this.recurring.event.start = e.start.slice(0, 10);
      this.recurring.event.end = e.end.slice(0, 10);
      this.createEventModal = true;
    },

    onSubmitEvents(e) {
      let start = new window.DayPilot.Date(this.recurring.event.start);
      let end = new window.DayPilot.Date(this.recurring.event.end);

      // Create recuring events and push them to list
      if (this.recurring.repeat === "no repeat") {
        this.unavailables.push({
          start: start.toString(),
          end: end.toString(),
          text: "Unavailable"
        });
      } else if (this.recurring.repeat === "weekly") {
        for (let i = 1; i <= this.recurring.times; i++) {
          this.unavailables.push({
            start: start.toString(),
            end: end.toString(),
            text: "Unavailable"
          });
          start = start.addDays(7);
          end = end.addDays(7);
        }
      } else if (this.recurring.repeat === "monthly") {
        let endDate = end
          .firstDayOfMonth()
          .addMonths(Number(this.recurring.times));
        while (start < endDate) {
          this.unavailables.push({
            start: start.toString(),
            end: end.toString(),
            text: "Unavailable"
          });
          start = start.addDays(7);
          end = end.addDays(7);
        }
      }

      this.createEventModal = false;
    },

    clearModal() {
      this.recurring.repeat = "no repeat";
      this.recurring.times = 1;
      this.recurring.event.start = "";
      this.recurring.event.end = "";
    },

    async onNext(e) {
      e.preventDefault();
      e.target.disabled = true;

      if (this.validateStep()) {
        this.isLoading = true;
        this.processStep().then(() => {
          e.target.disabled = false;
          this.isLoading = false;

          if (this.currentStep === this.lastStep) this.$router.push({name:'home'});

          this.currentStep =
            this.currentStep < this.lastStep
              ? this.currentStep + 1
              : this.currentStep;
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
