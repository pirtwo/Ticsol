<template>
  <nav-view :scrollbar="true" :loading="loading" padding="p-5">

    <template slot="toolbar">

    </template>

    <template slot="drawer">
      <ul class="v-menu">
        <li class="menu-title">Actions</li>
        <li>
          <router-link :to="{ name:'jobCreate' }" class="btn btn-light">
            New
          </router-link>
        </li>
        <li>
          <button class="btn btn-light" @click="onSave">
            Save
          </button>
        </li>
        <li>
          <button class="btn btn-light" @click="onCancel">
            Cancel
          </button>
        </li>
        <li class="menu-title">Links</li>
        <li>
          <router-link :to="{ name: 'jobList' }">Jobs</router-link>
        </li>
        <li>
          <router-link :to="{ name: 'jobList', params : { col: 'job_id', opt: 'eq', val: this.id } }">Schedule
            Items</router-link>
        </li>
        <li>
          <router-link :class="[{'disabled' : this.relatedActivities === null}, 'btn btn-link' ]" role="button"
            :aria-disabled="this.relatedActivities === null" 
            :to="{ name: 'activityList', params : { col: 'id', opt: 'in', val: this.relatedActivities } }">
            Activity Reports</router-link>
        </li>
        <li>
          <router-link :class="[{'disabled' : this.relatedJobs === null}, 'btn btn-link' ]" role="button"
            :aria-disabled="this.relatedJobs === null" 
            :to="{ name: 'jobList', params : { col: 'id', opt: 'in', val: this.relatedJobs } }">
            Related Jobs</router-link>
        </li>
        <li>
          <router-link :class="[{'disabled' : this.relatedRequests === null}, 'btn btn-link' ]" role="button"
            :aria-disabled="this.relatedRequests === null" 
            :to="{ name: 'requestList', params : { col: 'job_id', opt: 'in', val: this.relatedRequests } }">
            Related Requests</router-link>
        </li>
        <li>
          <router-link :class="[{'disabled' : this.relatedContacts === null}, 'btn btn-link' ]" role="button"
            :aria-disabled="this.relatedContacts === null" 
            :to="{ name: 'contactList', params : { col: 'id', opt: 'in', val: this.relatedContacts } }">
            Related Contacts</router-link>
        </li>
      </ul>
    </template>

    <template slot="content">

      <form class="needs-validation" novalidate>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Title</label>
            <div class="col-sm-10">
              <input v-model="form.title" id="title" type="text" class="form-control" placeholder="job title" />
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Code</label>
            <div class="col-sm-10">
              <input v-model="form.code" id="code" type="text" class="form-control" placeholder="display code" />
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Parent</label>
            <div class="col-sm-10">
              <select-box v-model="form.parent_id" :data="jobs" :default="defaultParent" :multi-select="false" id="parent_id"
                name="jobParent" placeholder="select parent..." search-placeholder="search..."></select-box>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Profile</label>
            <div class="col-sm-10">
              <select-box v-model="form.form_id" :data="profiles" :default="defaultProfile" :multi-select="false" id="form_id"
                name="jobProfile" placeholder="select profile..." search-placeholder="search..."></select-box>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Contacts</label>
            <div class="col-sm-10">
              <select-box v-model="form.contacts" :data="contacts" :multi-select="true" id="contacts"
                name="contacts" placeholder="select contacts..." search-placeholder="search..."></select-box>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-12 col-form-lable">Status</label>

            <div class="custom-control custom-radio custom-control-inline">
              <input v-model="form.isactive" type="radio" id="jobEnable" name="status" value="1" class="custom-control-input"
                checked>
              <label class="custom-control-label" for="jobEnable">Enable</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
              <input v-model="form.isactive" type="radio" id="jobDisable" name="status" value="0" class="custom-control-input">
              <label class="custom-control-label" for="jobDisable">Disable</label>
            </div>
          </div>
        </div>

        <form-gen :schema="schema" v-model="form.meta"></form-gen>

      </form>

    </template>
  </nav-view>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import NavView from "../../../framework/NavView.vue";
import Selectbox from "../../../framework/BaseSelectBox.vue";
import FormGen from "../../../framework/BaseFormGenerator/BaseFormGenerator.vue";

export default {
  name: "JobDetails",
  components: {
    "nav-view": NavView,
    "form-gen": FormGen,
    "select-box": Selectbox
  },
  props: ["id"],
  data() {
    return {
      form: {
        title: "",
        code: "",
        isactive: 1,
        parent_id: null,
        form_id: null,
        contacts: [],
        meta: null
      },
      currentJob: null,
      schema: "",
      formData: "",
      defaultParent: null,
      defaultProfile: null,
      defaultContacts: null,
      loading: false
    };
  },

  watch: {
    "form.form_id": function(value) {
      if (value !== null && value !== undefined) {
        this.schema = JSON.parse(
          this.getList("form", item => item.id == value)[0].body
        );
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
    this.clear("job");
  },

  mounted() {
    this.loading = true;
    let p1 = this.fetch({
      resource: "job",
      query: { with: "childs,contacts,requests,activities" }
    });
    let p2 = this.fetch({ resource: "form" });
    let p3 = this.fetch({ resource: "contact" });
    Promise.all([p1, p2, p3])
      .then(() => {
        this.currentJob = this.getList("job", item => item.id == this.id)[0];
        this.defaultParent = this.currentJob.parent_id;
        this.defaultProfile = this.currentJob.form_id;
        this.form.contacts = this.currentJob.contacts.map(item => item.id);        
        if (this.currentJob.form_id !== null) {
          this.schema = JSON.parse(
            this.getList("form", item => item.id == this.currentJob.form_id)[0]
              .body
          );
        }
        Object.assign(this.form, this.currentJob);
        this.loading = false;
      })
      .catch(error => {
        console.log(error);
      });
  },

  methods: {
    ...mapActions({
      update: "resource/update",
      fetch: "resource/list",
      clear: "resource/clearList"
    }),

    onSave(event) {      
      this.update({ resource: "job", id: this.id, data: this.form })
        .then(() => {
          console.log("job updated successfuly.");
        })
        .catch(error => {
          console.log(error);
          this.$formFeedback(error.response.data.errors);
        });
    },

    onCancel() {
      this.$router.go(-1);
    }
  }
};
</script>

<style>
</style>