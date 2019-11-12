<template>
  <app-main
    :scrollbar="true"
    :loading="isLoading"
    padding="p-5"
  >
    <template slot="toolbar" />

    <template slot="drawer">
      <ul class="v-menu">
        <template v-if="!this.id">
          <li class="menu-title">
            Actions
          </li>
          <li>
            <button
              class="btn"
              @click="clearForm"
            >
              New
            </button>
          </li>
          <li>
            <button
              class="btn"
              @click="onSubmit"
            >
              Submit
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
        </template>
        <template v-if="this.id && canModify">
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
        </template>
        
        <li class="menu-title">
          Links
        </li>
        <li>
          <router-link
            class="btn btn-link"
            :to="{ name: 'activityList' }"
          >
            Activities
          </router-link>
        </li>
        <li v-if="activity">
          <router-link
            class="btn btn-link"
            :to="{ name : 'jobDetails', params : { id: activity.job_id } }"
          >
            Related Job
          </router-link>
        </li>
      </ul>
    </template>

    <template slot="content">
      <!-- Adress list validation summary -->
      <div
        v-if="$v.form.desc.$error"
        class="alert alert-danger"
        role="alert"
      >
        <b class="alert-heading">Fix these problems:</b>
        <ul>
          <li v-if="$v.form.desc.$error">
            The report is required.
          </li>
        </ul>
        <hr>
        <p class="mb-0">
          Your report have some errors. Fix them to be able to save the report.
        </p>
      </div>

      <!-- Activity Form -->
      <form>
        <fieldset :disabled="!canModify">        
          <!-- Schedule item -->
          <div class="form-group">          
            <div class="form-row">
              <label class="col-sm-2 col-form-lable">Schedule <i class="field-required">*</i></label>
              <div class="col-sm-10">
                <ts-select
                  v-model="scheduleItem"
                  :data="scheduleItems"
                  :class="[{'is-invalid' : $v.scheduleItem.$error } ,'form-control']"                
                  @change="onScheduleChange"
                  id="schedule_id"
                  placeholder="Select the schedule..."
                  search-placeholder="search..."
                />
                <div
                  class="invalid-feedback"
                  v-if="!$v.scheduleItem.required"
                >
                  Schedule is required.
                </div>
              </div>
            </div>
          </div>

          <!-- From -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-sm-2 col-form-lable">From <i class="field-required">*</i></label>
              <div class="col">
                <input
                  id="from"
                  v-model="$v.form.fromDate.$model"
                  type="date"
                  :class="[{'is-invalid' : $v.form.fromDate.$error || $v.from.$error } ,'form-control']"
                >
                <div
                  class="invalid-feedback"
                  v-if="!$v.form.fromDate.required"
                >
                  From date is required.
                </div>
                <div
                  class="invalid-feedback"
                  v-if="!$v.from.between"
                >
                  Must be between {{ $v.from.$params.between.min }} and {{ $v.from.$params.between.max }}
                </div>
              </div>
              <div class="col">
                <input
                  v-model="$v.form.fromTime.$model"
                  type="time"
                  :class="[{'is-invalid' : $v.form.fromTime.$error } ,'form-control']"
                >
                <div
                  class="invalid-feedback"
                  v-if="!$v.form.fromTime.required"
                >
                  From time is required.
                </div>
              </div>
            </div>
          </div>

          <!-- Till -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-sm-2 col-form-lable">Till <i class="field-required">*</i></label>
              <div class="col">
                <input
                  id="till"
                  v-model="$v.form.tillDate.$model"
                  type="date"
                  :class="[{'is-invalid' : $v.form.tillDate.$error || $v.till.$error } ,'form-control']"
                >
                <div
                  class="invalid-feedback"
                  v-if="!$v.form.tillDate.required"
                >
                  Till date is required.
                </div>
                <div
                  class="invalid-feedback"
                  v-if="!$v.till.between"
                >
                  Must be between {{ $v.till.$params.between.min }} and {{ $v.till.$params.between.max }}
                </div>
              </div>
              <div class="col">
                <input
                  v-model="$v.form.tillTime.$model"
                  type="time"
                  :class="[{'is-invalid' : $v.form.tillTime.$error } ,'form-control']"
                >
                <div
                  class="invalid-feedback"
                  v-if="!$v.form.tillTime.required"
                >
                  Till time is required.
                </div>
              </div>
            </div>
          </div>
        </fieldset>

        <!-- Report -->
        <div class="form-group">
          <div class="form-row">
            <div class="col-sm-12">
              <editor
                v-model="form.desc"
                :init="{height: 300}"
                api-key="he51k5qf4qe8668k9rgkie9c13j01h43fh61m72chuvv93ip"
                plugins="print fullscreen table image link lists advlist"
                toolbar="formatselect | fontselect | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat"
              />
              <div
                class="invalid-feedback"
                v-if="!$v.form.tillTime.required"
              >
                Report is required.
              </div>
            </div>
          </div>
        </div>
      </form>
    </template>
  </app-main>
