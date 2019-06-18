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
            :to="{ name: 'jobCreate' }"
          >
            New
          </router-link>
        </li>
        <li>
          <router-link
            class="btn btn-link"
            :to="{ name: 'jobList' }"
          >
            Jobs
          </router-link>
        </li>
        <li v-if="this.id">
          <router-link
            class="btn btn-link"
            :to="{ name: 'jobList', params : { col: 'job_id', opt: 'eq', val: this.id } }"
          >
            Schedule
            Items
          </router-link>
        </li>
        <li v-if="this.id">
          <router-link
            class="btn btn-link"
            :to="{ name: 'commentList', params : { entity: 'job', id: this.id } }"
          >
            Comments
          </router-link>
        </li>
        <li v-if="this.id">
          <router-link
            :class="[{'disabled' : this.relatedActivities === null}, 'btn btn-link' ]"
            role="button"
            :aria-disabled="this.relatedActivities === null"
            :to="{ name: 'activityList', params : { col: 'id', opt: 'in', val: this.relatedActivities } }"
          >
            Activity Reports
          </router-link>
        </li>
        <li v-if="this.id">
          <router-link
            :class="[{'disabled' : this.relatedJobs === null}, 'btn btn-link' ]"
            role="button"
            :aria-disabled="this.relatedJobs === null"
            :to="{ name: 'jobList', params : { col: 'id', opt: 'in', val: this.relatedJobs } }"
          >
            Related Jobs
          </router-link>
        </li>
        <li v-if="this.id">
          <router-link
            :class="[{'disabled' : this.relatedRequests === null}, 'btn btn-link' ]"
            role="button"
            :aria-disabled="this.relatedRequests === null"
            :to="{ name: 'inbox', params : { col: 'id', opt: 'in', val: this.relatedRequests } }"
          >
            Related Requests
          </router-link>
        </li>
        <li v-if="this.id">
          <router-link
            :class="[{'disabled' : this.relatedContacts === null}, 'btn btn-link' ]"
            role="button"
            :aria-disabled="this.relatedContacts === null"
            :to="{ name: 'contactList', params : { col: 'id', opt: 'in', val: this.relatedContacts } }"
          >
            Related Contacts
          </router-link>
        </li>
      </ul>
    </template>

    <template slot="content">
      <form class="needs-validation">
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Title</label>
            <div class="col-sm-10">
              <input
                v-model="$v.form.title.$model"
                id="title"
                type="text"
                :class="[{'is-invalid' : $v.form.title.$error } ,'form-control']"
                placeholder="job title"
              >
              <div
                class="invalid-feedback"
                v-if="!$v.form.title.required"
              >
                Please enter a title.
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Code</label>
            <div class="col-sm-10">
              <input
                v-model="$v.form.code.$model"
                id="code"
                type="text"
                :class="[{'is-invalid' : $v.form.code.$error } ,'form-control']"
                placeholder="display code"
              >
              <div
                class="invalid-feedback"
                v-if="!$v.form.code.required"
              >
                Please enter a code.
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Parent</label>
            <div class="col-sm-10">
              <ts-select
                v-model="form.parent"
                :data="jobs"
                id="parent_id"
                name="jobParent"
                placeholder="select parent..."
                search-placeholder="search..."
              />
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Profile</label>
            <div class="col-sm-10">
              <ts-select
                v-model="form.profile"
                :data="profiles"
                id="form_id"
                name="jobProfile"
                placeholder="select profile..."
                search-placeholder="search..."
              />
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Contacts</label>
            <div class="col-sm-10">
              <ts-select
                v-model="form.contacts"
                :data="contacts"
                :multi="true"
                id="contacts"
                name="contacts"
                placeholder="select contacts..."
                search-placeholder="search..."
              />
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Status</label>

            <div class="custom-control custom-radio custom-control-inline">
              <input
                v-model="form.isactive"
                type="radio"
                id="jobEnable"
                name="status"
                value="1"
                class="custom-control-input"
                checked
              >
              <label
                class="custom-control-label"
                for="jobEnable"
              >Enable</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
              <input
                v-model="form.isactive"
                type="radio"
                id="jobDisable"
                name="status"
                value="0"
                class="custom-control-input"
              >
              <label
                class="custom-control-label"
                for="jobDisable"
              >Disable</label>
            </div>
          </div>
        </div>

        <form-gen
          :schema="schema"
          v-model="form.meta"
        />
      </form>      
    </template>
  </app-main>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import { required } from "vuelidate/lib/validators";
import PageMixin from "../../../../mixins/page-mixin.js";
import FormGen from "../../../base/formGenerator/BaseFormGenerator";

