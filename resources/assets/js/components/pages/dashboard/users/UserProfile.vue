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
      </form>

      <br>
      <hr>

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
        :view-type="'Weeks'"
        :disabled="id != userId"
        :message="calendarMsg"
        @range-selected="onRangeSelect"
        @event-clicked="onEventClicked"
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

      <!-- Event info modal -->
      <ts-modal
        :show.sync="eventInfoModal"
        title="Event Details"
      >
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-3 col-form-lable">Start</label>
            <div class="col">
              <input
                v-model="selectedEvent.start"
                class="form-control"
                type="date"
              >
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-3 col-form-lable">End</label>
            <div class="col">
              <input
                v-model="selectedEvent.end"
                class="form-control"
                type="date"
              >
            </div>
          </div>
        </div>
        <template slot="footer">
          <button
            type="button"
            class="btn btn-light"
            @click="()=>{ eventInfoModal = false }"
          >
            Cancel
          </button>
          <button
            type="button"
            class="btn btn-danger"
            @click="onDeleteEvent"
          >
            Delete
          </button>
        </template>
      </ts-modal>

      <!-- Confirm Delete Modal -->
      <ts-modal
        :show.sync="confirmDeleteModal"
        title="Confirm Delete"
      >
        <p>
          Are you sure you want to delete this event?
          <b>
            Unavailable
            {{ selectedEvent.startDate.toString("MMM dd") }} till
            {{ selectedEvent.endDate.toString("MMM dd") }}
          </b>?
        </p>
        <template slot="footer">
          <button
            type="button"
            class="btn btn-danger"
          >
            Delete
          </button>
          <button
            type="button"
            class="btn btn-light"
          >
            Cancel
          </button>
        </template>
      </ts-modal>
    </template>
  </app-main>
</template>

