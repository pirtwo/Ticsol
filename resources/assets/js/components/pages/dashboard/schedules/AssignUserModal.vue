
<template>

  <div class="modal fade" id="assignUserModal" tabindex="-1" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="title">Assign Job</h5>
          <button @click="onClose" type="button" class="close" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
          <form>
            <div class="form-group">
              <div class="form-row">
                <label class="col-form-label col-sm-12">User Name</label>
                <div class="col">
                  <input v-model="form.userName" class="form-control" type="text" readonly>
                </div>
              </div>              
            </div>

            <div class="form-group">
              <div class="form-row">
                <label class="col-form-label col-sm-12">Select Job</label>
                <div class="col">
                  <auto-complete
                    v-model="form.resourceId"
                    :data="options"
                    name="job"
                    place-holder="select job ..."
                  ></auto-complete>
                </div>
              </div>              
            </div>

            <div class="form-group">
               <div class="form-row">
                <label class="col-form-label col-sm-12">Starts</label>
                <div class="col">
                  <input v-model="form.start" type="date" class="form-control" />
                </div>
                <div class="col">
                  <input v-model="form.startTime" type="time" class="form-control" />                  
                </div>             
              </div>
            </div>

            <div class="form-group">
              <div class="form-row">
                <label class="col-form-label col-sm-12">Ends</label>
                <div class="col">
                  <input v-model="form.end" type="date" class="form-control" />
                </div>
                <div class="col">
                  <input v-model="form.endTime" type="time" class="form-control" />
                </div>  
              </div>
            </div>            

            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input v-model="form.offsite" type="checkbox" class="custom-control-input" id="offsite">
                <label class="custom-control-label" for="offsite">Work Offsite</label>
              </div>
            </div>

          </form>

        </div>
        <div class="modal-footer">
          <button @click="onSubmit" type="button" class="btn btn-success">Assign</button>
          <button @click="onClose" type="button" class="btn btn-secondary">Close</button>          
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import { mapGetters, mapActions } from "vuex";
import AutoComplete from "../../../framework/BaseAutoComplete.vue";

export default {
  name: "AssignUserPopup",

  components: {
    "auto-complete": AutoComplete
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
    value: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      form: {
        userName: "",
        userId: null,
        resourceId: null,
        startTime: "",
        endTime: "",
        start: "",
        end: "",
        offsite: false
      }
    };
  },

  computed: {
    ...mapGetters({
      jobList: "job/getJobList"
    }),

    options: function() {
      return this.jobList.map(obj => {
        let newObj = {};
        newObj.key = obj.id;
        newObj.value = obj.title;
        return newObj;
      });
    }
  },

  created() {
    this.fetchJobList({ query: "" });
  },

  mounted() {
    $("#assignUserModal").on("hide.bs.modal", this.onClose);
  },

  watch: {
    value: function(value) {
      if (value) {
        $("#assignUserModal").modal("show");
      } else {
        $("#assignUserModal").modal("hide");
      }
    },

    event: function(value) {
      this.form.userName = value.userName;
      this.form.userId = value.resourceId;
      this.form.start = value.start
        .toDate()
        .toISOString()
        .split("T")[0];
      this.form.end = value.end
        .toDate()
        .toISOString()
        .split("T")[0];
      this.form.startTime = value.start.toDate().toLocaleTimeString("en-US", {
        timeZone: "UTC",
        hour12: false,
        hour: "2-digit",
        minute: "2-digit"
      });
      this.form.endTime = value.end.toDate().toLocaleTimeString("en-US", {
        timeZone: "UTC",
        hour12: false,
        hour: "2-digit",
        minute: "2-digit"
      });
    }
  },

  methods: {
    ...mapActions({
      fetchJobList: "job/list",
      createEvent: "schedule/create"
    }),

    onClose() {
      this.clearForm();
      this.$emit("input", false);
    },

    onSubmit(e) {
      let event = {};
      event.userId = this.form.userId;
      event.resourceId = this.form.resourceId;
      event.start = this.form.start + "T" + this.form.startTime + ":00";
      event.end = this.form.end + "T" + this.form.endTime + ":00";
      event.offsite = this.form.offsite;
      
      e.target.innerHTML = "Creating...";
      e.target.disabled = true;

      this.createEvent({ data: event })
        .then(respond => {
          e.target.innerHTML = "Assign";
          e.target.disabled = false;

          this.clearForm();
          this.$emit("input", false);
        })
        .catch(error => {          
          e.target.innerHTML = "Assign";
          e.target.disabled = false;
          this.clearForm();
          this.$emit("input", false);
        });
    },

    clearForm() {
      this.form.userName = "";
      this.form.userId = null;
      this.form.resourceId = null;
      this.form.startTime = "";
      this.form.endTime = "";
      this.form.start = "";
      this.form.end = "";
      this.form.offsite = false;
    }
  }
};
</script>

<style scoped>
</style>