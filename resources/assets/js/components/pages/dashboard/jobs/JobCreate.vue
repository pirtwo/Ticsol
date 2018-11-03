<template>
  <nav-view 
    :scrollbar="true" 
    :loading="loading" 
    padding="p-5">

    <template slot="toolbar"/>

    <template slot="drawer">

      <ul class="v-menu">
        <li class="menu-title">Actions</li>
        <li>
          <button 
            class="btn btn-light" 
            @click="onSubmit">                        
            Save
          </button>
        </li>
        <li>
          <button 
            class="btn btn-light" 
            @click="onCancel">                        
            Cancel
          </button>
        </li>
        <li class="menu-title">Links</li>
        <li><router-link :to="{ name: 'jobList' }">Jobs</router-link></li>
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
                placeholder="job title">
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
                placeholder="display code">
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Parent</label>
            <div class="col-sm-10">
              <select-box
                v-model="form.parent_id"
                :data="jobs"
                :multi-select="false"
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
              <select-box
                v-model="form.form_id"
                :data="profiles"
                :multi-select="false" 
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
              <select-box
                v-model="form.contacts"
                :data="contacts"
                :multi-select="true" 
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
                checked>
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
                class="custom-control-input">
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
import NavView from "../../../framework/NavView.vue";
import Selectbox from "../../../framework/BaseSelectBox.vue";
import FormGen from "../../../framework/BaseFormGenerator/BaseFormGenerator.vue";

export default {
  name: "JobCreate",

  components: {
    "nav-view": NavView,
    "form-gen": FormGen,
    "select-box": Selectbox
  },

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
      loading: false
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
      let schema = this.getList(
        "form",
        item => item.id == this.form.form_id
      )[0];
      if (schema !== undefined) return JSON.parse(schema.body);
    }
  },

  created() {
    this.clear("job");
  },

  mounted() {
    this.loading = true;
    let p1 = this.fetch({ resource: "job" });
    let p2 = this.fetch({ resource: "form" });
    let p3 = this.fetch({ resource: "contact" });
    Promise.all([p1, p2, p3])
      .then(() => {
        this.loading = false;
      })
      .catch(error => {
        console.log(error);
      });
  },

  methods: {
    ...mapActions({
      fetch: "resource/list",
      create: "resource/create",
      clear: "resource/clearList"
    }),

    onSubmit(e) {
      e.target.disabled = true;
      this.create({ resource: "job", data: this.form })
        .then(respond => {
          e.target.disabled = false;
          console.log("job created successfuly");
        })
        .catch(error => {
          console.log(error.response);
          e.target.disabled = false;
          this.$formFeedback(error.response.data.errors);
        });
      e.preventDefault();
    },

    onCancel(e) {
      this.$router.go(-1);
      e.preventDefault();
    }
  }
};
</script>

<style scoped>
</style>