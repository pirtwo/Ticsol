<template>
  <nav-view 
    :scrollbar="true" 
    :loading="isLoading" 
    padding="p-5">
    <template slot="toolbar"/>

    <template slot="drawer">
      <ul class="v-menu">
        <li class="menu-title">Actions</li>
        <li>
          <router-link 
            class="btn btn-light" 
            tag="button" 
            :to="{ name:'jobCreate' }">New</router-link>
        </li>
        <li>
          <button 
            class="btn btn-light" 
            @click="onSave">Save</button>
        </li>
        <li>
          <button 
            class="btn btn-light" 
            @click="onCancel">Cancel</button>
        </li>
        <li class="menu-title">Links</li>
        <li>
          <router-link 
            class="btn btn-link" 
            :to="{ name: 'jobList' }">Jobs</router-link>
        </li>
        <li>
          <router-link
            class="btn btn-link"
            :to="{ name: 'jobList', params : { col: 'job_id', opt: 'eq', val: this.id } }"
          >
            Schedule
            Items
          </router-link>
        </li>
        <li>
          <router-link
            class="btn btn-link"
            :to="{ name: 'commentList', params : { entity: 'job', id: this.id } }"
          >Comments</router-link>
        </li>
        <li>
          <router-link
            :class="[{'disabled' : this.relatedActivities === null}, 'btn btn-link' ]"
            role="button"
            :aria-disabled="this.relatedActivities === null"
            :to="{ name: 'activityList', params : { col: 'id', opt: 'in', val: this.relatedActivities } }"
          >Activity Reports</router-link>
        </li>
        <li>
          <router-link
            :class="[{'disabled' : this.relatedJobs === null}, 'btn btn-link' ]"
            role="button"
            :aria-disabled="this.relatedJobs === null"
            :to="{ name: 'jobList', params : { col: 'id', opt: 'in', val: this.relatedJobs } }"
          >Related Jobs</router-link>
        </li>
        <li>
          <router-link
            :class="[{'disabled' : this.relatedRequests === null}, 'btn btn-link' ]"
            role="button"
            :aria-disabled="this.relatedRequests === null"
            :to="{ name: 'inbox', params : { col: 'job_id', opt: 'in', val: this.relatedRequests } }"
          >Related Requests</router-link>
        </li>
        <li>
          <router-link
            :class="[{'disabled' : this.relatedContacts === null}, 'btn btn-link' ]"
            role="button"
            :aria-disabled="this.relatedContacts === null"
            :to="{ name: 'contactList', params : { col: 'id', opt: 'in', val: this.relatedContacts } }"
          >Related Contacts</router-link>
        </li>
      </ul>
    </template>

    <template slot="content">
      <form 
        class="needs-validation" 
        novalidate>
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Title</label>
            <div class="col-sm-10">
              <input
                v-model="form.title"
                id="title"
                type="text"
                class="form-control"
                placeholder="job title"
              >
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Code</label>
            <div class="col-sm-10">
              <input
                v-model="form.code"
                id="code"
                type="text"
                class="form-control"
                placeholder="display code"
              >
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Parent</label>
            <div class="col-sm-10">
              <vb-select
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
              <vb-select
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
              <vb-select
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
            <label class="col-sm-12 col-form-lable">Status</label>

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
                for="jobEnable">Enable</label>
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
                for="jobDisable">Disable</label>
            </div>
          </div>
        </div>

        <form-gen 
          :schema="schema" 
          v-model="form.meta"/>
      </form>
    </template>
  </nav-view>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import PageMixin from "../../../../mixins/page-mixin.js";
import NavView from "../../../framework/NavView.vue";
import FormGen from "../../../framework/BaseFormGenerator/BaseFormGenerator.vue";

export default {
  name: "JobDetails",

  mixins: [PageMixin],

  components: {
    "nav-view": NavView,
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

  watch: {
    "form.profile": function(value) {
      if (value.key) {
        this.schema = this.getList("form", item => item.id == value.key)[0].schema;
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

  created() {    
  },

  mounted() {
    this.loadingStart();
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

        this.loadingStop();
      })
      .catch(error => {
        console.log(error);
      });
  },

  methods: {
    ...mapActions({
      update: "resource/update",
      fetch: "resource/list",
    }),

    onSave(e) {
      e.preventDefault();
      e.target.disabled = true;

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

    onCancel(e) {
      this.$router.push({ name: "jobList" });
    }
  }
};
</script>

<style scoped>
</style>