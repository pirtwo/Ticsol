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
        <div class="form-row">
          <div class="form-group col-sm-4">
            <img
              src="/img/avatar/pic_4.jpg"
              class="rounded mx-auto d-block"
              alt="..."
              width="200"
              height="200"
            >
          </div>
          <div class="form-group col-sm-8">
            <div class="form-group">
              <div class="form-row">
                <!-- <label class="col-sm-12 col-form-lable">First Name</label> -->
                <div class="col-sm-12">
                  <input
                    v-model="$v.form.firstname.$model"
                    id="firstname"
                    type="text"
                    :class="[{'is-invalid' : $v.form.firstname.$error } ,'form-control']"
                    placeholder="Enter your first name..."
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
            <div class="form-group">
              <div class="form-row">
                <!-- <label class="col-sm-12 col-form-lable">Last Name</label> -->
                <div class="col-sm-12">
                  <input
                    v-model="$v.form.lastname.$model"
                    id="lastname"
                    type="text"
                    :class="[{'is-invalid' : $v.form.lastname.$error } ,'form-control']"
                    placeholder="Enter your last name..."
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
            <div class="form-group">
              <div class="form-row">
                <!-- <label class="col-sm-12 col-form-lable">Email</label> -->
                <div class="col-sm-12">
                  <input
                    v-model="$v.form.email.$model"
                    id="email"
                    type="text"
                    :class="[{'is-invalid' : $v.form.email.$error } ,'form-control']"
                    placeholder="Enter your email..."
                  >
                  <div
                    class="invalid-feedback"
                    v-if="!$v.form.email.required"
                  >
                    Email is required.
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="form-row">
                <!-- <label class="col-sm-12 col-form-lable">Profile Picture</label> -->
                <div class="col-sm-12">
                  <div class="custom-file">
                    <input
                      type="file"
                      class="custom-file-input"
                      id="customFile"
                    >
                    <label
                      class="custom-file-label"
                      for="customFile"
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

      <ts-datepicker
        v-model="weekStart"
        range="Week"
      />
      <calendar 
        :start-date="weekStart" 
        :events="events" 
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
                >For {{ recurring.times }} {{ recurring.times > 1 ? "times" : "time" }}</label>
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
            SUBMIT
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="()=>{ createEventModal = false }"
          >
            CLOSE
          </button>
        </template>
      </ts-modal>

      <!-- Event info modal -->
      <ts-modal
        :show.sync="eventInfoModal"
        title="Event Info"
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
            class="btn btn-primary"
            @click="()=>{ eventInfoModal = false }"
          >
            CLOSE
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="onDelete"
          >
            DELETE
          </button>
        </template>
      </ts-modal>

      <!-- Confirm Delete Modal -->
      <ts-modal
        :show.sync="confirmDeleteModal"
        title="Confirm Delete"
      >
        <p>
          Are you sure you want to delete event 
          <b>Unavailable 
            {{ selectedEvent.startDate.toString("MMM dd") }} till 
            {{ selectedEvent.endDate.toString("MMM dd") }}
          </b>?
        </p>
        <template slot="footer">
          <button
            type="button"
            class="btn btn-primary"
          >
            YES
          </button>
          <button
            type="button"
            class="btn btn-primary"
          >
            NO
          </button>
        </template>
      </ts-modal>
    </template>
  </nav-view>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import { required } from "vuelidate/lib/validators";
import NavView from "../../../framework/NavView.vue";
import pageMixin from "../../../../mixins/page-mixin";
import DPCalendarVue from "../../../Base/DPCalendar.vue";
import BaseDatePickerVue from "../../../framework/BaseDatePicker.vue";

export default {
  name: "UserList",

  mixins: [pageMixin],

  components: {
    "nav-view": NavView,
    calendar: DPCalendarVue,
    "ts-datepicker": BaseDatePickerVue
  },

  data() {
    return {
      weekStart: DayPilot.Date.today().firstDayOfWeek(),
      events: [],
      selectedEvent: {
        startDate: DayPilot.Date.today(),
        endDate: DayPilot.Date.today(),
        start: "",
        end:""
      },
      createEventModal: false,
      confirmDeleteModal: false,
      eventInfoModal: false,
      form: {
        firstname: "",
        lastname: "",
        email: ""
      },
      recurring: {
        repeat: "no repeat",
        times: 1,
        event: {
          start: "",
          end: ""
        }
      }
    };
  },

  validations: {
    form: {
      firstname: { required },
      lastname: { required },
      email: { required }
    }
  },

  watch: {},

  computed: {},

  mounted() {},

  methods: {
    ...mapActions({ fetchList: "resource/list" }),

    onRangeSelect(e) {
      this.recurring.event.start = e.start.slice(0, 10);
      this.recurring.event.end = e.end.slice(0, 10);
      this.createEventModal = true;      
    },

    onEventClicked(e){  
      this.selectedEvent.startDate = DayPilot.Date(e.e.data.start);
      this.selectedEvent.endDate = DayPilot.Date(e.e.data.end);      
      this.selectedEvent.start = e.e.data.start.slice(0, 10);
      this.selectedEvent.end = e.e.data.end.slice(0, 10);
      this.eventInfoModal = true;
    },

    onEventMoved(e){

    },

    onEventResized(e){

    },    

    onEventDeleted(e){

    },

    onSubmitEvents(e) {
      let start = new DayPilot.Date(this.recurring.event.start);
      let end = new DayPilot.Date(this.recurring.event.end);      

      if (this.recurring.repeat === "no repeat") {
        this.events.push({
          start: start.toString(),
          end: end.toString(),
          id: DayPilot.guid(),
          text: "Unavailable"
        });
      } else if (this.recurring.repeat === "weekly") {
        for (let i = 1; i <= this.recurring.times; i++) {
          this.events.push({
            start: start.toString(),
            end: end.toString(),
            id: DayPilot.guid(),
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
          this.events.push({
            start: start.toString(),
            end: end.toString(),
            id: DayPilot.guid(),
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

    onSave(e) {},

    onDelete(e){
      this.eventInfoModal = false;
      this.confirmDeleteModal = true;
    },

    onCancel(e) {}
  }
};
</script>

<style scoped>
</style>