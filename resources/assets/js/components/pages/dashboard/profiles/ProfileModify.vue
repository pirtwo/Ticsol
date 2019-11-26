<template>
  <app-main
    :scrollbar="true"
    :loading="isLoading"
    padding="p-5"
  >
    <template slot="drawer">
      <template slot="toolbar" />

      <ul class="v-menu">
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
        <li class="menu-title">
          Links
        </li>
        <li>
          <router-link
            class="btn btn-link"
            :to="{ name: 'profileList' }"
          >
            Profiles
          </router-link>
        </li>
      </ul>
    </template>

    <template slot="content">      
      <form>        
        <div class="form-group">
          <div class="form-row">            
            <div class="col-md-5">
              <!-- profile Name -->
              <label class="">
                Profile Name
                <i class="field-required">*</i>
              </label>
              <input
                v-model="$v.form.name.$model"
                id="title"
                type="text"
                :class="[{'is-invalid' : $v.form.name.$error } ,'form-control']"
                placeholder="profile name"
              >
              <div
                class="invalid-feedback"
                v-if="!$v.form.name.required"
              >
                Name is required.
              </div>
            </div>

            
            <div class="col-md-5">
              <!-- Parent -->
              <label class="">Parent</label>
              <ts-chipsbox
                v-model="form.parent"
                :data="profiles"
                id="parent_id"
                name="profileParent"
                placeholder="Select profile parent"
                search-placeholder="search here..."
              />              
            </div>

            <!-- Billable -->
            <div class="col-md-2">              
              <div class="custom-control custom-switch mt-md-5 ml-md-2">
                <input
                  v-model="form.billable"
                  type="checkbox"
                  class="custom-control-input"
                  id="billable"
                >
                <label
                  class="custom-control-label"
                  for="billable"
                >Billable</label>
              </div> 
            </div>
          </div>
        </div>

        <!-- schema -->
        <div
          id="formBuilder"
          class="mb-4 text-dark"
        />
      </form>
    </template>
  </app-main>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import { required } from "vuelidate/lib/validators";
import FormBuilder from "../../../base/FormBuilder.vue";
import pageMixin from "../../../../mixins/page-mixin";

export default {
  name: "ProfileModify",

  mixins: [pageMixin],

  props: ["id"],

  data() {
    return {
      frmBuilder: {},
      profileList: [],
      form: {
        name: "",
        parent: null,
        billable: false,
        schema: ""
      }
    };
  },

  validations: {
    form: {
      name: { required }
    }
  },

  watch: {
    profile: function(val) {
      this.populateForm(val);
    }
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    profile: function() {
      if (!this.id) return null;
      return this.getList("form")[0];
    },

    profiles: function() {
      return this.profileList.map(item => {
        return { key: item.id, value: item.hierarchy, parentKey: item.parent_id };
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
    this.clear("form");
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

      await $("#formBuilder")
        .formBuilder({
          disabledAttrs: ["className", "value"],
          disableFields: ["hidden", "button", "paragraph", "header"],
          controlOrder: [],
          controlPosition: "right",
          stickyControls: {
            enable: false,
            offset: { top: 5, bottom: "auto", right: "auto" }
          },
          showActionButtons: false,
          disabledActionButtons: []
        })
        .promise.then(formBuilder => {
          this.frmBuilder = formBuilder;
        });

      let p1 = await this.list({ resource: "form" }).then(data => {
        this.profileList = data;
      });
      let p2 = this.id
        ? await this.list({ resource: "form", query: { eq: `id,${this.id}` } })
        : new Promise(resolve => resolve());

      this.stopLoading();
    },

    populateForm(profile) {
      this.form.name = profile.name;
      this.form.parent = this.profiles.find(
        item => item.key == profile.parent_id
      ) || null;
      this.form.billable = profile.billable;
      this.frmBuilder.actions.setData(JSON.stringify(profile.schema));
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
      form.name = this.form.name;
      form.parent_id = this.form.parent ? this.form.parent.key : null;
      form.billable = this.form.billable;
      form.schema = this.frmBuilder.actions.getData();
      
      this.create({ resource: "form", data: form })
        .then(() => {          
          this.showMessage(
            `Profile <b>${this.form.name}</b> created successfuly.`,
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

    onSave(e) {
      e.preventDefault();
      e.target.disabled = true;

      this.$v.$touch();
      if (this.$v.$invalid) {
        e.target.disabled = false;
        return;
      }

      let form = {};
      form.name = this.form.name;
      form.parent_id = this.form.parent ? this.form.parent.key : null;
      form.billable = this.form.billable;
      form.schema = this.frmBuilder.actions.getData();

      this.update({ resource: "form", id: this.id, data: form })
        .then(() => {         
          this.showMessage(
            `Profile <b>${this.form.name}</b> created successfuly.`,
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

    clearForm() {
      this.form.name = "";
      this.form.parent = null;
      this.form.schema = "";
      this.form.billable = false;
      this.$v.form.$reset();
    },

    onCancel(e) {
      e.preventDefault();
      this.$router.go(-1);
    }
  }
};
</script>

<style scoped>
</style>
