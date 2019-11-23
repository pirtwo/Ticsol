<template>
  <app-main
    :scrollbar="true"
    :loading="isLoading"
    padding="p-5"
  >
    <template slot="toolbar" />

    <template slot="drawer">
      <ul class="v-menu">
        <template v-if="userCan('job', ['full', 'create', 'update'])">
          <li class="menu-title">
            Actions
          </li>
          <li v-if="!this.id">
            <button
              class="btn"
              @click="clearForm"
            >
              New
            </button>
          </li>
          <li v-if="!this.id">
            <button
              class="btn"
              @click="onSubmit"
            >
              Submit
            </button>
          </li>
          <li v-if="this.id">
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
            :to="{ name: 'jobList' }"
          >
            Jobs
          </router-link>
        </li>
        <li v-if="job">
          <router-link
            role="button"
            class="btn btn-link"
            :to="{ name: 'commentList', params : { entity: 'job', id: this.id } }"
          >
            Comments
            <ts-badge class="badge-light ml-auto">
              {{ job.commentsCount }}
            </ts-badge>
          </router-link>
        </li>
        <li v-if="job">
          <router-link
            :class="[{'disabled' : this.job.reportsCount === 0}, 'btn btn-link' ]"
            role="button"
            :aria-disabled="this.job.reportsCount === 0"
            :to="{ name: 'activityList', params : { opt: 'eq', col: 'job_id', val: this.id } }"
          >
            Activity Reports
            <ts-badge class="badge-light ml-auto">
              {{ job.reportsCount }}
            </ts-badge>
          </router-link>
        </li>
        <li v-if="job">
          <router-link
            :class="[{'disabled' : this.job.childsCount === 0}, 'btn btn-link' ]"
            role="button"
            :aria-disabled="this.job.childsCount === 0"
            :to="{ name: 'jobList', params : { opt: 'eq', col: 'parent_id', val: this.id } }"
          >
            Related Jobs
            <ts-badge class="badge-light ml-auto">
              {{ job.childsCount }}
            </ts-badge>
          </router-link>
        </li>
        <li v-if="job">
          <router-link
            :class="[{'disabled' : this.job.requestsCount === 0 }, 'btn btn-link' ]"
            role="button"
            :aria-disabled="this.job.requestsCount === 0"
            :to="{ name: 'inbox', params : { opt: 'eq', col: 'job_id', val: this.id } }"
          >
            Related Requests
            <ts-badge class="badge-light ml-auto">
              {{ job.requestsCount }}
            </ts-badge>
          </router-link>
        </li>
        <li v-if="job">
          <router-link
            :class="[{'disabled' : this.job.contactsCount === 0}, 'btn btn-link' ]"
            role="button"
            :aria-disabled="this.job.contactsCount === 0"
            :to="{ name: 'contactList', params : { opt: 'in', col: 'contact.jobs.id', val: this.id } }"
          >
            Related Contacts
            <ts-badge class="badge-light ml-auto">
              {{ job.contactsCount }}
            </ts-badge>
          </router-link>
        </li>
      </ul>
    </template>

    <template slot="content">
      <!-- Job Form -->
      <form class="needs-validation">
        <fieldset :disabled="!userCan('job', ['full', 'update'])">
          <!-- Profile -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-sm-2 col-form-lable">Profile</label>
              <div class="col-sm-10">
                <ts-chipsbox
                  v-model="form.profile"
                  :data="profiles"
                  :multi="false"
                  id="form_id"
                  name="jobProfile"
                  placeholder="select job profile"
                  search-placeholder="search here..."
                  :disabled="!userCan('job', ['full', 'update'])"
                />
              </div>
            </div>
          </div>

          <!-- title -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-sm-2 col-form-lable">
                Title
                <i class="field-required">*</i>
              </label>
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
                  Title is required.
                </div>
              </div>
            </div>
          </div>

          <!-- Code -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-sm-2 col-form-lable">
                Code
                <i class="field-required">*</i>
              </label>
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
                  Code is required.
                </div>
              </div>
            </div>
          </div>

          <!-- Parent -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-sm-2 col-form-lable">Parent</label>
              <div class="col-sm-10">
                <ts-chipsbox
                  v-model="form.parent"
                  :data="jobs"
                  :multi="false"
                  id="parent_id"
                  name="jobParent"
                  placeholder="select job parent"
                  search-placeholder="search here..."
                  :disabled="!userCan('job', ['full', 'update']) || !form.profile"
                />
              </div>
            </div>
          </div>

          <!-- contact -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-sm-2 col-form-lable">Contacts</label>
              <div class="col-sm-10">
                <ts-chipsbox
                  v-model="form.contacts"
                  :data="contacts"
                  :multi="true"
                  id="contacts"
                  name="contacts"
                  placeholder="select job contacts"
                  search-placeholder="search here..."
                  :disabled="!userCan('job', ['full', 'update'])"
                />
              </div>
            </div>
          </div>

          <!-- Status -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-sm-2 col-form-lable">
                Status
                <i class="field-required">*</i>
              </label>

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

          <!-- Custom Fields -->
          <form-gen
            :schema="schema"
            v-model="form.meta"
          />
        </fieldset>
      </form>
    </template>
  </app-main>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import { required } from "vuelidate/lib/validators";
