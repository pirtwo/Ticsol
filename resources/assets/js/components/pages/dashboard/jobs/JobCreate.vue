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
          <button 
            class="btn btn-light" 
            @click="onSubmit">Save</button>
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
      </ul>
    </template>

    <template slot="content">
      <form 
        class="needs-validation" 
        novalidate>
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Title</label>
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
            <label class="col-sm-2 col-form-label">Code</label>
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
            <label class="col-sm-2 col-form-label">Parent</label>
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
            <label class="col-sm-2 col-form-label">Profile</label>
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
            <label class="col-sm-2 col-form-label">Contacts</label>
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
            <label class="col-sm-12 col-form-label">Status</label>

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
      </form>

      <form-gen 
        :schema="schema" 
        v-model="form.meta"/>
    </template>
  </nav-view>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import PageMixin from "../../../../mixins/page-mixin.js";
import NavView from "../../../framework/NavView.vue";
import FormGen from "../../../framework/BaseFormGenerator/BaseFormGenerator.vue";

export default {
  name: "JobCreate",

  mixins: [PageMixin],

  components: {
    "nav-view": NavView,
    "form-gen": FormGen
  },

  data() {
    return {
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

    schema: function() {
      let profile = this.getList(
        "form",
        item => item.id == this.form.profile.key
      )[0];
      if (profile !== undefined) {
        let s = profile.schema;
        return profile.schema;
      }
    }
  },

  created() {
    
  },

  mounted() {
    this.loadingStart();
    let p1 = this.fetch({ resource: "job" });
    let p2 = this.fetch({ resource: "form" });
    let p3 = this.fetch({ resource: "contact" });
    Promise.all([p1, p2, p3])
      .then(() => {
        this.loadingStop();
      })
      .catch(error => {
        this.loadingStop();
        this.showMessage(error.message, "danger");
      });
  },

  methods: {
    ...mapActions({
      fetch: "resource/list",
      create: "resource/create",
    }),

    onSubmit(e) {
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

    onCancel(e) {
      e.preventDefault();
      this.$router.push({ name: "jobList" });
    }
  }
};
</script>

<style scoped>
</style>