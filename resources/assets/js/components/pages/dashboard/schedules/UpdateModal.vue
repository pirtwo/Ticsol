
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
            @click="onHide" 
            type="button" 
            class="close" 
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div><!-- End Header -->

        <!-- Modal Body -->
        <div class="modal-body">
          <form>
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

            <div class="form-group">
              <div class="form-row">
                <label class="col-form-label col-sm-12">{{ view == 'user' ? 'Job' : 'User' }}</label>
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
                </div>
              </div>              
            </div>           

            <div class="form-group">
              <div class="form-row">
                <label class="col-form-label col-sm-12">Starts</label>
                <div class="col">
                  <input 
                    v-model="form.start" 
                    name="start" 
                    type="date" 
                    class="form-control"
                  >
                </div>
                <div class="col">
                  <input 
                    v-model="form.startTime" 
                    type="time" 
                    class="form-control"
                  >                  
                </div>             
              </div>
            </div>

            <div class="form-group">
              <div class="form-row">
                <label class="col-form-label col-sm-12">Ends</label>
                <div class="col">
                  <input 
                    v-model="form.end" 
                    name="end" 
                    type="date" 
                    class="form-control"
                  >
                </div>
                <div class="col">
                  <input 
                    v-model="form.endTime" 
                    type="time" 
                    class="form-control"
                  >
                </div>  
              </div>
            </div>            

            <div class="form-row">
              <div class="form-group col-sm-7">
                <div class="row no-gutters">
                  <label 
                    class="col-sm-12" 
                    for="status"
                  >Status</label> 
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
            @click="onSubmit" 
            type="button" 
            class="btn btn-primary"
          >
            Update
          </button>
          <button 
            @click="onDelete" 
            type="button" 
            class="btn btn-danger"
          >
            Delete
          </button>
          <button 
            @click="onHide" 
            type="button" 
            class="btn btn-light"
          >
            Cancel
          </button>          
        </div><!-- End Footer -->
        <job-modal v-model="jobModal" />
      </div><!-- End Content  -->
    </div><!-- End Modal -->
  </div>
</template>

<script>
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
        startTime: "",
        endTime: "",
        start: "",
        end: "",
        offsite: false
      },
      statusOptions: [
        { key: 1, value: "Tentative" },
        { key: 2, value: "Confirmed" }
      ],
      jobModal: false
    };
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
    }
  },

  created() {
    //
  },

  mounted() {
    $("#updateModal").on("show.bs.modal", this.onShow);
    $("#updateModal").on("hide.bs.modal", this.onHide);
  },

  watch: {
    value: function(value) {
      if (value) {
        $("#updateModal").modal("show");
      } else {
        $("#updateModal").modal("hide");
      }
    }
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

    onHide() {
      this.$emit("input", false);
    },

    onSubmit(e) {
      let event = {};
      event.user_id =
        this.view == "user" ? this.form.resource_id : this.form.event_id.key;
      event.job_id =
        this.view == "job" ? this.form.resource_id : this.form.event_id.key;
      event.status = this.form.status.toLowerCase();
      event.start = this.form.start + "T" + this.form.startTime + ":00";
      event.end = this.form.end + "T" + this.form.endTime + ":00";
      event.offsite = this.form.offsite;
      e.target.innerHTML = "Updating...";
      e.target.disabled = true;

      this.update({ resource: "schedule", id: this.event.id, data: event })
        .then(respond => {
          e.target.innerHTML = "Update";
          e.target.disabled = false;
          console.log("Event updated successfuly.");
          this.$emit("input", false);
        })
        .catch(error => {
          e.target.innerHTML = "Update";
          e.target.disabled = false;
          console.log(error.response);
          this.$formFeedback(error.response.data.errors);
        });
    },

    onDelete(event) {
      event.target.innerHTML = "Deleting...";
      event.target.disabled = true;
      this.delete({ resource: "schedule", id: this.event.id })
        .then(respond => {
          event.target.innerHTML = "Delete";
          event.target.disabled = false;
          console.log("Event deleted successfuly.");
          this.$emit("input", false);
        })
        .catch(error => {
          event.target.innerHTML = "Delete";
          event.target.disabled = false;
          console.log(error.response);
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
      this.form.start = this.event.start
        .toDate()
        .toISOString()
        .split("T")[0];
      this.form.end = this.event.end
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