<script>
import moment from "moment";
import { mapGetters, mapActions } from "vuex";
import { required, email } from "vuelidate/lib/validators";
import pageMixin from "../../../../mixins/page-mixin";
import DPCalendarVue from "../../../base/DPCalendar.vue";

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
      createEventModal: false,
      confirmDeleteModal: false,
      eventInfoModal: false,
      calendarMsg: { msg: "", delay: 0 },
      week: {
        start: moment(),
        end: moment()
      },
      form: {
        firstname: "",
        lastname: "",
        email: "",
        avatar: "",
        attachment: ""
      },
      recurring: {
        repeat: "no repeat",
        times: 1,
        event: {
          start: "",
          end: ""
        }
      },
      selectedEvent: {
        startDate: window.DayPilot.Date.today(),
        endDate: window.DayPilot.Date.today(),
        start: "",
        end: ""
      }
    };
  },

  validations: {
    form: {
      firstname: { required },
      lastname: { required },
      email: { required, email }
    }
  },

  watch: {
    id: function(val) {
      this.loadAssets();
    },

    userProfile: function(val){
      this.populateForm(val);
    }
  },

  computed: {
    ...mapGetters({
      userId: "user/getId",
      getList: "resource/getList"
    }),

    userProfile: function() {
      if(!this.id) return null;
      return this.getList("user")[0];
    },

    weekStart: function() {
      return this.week.start.format("YYYY-MM-DD");
    },

    weekEnd: function() {
      return this.week.end.format("YYYY-MM-DD");
    },

    contacts: function() {
      if(!this.userProfile) return "";
      return this.userProfile.contacts
        .filter(item => item.group == "user");
    },

    emergencyContacts: function() {
      if(!this.userProfile) return "";
      return this.userProfile.contacts
        .filter(item => item.group == "emergency");
    },

    unavailables: function() {
      return this.getList("schedule").map(item => {
        return {
          id: item.id,
          start: item.start,
          end: item.end,
          text: "Unavailable"
        };
      });
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

  methods: {
    ...mapActions({
      clear: "resource/clearResource",
      list: "resource/list",
      create: "resource/create",
      update: "resource/update",
      delete: "resource/delete",
      updateUserInfo: "user/updateInfo"
    }),

    loadAssets() {
      this.startLoading();
      let p1 = this.loadProfile();
      let p2 = this.loadCalendar();

      Promise.all([p1, p2])
        .then(() => {
          this.stopLoading();
        })
        .catch(error => {
          console.log(error);
          this.showMessage(error.message, "danger");
        });
    },

    loadProfile() {
      this.loadingAvatar = true;
      return this.list({
        resource: "user",
        id: this.id,
        query: { eq:`id,${this.id}`, with: "contacts" }
      });
    },

    loadCalendar() {
      this.calendarMsg = { msg: "Loading, please wait...", delay: 2000 };
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
      }).catch(error => {
        this.showMessage(error.message, "danger");
      });
    },

    populateForm(profile){
      this.form.firstname = profile.firstname;
      this.form.lastname = profile.lastname;
      this.form.email = profile.email;
      this.form.avatar = profile.meta.avatar;
    },

    idToString(list){
      if(!list) return "";
      return list.map(item => item.id)
        .reduce((acc,cur) => `${cur}${ acc ? ',':''}${acc}`, "");
    },

    onRangeSelect(e) {
      this.recurring.event.start = e.start.slice(0, 10);
      this.recurring.event.end = e.end.slice(0, 10);
      this.createEventModal = true;
    },

    onEventClicked(e) {
      console.log(e);
      this.selectedEvent.startDate = DayPilot.Date(e.e.data.start);
      this.selectedEvent.endDate = DayPilot.Date(e.e.data.end);
      this.selectedEvent.start = e.e.data.start.slice(0, 10);
      this.selectedEvent.end = e.e.data.end.slice(0, 10);
      this.eventInfoModal = true;
    },

    onEventMoved(e) {
      console.log(e);
    },

    onEventResized(e) {
      console.log(e);
    },

    onEventDeleted(e) {
      console.log(e);
    },

    onSubmitEvents(e) {
      // populate the form data
      let form = {
        event_type: "unavailable",
        user_id: this.id,
        unavailables: []
      };

      let start = new window.DayPilot.Date(this.recurring.event.start);
      let end = new window.DayPilot.Date(this.recurring.event.end);

      // Create recuring events and push them to list
      if (this.recurring.repeat === "no repeat") {
        form.unavailables.push({
          start: start.toString(),
          end: end.toString()
        });
      } else if (this.recurring.repeat === "weekly") {
        for (let i = 1; i <= this.recurring.times; i++) {
          form.unavailables.push({
            start: start.toString(),
            end: end.toString()
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
            start: start.toString(),
            end: end.toString()
          });
          start = start.addDays(7);
          end = end.addDays(7);
        }
      }

      // Submit events to server
      this.create({ resource: "schedule", data: form })
        .then(() => {
          console.log("events created successfuly.");
          this.showMessage("Unavailable hours created successfuly.", "success");
        })
        .catch(error => {
          console.log(error);
          this.showMessage(error.message, "danger");
        });

      this.createEventModal = false;
    },

    onDeleteEvent(e) {
      // Show delete confirmation
      this.eventInfoModal = false;
      this.confirmDeleteModal = true;
    },

    onConfirmDelete() {
      // Delete has been confirmed
      // Delete the event
    },

    clearModal() {
      this.recurring.repeat = "no repeat";
      this.recurring.times = 1;
      this.recurring.event.start = "";
      this.recurring.event.end = "";
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

      // populate form data
      let form = new FormData();
      form.append("_method", "PUT");
      form.append("firstname", this.form.firstname);
      form.append("lastname", this.form.lastname);
      form.append("email", this.form.email);
      form.append("avatar", this.form.attachment);

      this.update({
        resource: "user",
        id: this.id,
        data: form,
        method: "POST",
        hasAttachments: true
      })
        .then(respond => {
          this.updateUserInfo(respond);
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