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
                  :disabled="!userCan('job', ['full', 'update']) || !form.profile || jobs.length == 0"
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

          <!-- Billing Details -->
          <ts-section
            v-if="isBillable"
            title="Billing Details"
            class="mb-5"
          >
            <!-- payment type -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-md-4 col-form-lable">Prepaid or Paid on Arrears?</label>
                <div class="col">
                  <div class="custom-control custom-radio custom-control-inline">
                    <input
                      v-model="billing.paymentType"
                      type="radio"
                      id="prepaid"
                      name="paymentType"
                      value="prepaid"
                      class="custom-control-input"
                      checked
                    >
                    <label
                      class="custom-control-label"
                      for="prepaid"
                    >Prepaid</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input
                      v-model="billing.paymentType"
                      type="radio"
                      id="arrears"
                      name="paymentType"
                      value="inArrears"
                      class="custom-control-input"
                    >
                    <label
                      class="custom-control-label"
                      for="arrears"
                    >Paid on Arrears</label>
                  </div>
                </div>
              </div>
            </div>

            <!-- rate & unit type -->
            <div class="form-group">
              <div class="form-row">
                <!-- rate -->
                <label class="col-sm-4">Bill job at</label>
                <div class="col align-self-start">
                  <div :class="[{'is-invalid' : $v.billing.rate.$error } ,'input-group']">
                    <input
                      v-model="billing.rate"
                      type="text"
                      :class="[{'is-invalid' : $v.billing.rate.$error } ,'form-control']"
                      placeholder="Enter billing rate"
                    >                 
                    <div class="input-group-append">
                      <span class="input-group-text">$</span>
                    </div>  
                  </div>                  
                  <div
                    class="invalid-feedback"
                    v-if="!$v.billing.rate.required"
                  >
                    Biling rate is required.
                  </div>   
                  <div
                    class="invalid-feedback"
                    v-if="!$v.billing.rate.decimal"
                  >
                    Biling rate must be a number.
                  </div>   
                  <div
                    class="invalid-feedback"
                    v-if="!$v.billing.rate.minValue"
                  >
                    Biling rate must be zero or more.
                  </div>                
                </div>

                <!-- unit type -->
                <div class="col align-self-start">
                  <div :class="[{'is-invalid' : $v.billing.unitType.$error } ,'input-group']">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Per</span>
                    </div>
                    <select
                      v-model="billing.unitType"
                      name="unitType"
                      id="unitType"
                      :class="[{'is-invalid' : $v.billing.unitType.$error } ,'custom-select']"
                    >
                      <option
                        selected
                        disabled
                      >
                        Choose Unit Type...
                      </option>
                      <option value="minutes">
                        Minutes
                      </option>
                      <option value="days">
                        Days
                      </option>
                    </select>
                  </div>                  
                  <div
                    class="invalid-feedback"
                    v-if="!$v.billing.unitType.required"
                  >
                    Biling unit type is required.
                  </div>
                </div>
              </div>
            </div>

            <!-- unit -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-md-4">For</label>
                <div class="col-md-8">
                  <div :class="[{'is-invalid' : $v.billing.unit.$error } ,'input-group']">
                    <input
                      v-model="billing.unit"
                      type="text"
                      :class="[{'is-invalid' : $v.billing.unit.$error } ,'form-control']"
                      placeholder="Enter billing unit"
                    >                 
                    <div class="input-group-append">
                      <span
                        class="input-group-text"
                        style="text-transform: capitalize;"
                      >{{ billing.unitType }}</span>
                    </div>                                  
                  </div>
                  <div
                    class="invalid-feedback"
                    v-if="!$v.billing.unit.required"
                  >
                    Biling unit is required.
                  </div>  
                  <div
                    class="invalid-feedback"
                    v-if="!$v.billing.unit.decimal"
                  >
                    Biling unit must be a number.
                  </div>
                  <div
                    class="invalid-feedback"
                    v-if="!$v.billing.unit.minValue"
                  >
                    Biling unit must be zero or more.
                  </div>        
                </div>   
              </div>
            </div>

            <!-- total -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-4">Total</label>
                <div class="col-sm-8 input-group">
                  <input
                    v-model="total"
                    type="text"
                    class="form-control"
                    placeholder
                    readonly
                  >
                  <div class="input-group-append">
                    <span class="input-group-text">$</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- revenue account -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-md-4 col-form-lable">Revenue Account</label>
                <div class="col">
                  <ts-chipsbox
                    v-model="billing.revenueAccount"
                    :data="revenueAccountsList"
                    :multi="false"
                    id="revenueAccounts"
                    name="revenueAccounts"
                    placeholder="Select revenue account"
                    search-placeholder="search here..."
                  />
                </div>
              </div>
            </div>

            <!-- allow overbilling -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-md-4 col-form-lable">Allow Billing above job total value</label>
                <div class="col">
                  <div class="custom-control custom-switch">
                    <input
                      v-model="billing.allowOverbilling"
                      type="checkbox"
                      class="custom-control-input"
                      id="allowOverbilling"
                    >
                    <label
                      class="custom-control-label"
                      for="allowOverbilling"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- fallback rate -->
            <div class="form-group">
              <div class="form-row">
                <label
                  class="col-md-4 col-form-lable"
                >When billing above total job value apply same job rate or company default rate?</label>
                <div class="col">
                  <div class="custom-control custom-radio custom-control-inline">
                    <input
                      v-model="billing.jobFallbackRate"
                      value="sameRate"
                      type="radio"
                      id="sameRate"
                      name="jobFallbackRate"
                      class="custom-control-input"
                    >
                    <label
                      class="custom-control-label"
                      for="sameRate"
                    >Same Rate</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input
                      v-model="billing.jobFallbackRate"
                      value="companyDefault"
                      type="radio"
                      id="companyDefault"
                      name="jobFallbackRate"
                      class="custom-control-input"
                    >
                    <label
                      class="custom-control-label"
                      for="companyDefault"
                    >Company Default</label>
                  </div>
                </div>
              </div>
            </div>
          </ts-section>

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
import { required, decimal, minValue } from "vuelidate/lib/validators";
import PageMixin from "../../../../mixins/page-mixin.js";
import FormGen from "../../../base/formGenerator/BaseFormGenerator";
import { constants } from "crypto";
import { parse } from "path";

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
      client: null,
      form: {
        title: "",
        code: "",
        isactive: 1,
        parent: null,
        profile: null,
        contacts: [],
        meta: null
      },
      billing: {
        paymentType: "prepaid",
        allowOverbilling: false,
        jobFallbackRate: "sameRate",
        unitType: "minutes",
        unit: "",
        rate: "",
        revenueAccount: null
      }
    };
  },

  validations() {
    if (this.isBillable) {
      return {
        form: {
          title: { required },
          code: { required }
        },
        billing: {
          paymentType: { required },
          rate: { required, decimal, minValue: minValue(0) },
          unit: { required, decimal, minValue: minValue(0) },
          unitType: { required },
          allowOverbilling: { required },
          jobFallbackRate: { required },
          revenueAccount: {}
        }
      };
    } else {
      return {
        form: {
          title: { required },
          code: { required }
        }
      };
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
      userClientId: "user/getClientId",
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
        return {
          key: item.id,
          value: item.hierarchy,
          billable: item.billable,
          parentKey: item.parent_id
        };
      });
    },

    contacts: function() {
      return this.getList("contact").map(item => {
        return { key: item.id, value: `${item.firstname} ${item.lastname}` };
      });
    },

    isBillable: function() {
      return this.form.profile ? this.form.profile.billable : false;
    },

    total: function() {
      if (this.billing.rate && this.billing.unit) {
        try {
          let total = parseFloat(this.billing.unit) * parseFloat(this.billing.rate);
          return isNaN(total) || total <= 0 ? 0 : total.toFixed(4);
        } catch (error) {
          return 0;
        }
      }
      return 0;
    },

    revenueAccountsList: function() {
      let accounts = this.client
        ? this.client.billing_settings.revenue_accounts
        : [];
      return accounts.map(item => {
        return { key: item.Id, value: item.Name };
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
      show: "resource/show",
      create: "resource/create",
      update: "resource/update"
    }),

    async loadAssets() {
      this.startLoading();

      let p1 = this.list({ resource: "form" });
      let p2 = this.list({ resource: "contact" });
      let p3 = this.list({
        resource: "job",
        query: { with: "parent,profile,contacts" }
      });
      let p4 = this.show({ resource: "client", id: this.userClientId }).then(
        data => {
          this.client = data;
        }
      );

      Promise.all([p1, p2, p3, p4]).finally(() => {
        if (this.id) this.populateForm(this.job);
        else this.billingDefaults();
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
            billable: job.profile.billable,
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

      // populate billing details
      if (this.isBillable && job.billing) {
        let billing = job.billing;
        this.billing.paymentType = billing.payment_type;
        this.billing.rate = billing.rate;
        this.billing.unit = billing.unit;
        this.billing.unitType = billing.unit_type;
        this.billing.allowOverbilling = billing.allow_over_billing;
        this.billing.jobFallbackRate = billing.job_fallback_rate;
        this.billing.revenueAccount = this.revenueAccountsList.find(
          item => item.key == billing.revenue_account_id
        );
      }

      // populate custom fields
      this.schema =
        job.form_id !== null
          ? this.getList("form", item => item.id == job.form_id)[0].schema
          : null;
    },

    /** populate billing defaults. */
    billingDefaults() {
      let defaults = this.client.billing_defaults;
      this.billing.paymentType = defaults.payment_type;
      this.billing.allowOverbilling = defaults.allow_over_billing;
      this.billing.jobFallbackRate = defaults.job_fallback_rate;
      this.billing.unitType = defaults.unit_type;
      this.billing.revenueAccount = this.revenueAccountsList.find(
        item => item.key == defaults.revenue_account_id
      );
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
      form = this.fillBillingDetails(form);

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
      form = this.fillBillingDetails(form);

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

    fillBillingDetails(form) {
      if (!this.isBillable) return form;

      form.payment_type = this.billing.paymentType;
      form.rate = this.billing.rate;
      form.unit = this.billing.unit;
      form.unit_type = this.billing.unitType;
      form.allow_over_billing = this.billing.allowOverbilling;
      form.job_fallback_rate = this.billing.jobFallbackRate;
      form.revenue_account_id = this.billing.revenueAccount
        ? this.billing.revenueAccount.key
        : null;

      return form;
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