import PageMixin from "../../../../mixins/page-mixin.js";
import FormGen from "../../../base/formGenerator/BaseFormGenerator";
import { constants } from "crypto";

export default {
  name: "JobModify",

  mixins: [PageMixin],

  components: {
    "form-gen": FormGen
  },

  props: ["id"],

  data() {
    return {
      jobList: [],
      schema: "",
      formData: "",
      form: {
        title: "",
        code: "",
        isactive: 1,
        parent: null,
        profile: null,
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
      if (!value) {
        this.schema = [];
        return;
      }

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
      userCan: "user/can",
      getList: "resource/getList"
    }),

    job: function() {
      if (!this.id) return null;
      return this.getList("job").find(item => item.id == this.id);
    },

    jobs: function() {
      if (this.form.profile) {
        return this.getList("job")
          .filter(item => {
            return (
              this.form.profile.parentKey != null &&
              item.form_id == this.form.profile.parentKey
            );
          })
          .map(item => {
            return {
              key: item.id,
              value: item.hierarchy,
              parentKey: item.parent_id
            };
          });
      } else {
        return [];
      }
    },

    profiles: function() {
      return this.getList("form").map(item => {
        return { key: item.id, value: item.hierarchy, parentKey: item.parent_id };
      });
    },

    contacts: function() {
      return this.getList("contact").map(item => {
        return { key: item.id, value: `${item.firstname} ${item.lastname}` };
      });
    }
  },

  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.clearForm();
      vm.loadAssets();
    });
  },

  beforeRouteLeave(to, from, next) {
    this.clear("job");
    this.clear("contact");
    this.clear("form");
    next();
  },

  methods: {
    ...mapActions({
      clear: "resource/clearResource",
      list: "resource/list",
      create: "resource/create",
      update: "resource/update"
    }),

    async loadAssets() {
      this.startLoading();

      let p1 = await this.list({ resource: "form" });
      let p2 = await this.list({ resource: "contact" });
      let p3 = await this.list({
        resource: "job",
        query: {
          with: "parent,profile,contacts"
        }
      });

      Promise.all([p1, p2, p3]).finally(() => {
        if (this.id) {
          this.populateForm(this.job);
        }
        this.stopLoading();
      });
    },

    populateForm(job) {
      this.form.title = job.title;
      this.form.code = job.code;
      this.form.parent = job.parent
        ? {
            key: job.parent.id,
            value: job.parent.hierarchy,
            parentKey: job.parent.parent_id
          }
        : null;
      this.form.profile = job.profile
        ? {
            key: job.profile.id,
            value: job.profile.hierarchy,
            parentKey: job.profile.parent_id
          }
        : null;
      this.form.contacts = job.contacts
        ? job.contacts.map(item => {
            return {
              key: item.id,
              value: `${item.firstname} ${item.lastname}`
            };
          })
        : [];

      this.form.meta = job.meta;

      this.schema =
        job.form_id !== null
          ? this.getList("form", item => item.id == job.form_id)[0].schema
          : null;
      console.log(this.form.parent);
      console.log(this.jobs);
    },

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
      form.parent_id = this.form.parent ? this.form.parent.key : null;
      form.form_id = this.form.profile ? this.form.profile.key : null;
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
      form.parent_id = this.form.parent ? this.form.parent.key : null;
      form.form_id = this.form.profile ? this.form.profile.key : null;
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
      this.form.parent = null;
      this.form.profile = null;
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