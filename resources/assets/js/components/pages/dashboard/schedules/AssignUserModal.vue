
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
                  <input v-model="form.userName" name="user_id" class="form-control" type="text" readonly>
                </div>
              </div>              
            </div>

            <div class="form-group">
              <div class="form-row">
                <label class="col-form-label col-sm-12">Select Job</label>
                <div class="col">
                  <selelct-box
                    v-model="form.job_id"
                    :data="options"
                    :multi-select="false"
                    name="job_id"                    
                    placeholder="job..."
                    search-placeholder="search..."
                  ></selelct-box>
                </div>
              </div>              
            </div>

            <div class="form-group">
              <div class="form-row">
                <label class="col-form-label col-sm-12">Status</label>
                <div class="col">
                  <selelct-box
                    v-model="form.status"
                    :data="statusOptions"
                    :multi-select="false"                    
                    :enable-searchbox="false"
                    output-type="value"
                    name="status"                    
                    placeholder="status..."                    
                  ></selelct-box>
                </div>
              </div>              
            </div>

            <div class="form-group">
               <div class="form-row">
                <label class="col-form-label col-sm-12">Starts</label>
                <div class="col">
                  <input v-model="form.start" name="start" type="date" class="form-control" />
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
                  <input v-model="form.end" name="end" type="date" class="form-control" />
                </div>
                <div class="col">
                  <input v-model="form.endTime" type="time" class="form-control" />
                </div>  
              </div>
            </div>            

            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input v-model="form.offsite" name="offsite" type="checkbox" class="custom-control-input" id="offsite">
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
import Selectbox from "../../../framework/BaseSelectBox.vue";

export default {
  name: "AssignUserPopup",

  components: {
    "selelct-box": Selectbox
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
        user_id: null,
        job_id: null,
        status: null,
        startTime: "",
        endTime: "",
        start: "",
        end: "",
        offsite: false
      },
      statusOptions: [
        { key: 1, value: "Tentative" },
        { key: 2, value: "Confirmed" },
        { key: 3, value: "Submitted" }
      ]
    };
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    options: function() {
      if (!this.getList("job").map) return [];
      return this.getList("job").map(obj => {
        return { key: obj.id, value: obj.title };
      });
    }
  },

  created() {
    this.fetch({ resource: "job" });
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
      this.form.user_id = value.resourceId;
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
      fetch: "resource/list",
      create: "resource/create"
    }),

    onClose() {
      this.clearForm();
      this.$emit("input", false);
    },

    onSubmit(e) {      
      let event = {};
      event.user_id = this.form.user_id;
      event.job_id = this.form.job_id;
      event.status = this.form.status.toLowerCase();
      event.type = "schedule";
      event.start = this.form.start + "T" + this.form.startTime + ":00";
      event.end = this.form.end + "T" + this.form.endTime + ":00";
      event.offsite = this.form.offsite;
      event.break_length = 0;

      e.target.innerHTML = "Creating...";
      e.target.disabled = true;

      this.create({ resource: "schedule", data: event })
        .then(respond => {
          e.target.innerHTML = "Assign";
          e.target.disabled = false;
          this.clearForm();
          this.$emit("input", false);
        })
        .catch(error => {          
          e.target.innerHTML = "Assign";
          e.target.disabled = false;
          console.log(error.response);
          this.$formFeedback(error.response.data.errors);
        });
    },

    clearForm() {
      this.form.userName = "";
      this.form.user_id = null;
      this.form.job_id = null;
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