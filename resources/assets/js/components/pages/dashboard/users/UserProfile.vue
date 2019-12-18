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
          <button
            class="btn"
            :disabled="userId != id"
            @click="onSave"
          >
            Save
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

        <li v-if="userProfile">
          <router-link
            role="button"
            :class="[{ 'disabled' : this.contacts.length === 0 }, 'btn']"
            :aria-disabled="this.contacts.length === 0"
            :to="{ name:'contactList', params:{ opt:'in', col:'id', val: idToString(contacts) } }"
          >
            Contacts
            <ts-badge class="badge-light ml-auto">
              {{ contacts.length }}
            </ts-badge>
          </router-link>
        </li>
        <li v-if="userProfile">
          <router-link
            role="button"
            :class="[{ 'disabled' : this.emergencyContacts.length === 0 }, 'btn']"
            :aria-disabled="this.emergencyContacts.length === 0"
            :to="{ name:'contactList', params:{ opt:'in', col:'id', val: idToString(emergencyContacts) } }"
          >
            Emergencey Contacts
            <ts-badge class="badge-light ml-auto">
              {{ emergencyContacts.length }}
            </ts-badge>
          </router-link>
        </li>
      </ul>
    </template>

    <template slot="content">
      <!-- Profile info -->
      <form class="needs-validation">
        <fieldset :disabled="userId != id">
          <div class="form-row">
            <!-- profile picture -->
            <div class="form-group col-sm-4">
              <img
                v-show="!loadingAvatar"
                :src="form.avatar"
                @load="loadingAvatar = false"
                :class="['rounded mx-auto d-block', (loadingAvatar ? 'position-absolute':'position-relative')]"
                :style="{ opacity: (loadingAvatar) ? 0 : 1 }"
                alt="Profile picture"
                width="200"
                height="200"
              >
              <div
                v-if="loadingAvatar"
                class="d-flex flex-column justify-content-center align-items-center h-100"
              >
                <ts-spinner
                  :spinner-style="'grow'"
                  class="spinner-grow-lg"
                  :sr="'loading...'"
                />
                <strong>Loading profile picture...</strong>
              </div>
            </div>

            <!-- profile info -->
            <div class="form-group col-sm-8">
              <!-- first name -->
              <div class="form-group">
                <div class="form-row">
                  <label class="col-sm-3 col-form-lable">
                    First Name
                    <i class="field-required">*</i>
                  </label>
                  <div class="col-sm-9">
                    <input
                      v-model="$v.form.firstname.$model"
                      id="firstname"
                      type="text"
                      :class="[{'is-invalid' : $v.form.firstname.$error } ,'form-control']"
                      placeholder="user first name"
                    >
                    <div
                      class="invalid-feedback"
                      v-if="!$v.form.firstname.required"
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
                      v-model="$v.form.lastname.$model"
                      id="lastname"
                      type="text"
                      :class="[{'is-invalid' : $v.form.lastname.$error } ,'form-control']"
                      placeholder="user last name"
                    >
                    <div
                      class="invalid-feedback"
                      v-if="!$v.form.lastname.required"
                    >
                      Last name is required.
                    </div>
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
                      v-model="$v.form.email.$model"
                      id="email"
                      type="text"
                      :class="[{'is-invalid' : $v.form.email.$error } ,'form-control']"
                      placeholder="user e-mail"
                    >
                    <div
                      class="invalid-feedback"
                      v-if="!$v.form.email.required"
                    >
                      E-Mail is required.
                    </div>
                    <div
                      class="invalid-feedback"
                      v-if="!$v.form.email.email"
                    >
                      Should be a valid e-mail address.
                    </div>
                  </div>
                </div>
              </div>

              <!-- profile picture -->
              <div class="form-group">
                <div class="form-row">
                  <label class="col-sm-3 col-form-lable">Profile Picture</label>
                  <div class="col-sm-9">
                    <div class="custom-file">
                      <input
                        id="avatar"
                        name="avatar"
                        type="file"
                        class="custom-file-input"
                        @change="onAttachment"
                      >
                      <label
                        for="avatar"
                        class="custom-file-label"
                      >Choose Profile Picture</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </fieldset>
      </form>

      <ts-section
        title="Unavailable Hours"
        :devider="false"
        class="mb-3"
      >
        <!-- Unavailable hours calendar -->
        <ts-datescroller
          v-model="week"
          range="Week"
          :week-start="'Sun'"
          @change="loadCalendar"
        />
        <calendar
          :start-date="weekStart"
          :events="unavailables"
          :event-height="60"
          :view-type="'Weeks'"
          :disabled="id != userId"
          @range-selected="onRangeSelect"
          @event-clicked="onEventClicked"
        />
      </ts-section>

      <!-- Event Modal -->
      <ts-modal
        :show.sync="eventModal.show"
        :title="eventModal.status === 'create' ? 'Assign Unavailable':'Update Event'"
        @onHide="clearModal"
      >
        <form>
          <!-- Event Start -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-md-12 col-form-lable">Start</label>
              <div class="col-md-6">
                <input
                  v-model="recurring.event.startDate"
                  :class="[{'is-invalid' : $v.recurringEventStart.$error } ,'form-control']"
                  type="date"
                >
                <div
                  class="invalid-feedback"
                  v-if="!$v.recurringEventStart.lt"
                >
                  Start date must be before End date.
                </div>
              </div>
              <div class="col-md-6">
                <input
                  v-model="recurring.event.startTime"
                  class="form-control"
                  type="time"
                >
              </div>
            </div>
          </div>

          <!-- Event End -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-sm-12 col-form-lable">End</label>
              <div class="col-md-6">
                <input
                  v-model="recurring.event.endDate"
                  :class="[{'is-invalid' : $v.recurringEventEnd.$error } ,'form-control']"
                  type="date"
                >
                <div
                  class="invalid-feedback"
                  v-if="!$v.recurringEventEnd.gt"
                >
                  End date must be after Start date.
                </div>
              </div>
              <div class="col-md-6">
                <input
                  v-model="recurring.event.endTime"
                  class="form-control"
                  type="time"
                >
              </div>
            </div>
          </div>

          <!-- Repeat -->
          <div
            v-if="eventModal.status === 'create'"
            class="form-row"
          >
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

        <!-- Confirm Delete Modal -->
        <ts-modal
          :show.sync="confirmDeleteModal"
          :backdrop="false"
          size="sm"
          title="Confirm Delete"
        >
          <p>
            Are you sure you want to delete this event?
          </p>
          <template slot="footer">
            <button
              type="button"
              class="btn btn-light"
              @click="()=>{ confirmDeleteModal = false }"
            >
              Cancel
            </button>
            <button
              type="button"
              class="btn btn-success"
              @click="deleteEvent"
            >
              YES
            </button>
          </template>
        </ts-modal>

        <!-- modal footer -->
        <template slot="footer">
          <button
            type="button"
            class="btn btn-light"
            @click="()=>{ eventModal.show = false }"
          >
            Cancel
          </button>
          <button
            v-if="eventModal.status === 'create'"
            type="button"
            class="btn btn-primary"
            @click="createEvent"
          >
            Create
          </button>
          <button
            v-if="eventModal.status === 'update'"
            type="button"
            class="btn btn-danger"
            @click="onDeleteEvent"
          >
            Delete
          </button>
          <button
            v-if="eventModal.status === 'update'"
            type="button"
            class="btn btn-primary"
            @click="updateEvent"
          >
            Update
          </button>
        </template>
      </ts-modal>
    </template>
  </app-main>
