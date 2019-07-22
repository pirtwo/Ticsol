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
      <!-- Profile Form -->
      <form>
        <!-- profile Name -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Profile Name <i class="field-required">*</i></label>
            <div class="col-sm-10">
              <input
                v-model="$v.form.name.$model"
                id="title"
                type="text"
                :class="[{'is-invalid' : $v.form.name.$error } ,'form-control']"
                placeholder="Enter name for profile..."
              >
              <div
                class="invalid-feedback"
                v-if="!$v.form.name.required"
              >
                Name is required.
              </div>
            </div>
          </div>
        </div>

        <!-- meta -->
        <form-builder
          v-model="frmBuilder"
          :disable-fields="['hidden', 'button', 'paragraph', 'header']"
          :disabled-attrs="['className', 'value']"
          :disabled-action-buttons="[]"
          :control-order="[]"
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

  components: {
    "form-builder": FormBuilder
  },

  props: ["id"],

  data() {
    return {
      frmBuilder: {},
      form: {
        name: "",
        schema: ""
      }
    };
  },

  validations: {
    form: {
      name: { required }
    }
  },

  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.clearForm();
    });
  },

  mounted() {
    if (this.id) {
      this.startLoading();
      this.fetchItem({ resource: "form", id: this.id })
        .then(respond => {
          this.form.name = respond.name;
          this.frmBuilder.actions.setData(JSON.stringify(respond.schema));
          this.stopLoading();
        })
        .catch(error => {
          console.log(error);
          this.stopLoading();
        });
    }
  },

  methods: {
    ...mapActions({
      create: "resource/create",
      update: "resource/update",
      fetchItem: "resource/show"
    }),

    onSubmit(e) {
      e.preventDefault();
      e.target.disabled = true;

      this.$v.$touch();
      if (this.$v.$invalid) {
        e.target.disabled = false;
        return;
      }

      this.form.schema = this.frmBuilder.actions.getData();
      this.create({ resource: "form", data: this.form })
        .then(() => {
          e.target.disabled = false;
          this.showMessage(
            `Profile <b>${this.form.name}</b> created successfuly.`,
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

      this.form.schema = this.frmBuilder.actions.getData();
      this.update({ resource: "form", id: this.id, data: this.form })
        .then(() => {
          e.target.disabled = false;
          this.showMessage(
            `Profile <b>${this.form.name}</b> created successfuly.`,
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
      this.form.name = "";
      this.form.schema = "";
      this.$v.form.$reset();
    },

    onCancel(e) {
      e.preventDefault();
      this.$router.push({ name: "profileList" });
    }
  }
};
</script>

<style scoped>
</style>