</template>

<script>
import moment from "moment";
import Editor from "@tinymce/tinymce-vue";
import pageMixin from "../../../../mixins/page-mixin";
import { mapActions, mapGetters } from "vuex";
import { required, helpers } from "vuelidate/lib/validators";

const between = (min, max) =>
  helpers.withParams(
    { type: "between", min: min, max: max },
    value => value >= min && value <= max
  );

export default {
  name: "ActivityModify",

  mixins: [pageMixin],

  components: {
    editor: Editor
  },

  props: ["id"],

  data() {
    return {
      scheduleItem: {},
      form: {
        schedule_id: null,
        fromDate: "",
        fromTime: "",
        tillDate: "",
        tillTime: "",
        desc: ""
      }
    };
  },

  validations() {
    return {
      scheduleItem: { required },
      from: { between: between(this.scheduleStart, this.scheduleEnd) },
      till: { between: between(this.scheduleStart, this.scheduleEnd) },
      form: {
        fromDate: { required },
        fromTime: { required },
        tillDate: { required },
        tillTime: { required },
        desc: { required }
      }
    };
  },
  
  computed: {
    ...mapGetters({
      userId: "user/getId",
      getList: "resource/getList"
    }),

    activity: function() {
      if(!this.id) return null;
      return this.getList("activity")[0];
    },

    from: function() {
      return moment(this.form.fromDate + " " + this.form.fromTime).format(
        "YYYY-MM-DD HH:mm"
      );
    },

    till: function() {
      return moment(this.form.tillDate + " " + this.form.tillTime).format(
        "YYYY-MM-DD HH:mm"
      );
    },

    scheduleStart: function() {
      if (!this.scheduleItem) return "";
      return moment(this.scheduleItem.start).format("YYYY-MM-DD HH:mm");
    },

    scheduleEnd: function() {
      if (!this.scheduleItem) return "";
      return moment(this.scheduleItem.end).format("YYYY-MM-DD HH:mm");
    },

    scheduleItems: function() {
      // if watching the other user report, populate the
      // schedule selectbox with current activity
      if (this.activity) {
        if (this.activity.creator_id != this.userId) {
          let sch = this.activity.schedule;
          let job = this.activity.job;
          return [
            {
              start: sch.start,
              end: sch.end,
              job: job,
              key: sch.id,
              value: `${moment(sch.start).format("DD MMM")} - ${job.title}`
            }
          ];
        }
      }

      return this.getList("schedule").map(obj => {
        return {
          start: obj.start,
          end: obj.end,
          job: obj.job,
          key: obj.id,
          value: `${moment(obj.start).format("DD MMM")} - ${obj.job.title}`
        };
      });
    },

    canModify: function(){
      if(this.activity)
        return this.activity.creator_id == this.userId;
      else
        return true;
    }
  },

  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.clearForm();
      vm.loadAssets();
    });
  },

  beforeRouteLeave(to, from, next) {
    this.clear("activity");
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

    async loadAssets() {
      this.startLoading();

      let p1 = await this.list({
        resource: "schedule",
        query: this.$queryBuilder(
          null,
          null,
          ["job"],
          [
            { opt: "eq", col: "user_id", val: this.userId },
            { opt: "eq", col: "type", val: "schedule" },
            { opt: "eq", col: "event_type", val: "scheduled" },
            { opt: "ob", col: "start", val: "desc" }
          ]
        )
      });

      let p2 = this.id
        ? this.list({
            resource: "activity",
            query: { eq: `id,${this.id}`, with: "job,schedule" }
          }).then((data) => {
            this.populateForm(data[0])
          })
        : new Promise(resolve => resolve());      

      Promise.all([p1, p2]).finally(() => {
        this.stopLoading();
      });
    },

    /**
     * Fill the form with current model.
     */
    populateForm(activity) {
      this.scheduleItem = this.scheduleItems.find(
        item => item.key === activity.schedule_id
      );
      this.form.schedule_id = activity.schedule_id;
      this.form.fromDate = activity.from.slice(0, 10);
      this.form.fromTime = activity.from.slice(11, 16);
      this.form.tillDate = activity.till.slice(0, 10);
      this.form.tillTime = activity.till.slice(11, 16);
      this.form.desc = activity.desc;
    },

    /**
     * Populate date fields when schedule changes.
     */
    onScheduleChange(value){
      this.form.schedule_id = value.key;
      if (value !== null && value !== undefined) {
        this.form.fromDate = value.start.slice(0, 10);
        this.form.fromTime = value.start.slice(11, 16);
        this.form.tillDate = value.end.slice(0, 10);
        this.form.tillTime = value.end.slice(11, 16);
      }
    },

    /**
     * Handler for submit button click.
     */
    onSubmit(e) {
      e.preventDefault();
      e.target.disabled = true;

      // Validate
      this.$v.$touch();
      if (this.$v.$invalid) {
        e.target.disabled = false;
        return;
      }

      // Populate request body
      let form = {};
      form.schedule_id = this.form.schedule_id;
      form.job_id = this.scheduleItem.job.id;
      form.from = this.from;
      form.till = this.till;
      form.desc = this.form.desc;

      this.create({ resource: "activity", data: form })
        .then(() => {          
          this.showMessage(
            `Report <b>${this.scheduleItem.value}</b> created successfuly.`,
            "success"
          );
        })
        .catch(error => {
          this.showMessage(error.message, "danger");
          this.$formFeedback(error.response.data.errors);
        })
        .finally(()=>{
          e.target.disabled = false;
        });
    },

    /**
     * Handler for save button click.
     */
    onSave(e) {
      e.preventDefault();
      e.target.disabled = true;

      // Validate
      this.$v.$touch();
      if (this.$v.$invalid) {
        e.target.disabled = false;
        return;
      }

      // Populate request body
      let form = {};
      form.schedule_id = this.form.schedule_id;
      form.job_id = this.activity.job_id;
      form.from = this.from;
      form.till = this.till;
      form.desc = this.form.desc;

      this.update({ resource: "activity", id: this.id, data: form })
        .then(() => {
          this.showMessage(
            `Report <b>${this.scheduleItem.value}</b> updated successfuly.`,
            "success"
          );
        })
        .catch(error => {
          this.showMessage(error.message, "danger");
          this.$formFeedback(error.response.data.errors);
        })
        .finally(()=>{
          e.target.disabled = false;
        });
    },

    /**
     * Clear the form controls.
     */
    clearForm() {
      this.scheduleItem = null;
      this.form.fromDate = "";
      this.form.fromTime = "";
      this.form.tillDate = "";
      this.form.tillTime = "";
      this.form.desc = "";
      this.$v.form.$reset();
    },

    /**
     * Handler for cancel button click.
     */
    onCancel(e) {
      e.preventDefault();
      this.$router.go(-1);
    }
  }
};
</script>

<style scoped>
</style>