</template>

<script>
import {
  required,
  email,
  sameAs,
  not,
  helpers
} from "vuelidate/lib/validators";
import moment from "moment";
import { mapGetters, mapActions } from "vuex";
import pageMixin from "../../../../mixins/page-mixin";
import DPCalendarVue from "../../../base/DPCalendar.vue";
import bsCustomFileInput from "bs-custom-file-input";

const lt = field =>
  helpers.withParams({ type: "lt", field: field }, function(value, parentVm) {
    return value < helpers.ref(field, this, parentVm);
  });

const gt = field =>
  helpers.withParams({ type: "gt", field: field }, function(value, parentVm) {
    return value > helpers.ref(field, this, parentVm);
  });

export default {
  name: "UserProfile",

  mixins: [pageMixin],

  components: {
    calendar: DPCalendarVue
  },

  props: ["id"],

  data() {
    return {
      loadingAvatar: true,
      eventModal: {
        show: false,
        status: "create"
      },
      confirmDeleteModal: false,
      week: {
        start: moment(),
        end: moment()
      },
      form: {
        firstname: "",
        lastname: "",
        email: "",
        currentEmail: "",
        avatar: "",
        attachment: ""
      },
      recurring: {
        repeat: "no repeat",
        times: 1,
        event: {
          id: "",
          startDate: "",
          startTime: "00:00",
          endDate: "",
          endTime: "00:00"
        }
      }
    };
  },

  validations: {
    form: {
      firstname: { required },
      lastname: { required },
      email: { required, email }
    },
    recurringEventStart: { lt: lt("recurringEventEnd") },
    recurringEventEnd: { gt: gt("recurringEventStart") }
  },

  watch: {
    id: function(val) {
      this.loadAssets();
    },

    userProfile: function(val) {
      this.populateForm(val);
    }
  },

  computed: {
    ...mapGetters({
      userId: "user/getId",
      getList: "resource/getList"
    }),

    userProfile: function() {
      if (!this.id) return null;
      return this.getList("user")[0];
    },

    weekStart: function() {
      return this.week.start.format("YYYY-MM-DD");
    },

    weekEnd: function() {
      return this.week.end.format("YYYY-MM-DD");
    },

    contacts: function() {
      if (!this.userProfile) return "";
      return this.userProfile.contacts.filter(item => item.group == "user");
    },

    emergencyContacts: function() {
      if (!this.userProfile) return "";
      return this.userProfile.contacts.filter(
        item => item.group == "emergency"
      );
    },

    unavailables: function() {
      return this.getList("schedule").map(item => {
        let start = new window.DayPilot.Date(item.start);
        let end = new window.DayPilot.Date(item.end);
        return {
          id: item.id,
          start: start,
          end: end,
          html: `Unavailable <br/>Start: ${start.toString(
            "hh:mm"
          )} <br/>End: ${end.toString("hh:mm")}`
        };
      });
    },

    recurringEventStart: function() {
      return `${this.recurring.event.startDate.toString("yyyy-MM-dd")} ${
        this.recurring.event.startTime
      }`;
    },

    recurringEventEnd: function() {
      return `${this.recurring.event.endDate.toString("yyyy-MM-dd")} ${
        this.recurring.event.endTime
      }`;
    },

    timesLable: function() {
      if (this.recurring.repeat == "weekly")
        return this.recurring.times > 1 ? "Weeks" : "Week";
      if (this.recurring.repeat == "monthly")
        return this.recurring.times > 1 ? "Months" : "Month";
      return "time";
    }
  },

  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.loadAssets();
    });
  },

  beforeRouteLeave(to, from, next) {
    this.clear("user");
    this.clear("schedule");
    next();
  },

  mounted() {
    bsCustomFileInput.init();
  },

  methods: {
    ...mapActions({
      clear: "resource/clearResource",
      list: "resource/list",
      create: "resource/create",
      update: "resource/update",
      delete: "resource/delete",
      updateUserInfo: "user/updateInfo",
      updateUserSettings: "user/updateSettings"
    }),

    async loadAssets() {
      this.startLoading();

      this.startLoading();
      this.loadingAvatar = true;

      let p1 = this.list({
        resource: "user",
        id: this.id,
        query: { eq: `id,${this.id}`, with: "contacts" }
      }).then(() => {
        this.loadingAvatar = false;
      });

      let p2 = this.list({
        resource: "schedule",
        query: this.$queryBuilder(
          null,
          null,
          ["user"],
          [
            { opt: "eq", col: "user_id", val: this.id },
            { opt: "eq", col: "event_type", val: "unavailable" },
            {
              opt: "inRange",
              col: "",
              val: `${this.weekStart},${this.weekEnd}`
            }
          ]
        )
      });

      await Promise.all([p1, p2])
        .then(() => {
          this.stopLoading();
        })
        .catch(error => {
          console.log(error);
          this.showMessage(error.message, "danger");
        });
    },

    loadCalendar() {
      this.startLoading();
      return this.list({
        resource: "schedule",
        query: this.$queryBuilder(
          null,
          null,
          ["user"],
          [
            { opt: "eq", col: "user_id", val: this.id },
            { opt: "eq", col: "event_type", val: "unavailable" },
            {
              opt: "inRange",
              col: "",
              val: `${this.weekStart},${this.weekEnd}`
            }
          ]
        )
      })
        .then(() => {
          this.stopLoading();
        })
        .catch(error => {
          this.showMessage(error.message, "danger");
        });
    },

    populateForm(profile) {
      this.form.firstname = profile.firstname;
      this.form.lastname = profile.lastname;
      this.form.email = this.form.currentEmail = profile.email;
      this.form.avatar = profile.avatar;
    },

    idToString(list) {
      if (!list) return "";
      return list
        .map(item => item.id)
        .reduce((acc, cur) => `${cur}${acc ? "," : ""}${acc}`, "");
    },

    //---------------------//
    // Calendar Events     //
    //---------------------//
    onRangeSelect(e) {
      this.recurring.event.startDate = e.start.slice(0, 10);
      this.recurring.event.endDate = e.end.slice(0, 10);

      this.eventModal.status = "create";
      this.eventModal.show = true;
    },

    onEventClicked(e) {
      let event = e.e.data;
      let start = event.start;
      let end = event.end;

      this.clearModal();

      this.recurring.event.id = event.id;
      this.recurring.event.startDate = start.toString("yyyy-MM-dd");
      this.recurring.event.startTime = start.toString("hh:mm");
      this.recurring.event.endDate = end.toString("yyyy-MM-dd");
      this.recurring.event.endTime = end.toString("hh:mm");

      this.eventModal.status = "update";
      this.eventModal.show = true;      
    },

    onEventMoved(e) {
      console.log(e);
    },

    onEventResized(e) {
      console.log(e);
    },

    onDeleteEvent(e) {
      // Show delete confirmation
      this.confirmDeleteModal = true;
    },

    async createEvent(e) {
      e.target.disabled = true;

      this.$v.recurringEventStart.$touch();
      this.$v.recurringEventEnd.$touch();
      if (
        this.$v.recurringEventStart.$invalid ||
        this.$v.recurringEventEnd.$invalid
      ) {
        e.target.disabled = false;
        return;
      }

      // populate the form data
      let form = {
        event_type: "unavailable",
        user_id: this.id,
        unavailables: []
      };

      let start = new window.DayPilot.Date(this.recurring.event.startDate);
      let startTime = this.recurring.event.startTime;
      let end = new window.DayPilot.Date(this.recurring.event.endDate);
      let endTime = this.recurring.event.endTime;

      // Create recurring events and push them to list
      if (this.recurring.repeat === "no repeat") {
        form.unavailables.push({
          start: `${start.toString("yyyy-MM-dd")}T${startTime}:00`,
          end: `${end.toString("yyyy-MM-dd")}T${endTime}:00`
        });
      } else if (this.recurring.repeat === "weekly") {
        for (let i = 1; i <= this.recurring.times; i++) {
          form.unavailables.push({
            start: `${start.toString("yyyy-MM-dd")}T${startTime}:00`,
            end: `${end.toString("yyyy-MM-dd")}T${endTime}:00`
          });
          start = start.addDays(7);
          end = end.addDays(7);
        }
      } else if (this.recurring.repeat === "monthly") {
        let endDate = end
          .firstDayOfMonth()
          .addMonths(Number(this.recurring.times));
        while (start < endDate) {
          form.unavailables.push({
            start: `${start.toString("yyyy-MM-dd")}T${startTime}:00`,
            end: `${end.toString("yyyy-MM-dd")}T${endTime}:00`
          });
          start = start.addDays(7);
          end = end.addDays(7);
        }
      }

      console.log(form);

      // Submit events to server
      await this.create({ resource: "schedule", data: form })
        .then(() => {
          console.log("events created successfuly.");
          this.showMessage("Unavailable hours created successfuly.", "success");
        })
        .catch(error => {
          console.log(error);
          this.showMessage(error.message, "danger");
        });

      e.target.disabled = false;
      this.eventModal.show = false;
    },

    async updateEvent(e) {
      e.target.disabled = true;

      this.$v.recurringEventStart.$touch();
      this.$v.recurringEventEnd.$touch();
      if (
        this.$v.recurringEventStart.$invalid ||
        this.$v.recurringEventEnd.$invalid
      ) {
        e.target.disabled = false;
        return;
      }

      let form = {};
      form.start = this.recurringEventStart;
      form.end = this.recurringEventEnd;

      await this.update({ resource: "schedule", id: this.recurring.event.id, data: form })
        .then(() => {
          this.showMessage("Unavailable hours updated successfuly.", "success");
        })
        .catch(error => {
          console.log(error);
          this.showMessage(error.message, "danger");
        });

      e.target.disabled = false;
      this.eventModal.show = false;
    },

    deleteEvent(e) {
      this.confirmDeleteModal = false;
      this.eventModal.show = false;

      this.delete({ resource: "schedule", id: this.recurring.event.id })
        .then(() => {
          this.showMessage("Unavailable hours deleted successfuly.", "success");
        })
        .catch(error => {
          console.log(error);
          this.showMessage(error.message, "danger");
        });
    },

    clearModal() {
      this.recurring.repeat = "no repeat";
      this.recurring.times = 1;
      this.recurring.event.startDate = "";
      this.recurring.event.startTime = "00:00";
      this.recurring.event.endDate = "";
      this.recurring.event.endTime = "00:00";
    },

    //---------------------//
    // User Profile        //
    //---------------------//
    onSave(e) {
      e.preventDefault();
      e.target.disabled = true;

      // validate
      this.$v.$touch();
      if (this.$v.$invalid) {
        e.target.disabled = false;
        return;
      }

      // populate form data
      let form = new FormData();
      form.append("_method", "PUT");
      form.append("firstname", this.form.firstname);
      form.append("lastname", this.form.lastname);
      if (this.form.email !== this.form.currentEmail)
        form.append("email", this.form.email);
      form.append("avatar", this.form.attachment);

      this.update({
        resource: "user",
        id: this.id,
        data: form,
        method: "POST",
        hasAttachments: true
      })
        .then(data => {
          this.populateForm(data);
          if (this.userId === data.id) {
            this.updateUserInfo(data);
            this.updateUserSettings(data.meta);
          }
          this.showMessage(`Profile updated successfuly.`, "success");
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

    onAttachment(e) {
      console.log(e);
      this.form.attachment = e.target.files[0];
    },

    onCancel(e) {
      e.preventDefault();
      this.$router.go(-1);
    }
  }
};
</script>

<style scoped>
</style>