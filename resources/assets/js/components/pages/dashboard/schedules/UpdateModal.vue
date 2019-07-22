
<template>
  <!-- Modal -->
  <div 
    class="modal fade" 
    id="updateModal" 
    tabindex="-1" 
    role="dialog" 
    aria-labelledby="title" 
    aria-hidden="true"
  >
    <div 
      class="modal-dialog modal-dialog-centered" 
      role="document"
    >
      <!-- Modal Content -->
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h5 
            class="modal-title" 
            id="title"
          >
            Update Event
          </h5>
          <button 
            @click="onClose" 
            type="button" 
            class="close" 
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div><!-- End Header -->

        <!-- Modal Body -->
        <div class="modal-body">
          <!-- Event Form -->
          <form>
            <!-- Resource -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-form-label col-sm-12">{{ view == 'user' ? 'User Name' : 'Job Name' }}</label>
                <div class="col">
                  <input 
                    v-model="form.resourceName" 
                    :name="view == 'user' ? 'user_id' : 'job_id'" 
                    class="form-control" 
                    type="text" 
                    readonly
                  >
                </div>
              </div>              
            </div>

            <!-- Event -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-form-label col-sm-12">{{ view == 'user' ? 'Job' : 'User' }} <i class="field-required">*</i></label>
                <div class="col">
                  <ts-select
                    v-model="form.event_id"
                    :default="form.event_id"
                    :data="eventList"
                    :multi="false"
                    :name="view == 'user' ? 'job_id' : 'user_id'"                    
                    :placeholder="view == 'user' ? 'please select job' : 'please select user'"
                    search-placeholder="search..."
                  >
                    <template 
                      slot="fixed-top" 
                      v-if="view == 'user'"
                    >
                      <li @click="onCreateJob">
                        <i>-- CREATE NEW JOB --</i>
                      </li>
                      <hr>
                    </template>  
                  </ts-select>
                  <div
                    class="invalid-feedback"
                    v-if="!$v.form.event_id.required"
                  >
                    Event is required.
                  </div>
                </div>
              </div>              
            </div>           

            <!-- Start -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-form-label col-sm-12">Starts <i class="field-required">*</i></label>
                <div class="col">
                  <input 
                    v-model="form.startDate" 
                    name="start" 
                    type="date" 
                    :class="[{'is-invalid' : $v.form.startDate.$error || $v.start.$error } ,'form-control']"
                  >
                  <div
                    class="invalid-feedback"
                    v-if="!$v.form.startDate.required"
                  >
                    Start date is required.
                  </div>
                  <div
                    class="invalid-feedback"
                    v-if="!$v.start.before"
                  >
                    Start date should be before {{ $v.start.$params.before.date }}
                  </div>
                </div>
                <div class="col">
                  <input 
                    v-model="form.startTime" 
                    type="time" 
                    :class="[{'is-invalid' : $v.form.startTime.$error } ,'form-control']"
                  >
                  <div
                    class="invalid-feedback"
                    v-if="!$v.form.startTime.required"
                  >
                    Start time is required.
                  </div>                  
                </div>             
              </div>
            </div>

            <!-- End -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-form-label col-sm-12">Ends <i class="field-required">*</i></label>
                <div class="col">
                  <input 
                    v-model="form.endDate" 
                    name="end" 
                    type="date" 
                    :class="[{'is-invalid' : $v.form.endDate.$error || $v.end.$error } ,'form-control']"
                  >
                  <div
                    class="invalid-feedback"
                    v-if="!$v.form.endDate.required"
                  >
                    End date is required.
                  </div>
                  <div
                    class="invalid-feedback"
                    v-if="!$v.end.after"
                  >
                    End date should be after {{ $v.end.$params.after.date }}
                  </div>
                </div>
                <div class="col">
                  <input 
                    v-model="form.endTime" 
                    type="time" 
                    :class="[{'is-invalid' : $v.form.endTime.$error } ,'form-control']"
                  >
                  <div
                    class="invalid-feedback"
                    v-if="!$v.form.endTime.required"
                  >
                    End time is required.
                  </div>
                </div>  
              </div>
            </div>            

            <!-- Status -->
            <div class="form-row">
              <div class="form-group col-sm-7">
                <div class="row no-gutters">
                  <label 
                    class="col-sm-12" 
                    for="status"
                  >Status <i class="field-required">*</i></label> 
                  <div class="col-sm-12">     
                    <div class="custom-control custom-radio custom-control-inline">
                      <input 
                        v-model="form.status" 
                        type="radio" 
                        id="updateTentative" 
                        name="status" 
                        value="tentative" 
                        class="custom-control-input" 
                        checked
                      >
                      <label 
                        class="custom-control-label" 
                        for="updateTentative"
                      >Tentative</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                      <input 
                        v-model="form.status" 
                        type="radio" 
                        id="updateConfirmed" 
                        name="status" 
                        value="confirmed" 
                        class="custom-control-input"
                      >
                      <label 
                        class="custom-control-label" 
                        for="updateConfirmed"
                      >Confirmed</label>
                    </div> 
                  </div>  
                </div>                  
              </div>

              <div class="form-group col-sm-5">
                <label for="offsite">Offsite</label> 
                <div class="custom-control custom-checkbox">
                  <input 
                    v-model="form.offsite" 
                    name="offsite" 
                    type="checkbox" 
                    class="custom-control-input" 
                    id="updateOffsite"
                  >
                  <label 
                    class="custom-control-label" 
                    for="updateOffsite"
                  >Work Offsite</label>
                </div> 
              </div>
            </div>
          </form>
        </div><!-- End Body -->

        <!-- Modal Footer -->
        <div class="modal-footer">          
          <button 
            @click="onClose" 
            type="button" 
            class="btn btn-light"
          >
            Cancel
          </button>      
          <button 
            @click="onDelete" 
            type="button" 
            class="btn btn-danger"
          >
            Delete
          </button>
          <button 
            @click="onSubmit" 
            type="button" 
            class="btn btn-primary"
          >
            Update
          </button>              
        </div><!-- End Footer -->
        <job-modal v-model="jobModal" />
      </div><!-- End Content  -->
    </div><!-- End Modal -->
  </div>