export default {
  name: "JobModify",

  mixins: [PageMixin],

  components: {
    "form-gen": FormGen
  },

  props: ["id"],

  data() {
    return {
      currentJob: null,
      schema: "",
      formData: "",
      form: {
        title: "",
        code: "",
        isactive: 1,
        parent: {},
        profile: {},
        contacts: [],
        meta: null
      }
    };
  },

  validations: {
    form: {
      title: { required },
      code: { required }
    }
  },

  watch: {
    "form.profile": function(value) {
      if (value.key) {
        this.schema = this.getList(
          "form",
          item => item.id == value.key
        )[0].schema;
      }
    }
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    jobs: function() {
      return this.getList("job").map(item => {
        return { key: item.id, value: item.title };
      });
    },

    profiles: function() {
      return this.getList("form").map(item => {
        return { key: item.id, value: item.name };
      });
    },

    contacts: function() {
      return this.getList("contact").map(item => {
        return { key: item.id, value: `${item.firstname} ${item.lastname}` };
      });
    },

    relatedContacts: function() {
      if (this.currentJob != null && this.currentJob.contacts.length > 0)
        return this.currentJob.contacts.reduce(
          (a, c, i) => a + (i == 0 ? "" : ",") + c.id,
          ""
        );
      else return null;
    },

    relatedJobs: function() {
      if (this.currentJob != null && this.currentJob.childs.length > 0)
        return this.currentJob.childs.reduce(
          (a, c, i) => a + (i == 0 ? "" : ",") + c.id,
          ""
        );
      else return null;
    },

    relatedActivities: function() {
      if (this.currentJob != null && this.currentJob.activities.length > 0)
        return this.currentJob.activities.reduce(
          (a, c, i) => a + (i == 0 ? "" : ",") + c.id,
          ""
        );
      else return null;
    },

    relatedRequests: function() {
      if (this.currentJob != null && this.currentJob.requests.length > 0) {
        return this.currentJob.requests.reduce(
          (a, c, i) => a + (i == 0 ? "" : ",") + c.id,
          ""
        );
      } else return null;
    }
  },

  beforeRouteEnter(to, from, next) {    
    next(vm => {
      vm.clearForm();
    });
  },

  mounted() {
    this.startLoading();
    if (this.id) {
      let p1 = this.fetch({
        resource: "job",
        query: { with: "childs,contacts,requests,activities" }
      });
      let p2 = this.fetch({ resource: "form" });
      let p3 = this.fetch({ resource: "contact" });
      Promise.all([p1, p2, p3])
        .then(() => {
          this.currentJob = this.getList("job", item => item.id == this.id)[0];
          this.form.title = this.currentJob.title;
          this.form.code = this.currentJob.code;

          this.form.parent =
            this.currentJob.parent_id !== null
              ? this.jobs.find(item => item.key == this.currentJob.parent_id)
              : {};

          this.form.profile =
            this.currentJob.form_id !== null
              ? this.profiles.find(item => item.key == this.currentJob.form_id)
              : {};

          this.form.contacts = this.contacts.filter(item =>
            this.currentJob.contacts.find(elm => elm.id === item.key)
          );

          this.form.meta = this.currentJob.meta;

          this.schema =
            this.currentJob.form_id !== null
              ? this.getList(
                  "form",
                  item => item.id == this.currentJob.form_id
                )[0].schema
              : null;

          this.stopLoading();
        })
        .catch(error => {
          console.log(error);
        });
    } else {
      let p1 = this.fetch({ resource: "job" });
      let p2 = this.fetch({ resource: "form" });
      let p3 = this.fetch({ resource: "contact" });
      Promise.all([p1, p2, p3])
        .then(() => {
          this.stopLoading();
        })
        .catch(error => {
          this.stopLoading();
          this.showMessage(error.message, "danger");
        });
    }
  },

  methods: {
    ...mapActions({
      fetch: "resource/list",
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
      form.title = this.form.title;
      form.code = this.form.code;
      form.isactive = this.form.isactive;
      form.parent_id = this.form.parent.key;
      form.form_id = this.form.profile.key;
      form.contacts = this.form.contacts.map(item => item.key);
      form.meta = this.form.meta;

      this.create({ resource: "job", data: form })
        .then(respond => {
          e.target.disabled = false;
          this.showMessage(
            `Job <b>${form.title}</b> created successfuly.`,
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
      form.title = this.form.title;
      form.code = this.form.code;
      form.isactive = this.form.isactive;
      form.parent_id = this.form.parent.key;
      form.form_id = this.form.profile.key;
      form.contacts = this.form.contacts.map(item => item.key);
      form.meta = this.form.meta;

      this.update({ resource: "job", id: this.id, data: form })
        .then(() => {
          this.showMessage(
            `job <b>${form.title}</b> updated successfuly.`,
            "success"
          );
          e.target.disabled = false;
        })
        .catch(error => {
          e.target.disabled = false;
          this.showMessage(error.message, "danger");
          this.$formFeedback(error.response.data.errors);
        });
    },

    clearForm() {
      this.schema = "";
      this.form.title = "";
      this.form.code = "";
      this.form.isactive = 1;
      this.form.parent = {};
      this.form.profile = {};
      this.form.contacts = [];
      this.form.meta = null;
      this.$v.form.$reset();
    },

    onCancel(e) {
      this.$router.push({ name: "jobList" });
    }
  }
};
</script>

<style scoped>
</style>