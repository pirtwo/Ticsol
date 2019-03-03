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
        <li v-if="!this.id">
          <button
            class="btn btn-light"
            @click="clearForm"
          >
            New
          </button>
        </li>
        <li v-if="!this.id">
          <button
            class="btn btn-light"
            @click="onSubmit"
          >
            Submit
          </button>
        </li>
        <li v-if="this.id">
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
        <li>
          <router-link
            class="btn btn-link"
            :to="{ name: 'activityList' }"
          >
            Activities
          </router-link>
        </li>
        <li v-if="this.id">
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
      <form>
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Schedule</label>
            <div class="col-sm-10">
              <vb-select
                v-model="scheduleItem"
                :data="scheduleItems"                            
                id="schedule_id"
                name="schedule_id"
                placeholder="Select the schedule..."
                search-placeholder="search..."
              />
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">From</label>
            <div class="col">
              <input 
                id="from" 
                v-model="$v.form.fromDate.$model" 
                type="date" 
                :class="[{'is-invalid' : $v.form.fromDate.$error } ,'form-control']"
              >
              <div
                class="invalid-feedback"
                v-if="!$v.form.fromDate.required"
              >
                From date is required.
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

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Till</label>
            <div class="col">
              <input 
                id="till" 
                v-model="$v.form.tillDate.$model" 
                type="date" 
                :class="[{'is-invalid' : $v.form.tillDate.$error } ,'form-control']"
              >
              <div
                class="invalid-feedback"
                v-if="!$v.form.tillDate.required"
              >
                Till date is required.
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
            </div>
          </div>
        </div>
      </form>
    </template>
  </nav-view>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { required } from "vuelidate/lib/validators";
import Editor from "@tinymce/tinymce-vue";
import NavView from "../../../framework/NavView.vue";
import pageMixin from "../../../../mixins/page-mixin";

export default {
  name: "ActivityCreate",

  mixins: [pageMixin],

  components: {
    "nav-view": NavView,
    editor: Editor
  },

  props: ["id"],

  data() {
    return {
      activity: {},
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

  validations:{
    form:{
      schedule_id:{required},
      fromDate:{required},
      fromTime:{required},
      tillDate:{required},
      tillTime:{required},
    }
  },

  watch: {
    scheduleItem: function(value) {
      this.form.schedule_id = value.key;
      if (value !== null && value !== undefined) {        
        this.form.fromDate = value.start.slice(0, 10);
        this.form.fromTime = value.start.slice(11, 16);
        this.form.tillDate = value.end.slice(0, 10);
        this.form.tillTime = value.end.slice(11, 16);
      }
    }
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),    

    scheduleItems: function() {
      return this.getList("schedule").map(obj => {
        return {
          start: obj.start,
          end: obj.end,
          job: obj.job,
          key: obj.id,          
          value:
            new Date(obj.start.slice(0, 10)).toLocaleString("en-AU", {
              day: "2-digit",
              month: "short"
            }) +
            " - " +
            obj.job.title
        };
      });
    }
  },

  mounted() {
    this.loadingStart();
    if (this.id) {
      let activity = null;
      let p1 = this.fetchList({ resource: "schedule", query: { with: "job" } });
      let p2 = this.fetchItem({
        resource: "activity",
        id: this.id,
        query: { with: "job" }
      }).then(respond => {
        this.activity = respond;
        activity = respond;
      });
      Promise.all([p1, p2])
        .then(() => {
          this.scheduleItem = this.scheduleItems.find(item => item.key === activity.schedule_id);
          this.form.schedule_id = activity.schedule_id;
          this.form.fromDate = activity.from.slice(0, 10);
          this.form.fromTime = activity.from.slice(11, 16);
          this.form.tillDate = activity.till.slice(0, 10);
          this.form.tillTime = activity.till.slice(11, 16);
          this.form.desc = activity.desc;
          this.loadingStop();
        })
        .catch(error => {
          console.log(error);
          this.loadingStop();
        });
    } else {
      this.fetchList({ resource: "schedule", query: { with: "job" } })
        .then(() => {
          this.loadingStop();
        })
        .catch(error => {
          console.log(error);
          this.loadingStop();
        });
    }
  },

  methods: {
    ...mapActions({
      fetchItem: "resource/show",
      fetchList: "resource/list",
      create: "resource/create",
      update: "resource/update"
    }),

    onSubmit(e) {
      e.preventDefault();
      e.target.disabled = true;

      this.$v.$touch();
      if (this.$v.$invalid) {
        e.target.disabled = false;
        return;
      }

      let form = {};
      form.schedule_id = this.form.schedule_id;
      form.job_id = this.scheduleItem.job.id;
      form.from =
        this.form.fromDate +
        "T" +
        (this.form.fromTime == "" ? "00:00" : this.form.fromTime);
      form.till =
        this.form.tillDate +
        "T" +
        (this.form.tillTime == "" ? "00:00" : this.form.tillTime);
      form.desc = this.form.desc;
      this.create({ resource: "activity", data: form })
        .then(() => {
          e.target.disabled = false;
          this.showMessage(
            `Report <b>${this.scheduleItem.value}</b> created successfuly.`,
            "success"
          );
        })
        .catch(error => {
          e.target.disabled = false;
          this.showMessage(error.message, "danger");
          this.$formFeedback(error.response.data.errors);
        });
    },

    onSave(e) {
      e.preventDefault();
      e.target.disabled = true;

      this.$v.$touch();
      if (this.$v.$invalid) {
        e.target.disabled = false;
        return;
      }

      let form = {};
      form.schedule_id = this.form.schedule_id;
      form.job_id = this.activity.job_id;
      form.from =
        this.form.fromDate +
        "T" +
        (this.form.fromTime == "" ? "00:00" : this.form.fromTime);
      form.till =
        this.form.tillDate +
        "T" +
        (this.form.tillTime == "" ? "00:00" : this.form.tillTime);
      form.desc = this.form.desc;
      this.update({ resource: "activity", id: this.id, data: form })
        .then(() => {
          e.target.disabled = false;
          this.showMessage(
            `Report <b>${this.scheduleItem.value}</b> updated successfuly.`,
            "success"
          );
        })
        .catch(error => {
          e.target.disabled = false;
          this.showMessage(error.message, "danger");
          this.$formFeedback(error.response.data.errors);
        });
    },

    clearForm() {
      this.form.schedule_id = null;
      this.form.fromDate = "";
      this.form.fromTime = "";
      this.form.tillDate = "";
      this.form.tillTime = "";
      this.form.desc = "";
      this.$v.form.$reset();
    },

    onCancel(e) {
      e.preventDefault();      
      this.$router.push({ name: "activityList" });
    }
  }
};
</script>

<style scoped>
</style>