</template>

<script>

import moment from "moment";
import { required } from "vuelidate/lib/validators";
import { before, after } from "../../../../utils/custom-validations";
import { mapGetters, mapActions } from "vuex";
import CreateJobModal from "../schedules/CreateJobModal.vue";

export default {
  name: "AssignJobModal",

  components: {
    "job-modal": CreateJobModal
  },

  props: {
    show: {
      type: Boolean,
      default: false
    },
    event: {
      type: Object,
      default: null
    },
    view: {
      type: String,
      default: "user"
    },
    value: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      form: {
        resourceName: "",
        resource_id: null,
        event_id: {},
        status: "tentative",
        startDate: "",
        endDate: "",
        startTime: "",
        endTime: "",
        offsite: false
      },
      statusOptions: [
        { key: 1, value: "Tentative" },
        { key: 2, value: "Confirmed" }
      ],
      jobModal: false
    };
  },

  validations() {
    return {
      start: { before: before(this.end) },
      end: { after: after(this.start) },
      form:{
        event_id: { required },
        status: { required },        
        startDate: { required },
        startTime: { required },
        endDate: { required },
        endTime: { required }
      }      
    }
  },

  watch: {
    value: function(value) {
      if (value) {
        $("#updateModal").modal("show");
      } else {
        $("#updateModal").modal("hide");
      }
    },
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    eventList: function() {
      if (this.view == "user") {
        return this.getList("job").map(obj => {
          return { key: obj.id, value: obj.title };
        });
      } else {
        return this.getList("user").map(obj => {
          return { key: obj.id, value: obj.name };
        });
      }
    },

    start: function() {      
      return moment(`${this.form.startDate} ${this.form.startTime}`).format(
        "YYYY-MM-DD HH:mm"
      );
    },

    end: function() {
      return moment(`${this.form.endDate} ${this.form.endTime}`).format(
        "YYYY-MM-DD HH:mm"
      );
    }
  },

  mounted() {
    $("#updateModal").on("show.bs.modal", this.onShow);
    $("#updateModal").on("hide.bs.modal", this.onClose);
  },

  methods: {
    ...mapActions({
      fetch: "resource/list",
      update: "resource/update",
      delete: "resource/delete"
    }),

    onShow() {
      this.fillForm();
    },

    onClose() {
      this.clearForm();
      this.$emit("input", false);
    },

    onSubmit(e) {
      e.preventDefault();
      e.target.disabled = true;      

      // Validate
      this.$v.$touch();
      if (this.$v.$invalid) {
        e.target.disabled = false;
        return;
      }

      // Populate form data
      let form = {};
      form.user_id =
        this.view == "user" ? this.form.resource_id : this.form.event_id.key;
      form.job_id =
        this.view == "job" ? this.form.resource_id : this.form.event_id.key;
      form.status = this.form.status.toLowerCase();
      form.start = this.start;
      form.end = this.end;
      form.offsite = this.form.offsite;

      e.target.innerHTML = "Updating...";

      this.update({ resource: "schedule", id: this.event.id, data: form })
        .then(respond => {          
          console.log("Event updated successfuly.");
          this.$emit("input", false);
        })
        .catch(error => {          
          console.log(error.response);
          this.$formFeedback(error.response.data.errors);
        })
        .finally(()=>{
          e.target.innerHTML = "Update";
          e.target.disabled = false;
        });
    },

    onDelete(e) {
      e.target.innerHTML = "Deleting...";
      e.target.disabled = true;

      this.delete({ resource: "schedule", id: this.event.id })
        .then(respond => {         
          console.log("Event deleted successfuly.");
          this.$emit("input", false);
        })
        .catch(error => {          
          console.log(error.response);
        })
        .finally(()=>{
          e.target.innerHTML = "Delete";
          e.target.disabled = false;
        });
    },

    fillForm() {
      let eventId = this.view == "user" ? this.event.job_id : this.event.user_id;
      let resourceId = this.view == "user" ? this.event.user_id : this.event.job_id;

      this.form.resourceName = this.event.name;
      this.form.resource_id = resourceId;        
      this.form.event_id =
        this.eventList.find(item => item.key === eventId);        
      this.form.status = this.event.status;
      this.form.offsite = this.event.offsite;
      this.form.break_length = this.event.break_length;
      this.form.startDate = this.event.start
        .toDate()
        .toISOString()
        .split("T")[0];
      this.form.endDate = this.event.end
        .toDate()
        .toISOString()
        .split("T")[0];
      this.form.startTime = this.event.start
        .toDate()
        .toLocaleTimeString("en-US", {
          timeZone: "UTC",
          hour12: false,
          hour: "2-digit",
          minute: "2-digit"
        });
      this.form.endTime = this.event.end.toDate().toLocaleTimeString("en-US", {
        timeZone: "UTC",
        hour12: false,
        hour: "2-digit",
        minute: "2-digit"
      });
    },

    clearForm() {
      this.form.userName = "";
      this.form.user_id = null;
      this.form.job_id = null;
      this.form.startDate = "";
      this.form.startTime = "";
      this.form.endDate = "";
      this.form.endTime = "";
      this.form.offsite = false;

      this.$v.start.$reset();
      this.$v.end.$reset();
      this.$v.form.$reset();
    },

    onCreateJob() {
      this.jobModal = true;
    }
  }
};
</script>

<style scoped>
.modal-content {
  border: 0px;
}
</style>