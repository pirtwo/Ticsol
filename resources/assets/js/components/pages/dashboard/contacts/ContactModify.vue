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
        <li v-if="!contact">
          <button
            class="btn"
            @click="clearForm"
          >
            New
          </button>
        </li>
        <li v-if="!contact">
          <button
            class="btn"
            @click="onSubmit"
          >
            Submit
          </button>
        </li>
        <li v-if="contact">
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
            :to="{ name: 'contactList' }"
          >
            Contacts
          </router-link>
        </li>
        <li v-if="contact">
          <router-link
            :class="[{'disabled' : this.contact.jobs.length === 0}, 'btn btn-link' ]"
            role="button"
            :aria-disabled="this.contact.jobs.length === 0"
            :to="{ name: 'jobList', params : { opt: 'in', col: 'job.contacts.id', val: this.id } }"
          >
            Related Jobs
            <ts-badge class="badge-light ml-auto">
              {{ contact.jobs.length }}
            </ts-badge>
          </router-link>
        </li>
      </ul>
    </template>

    <template slot="content">
      <!-- Adress list validation summary -->
      <div
        v-if="$v.form.addresses.$error"
        class="alert alert-danger"
        role="alert"
      >
        <b class="alert-heading">Fix these problems:</b>
        <ul>
          <template v-for="(item, index) in $v.form.addresses.$each.$iter">
            <li
              :key="index"
              v-if="item.$invalid"
            >
              <div>Row #{{ 1 + (+index) }}:</div>
              <div v-if="!item.number.required">
                Number is required.
              </div>
              <div v-if="!item.street.required">
                Street is required.
              </div>
              <div v-if="!item.suburb.required">
                Suburb is required.
              </div>
              <div v-if="!item.state.required">
                State is required.
              </div>
              <div v-if="!item.country.required">
                Country is required.
              </div>
              <div v-if="!item.postcode.required">
                Post code is required.
              </div>
            </li>
          </template>
        </ul>
        <hr>
        <p class="mb-0">
          Your address list have some errors. Fix them to be able to save the contact.
        </p>
      </div>

      <!-- Contact Form -->
      <form>
        <!-- Group -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">
              Group
              <i class="field-required">*</i>
            </label>
            <div class="col-sm-10">
              <select              
                id="group"
                v-model="$v.form.group.$model"
                :class="[{'is-invalid' : $v.form.group.$error } ,'custom-select']"
                :disabled="contact"
                @change="form.email = ''"
              >
                <option
                  selected
                  disabled
                >
                  Select contact group
                </option>
                <option value="customer">
                  Customer
                </option>
                <option value="user">
                  User
                </option>
                <option value="emergency">
                  Emergency
                </option>
              </select>
              <div
                class="invalid-feedback"
                v-if="!$v.form.group.required"
              >
                Group name is required.
              </div>
            </div>
          </div>
        </div>

        <!-- User -->
        <div
          class="form-group"
          v-if="form.group !== 'customer' && form.group !== ''"
        >
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">User</label>
            <div class="col-sm-10">
              <input
                v-model="userFullname"
                type="text"
                class="form-control"
                name="user"
                id="user"
                readonly
              >
            </div>
          </div>
        </div>

        <!-- First Name -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">
              First Name
              <i class="field-required">*</i>
            </label>
            <div class="col-sm-10">
              <input
                v-model="$v.form.firstname.$model"
                type="text"
                :class="[{'is-invalid' : $v.form.firstname.$error } ,'form-control']"
                id="firstname"
                placeholder="contact first name"
              >
              <div
                class="invalid-feedback"
                v-if="!$v.form.firstname.required"
              >
                First name is required.
              </div>
            </div>
          </div>
        </div>

        <!-- Last Name -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">
              Last Name
              <i class="field-required">*</i>
            </label>
            <div class="col-sm-10">
              <input
                v-model="$v.form.lastname.$model"
                type="text"
                :class="[{'is-invalid' : $v.form.lastname.$error } ,'form-control']"
                id="lastname"
                placeholder="contact last name"
              >
              <div
                class="invalid-feedback"
                v-if="!$v.form.lastname.required"
              >
                Last name is required.
              </div>
            </div>
          </div>
        </div>

        <!-- Email -->
        <div
          v-show="form.group === 'customer'"
          class="form-group"
        >
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">
              E-Mail
              <i class="field-required">*</i>
            </label>
            <div class="col-sm-10">
              <input
                v-model="$v.form.email.$model"
                type="text"
                :class="[{'is-invalid' : $v.form.email.$error } ,'form-control']"
                id="email"
                placeholder="contact e-mail"
              >
              <div
                class="invalid-feedback"
                v-if="!$v.form.email.required"
              >
                E-Mail is required.
              </div>
            </div>
          </div>
        </div>

        <!-- Tel Num -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Tel Number</label>
            <div class="col-sm-10">
              <input
                v-model="form.telephone"
                type="tel"
                class="form-control"
                id="telephone"
                placeholder="contact tel number"
              >
            </div>
          </div>
        </div>

        <!-- Mobile Num -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">
              Mobile Number
              <i class="field-required">*</i>
            </label>
            <div class="col-sm-10">
              <input
                v-model="$v.form.mobilephone.$model"
                type="tel"
                :class="[{'is-invalid' : $v.form.mobilephone.$error } ,'form-control']"
                id="mobilephone"
                placeholder="contact mobile number"
              >
              <div
                class="invalid-feedback"
                v-if="!$v.form.mobilephone.required"
              >
                Mobile number is required.
              </div>
            </div>
          </div>
        </div>
      </form>

      <ts-section
        class="mb-3"
        title="Address"
      > 
        <ts-grid
          v-model="form.addresses"
          :columns="columns"
          :has-toolbar="false"
          @inserted="place = {}"
          @modalHide="place = {}"
        >
          <template slot-scope="{ item }">
            <td>{{ item.number }}</td>
            <td>{{ item.unit }}</td>
            <td>{{ item.street }}</td>
            <td>{{ item.suburb }}</td>          
            <td>{{ item.state }}</td>
            <td>{{ item.country }}</td>
            <td>{{ item.postcode }}</td>
          </template>
          <template
            slot="grid-modal"
            slot-scope="{ item }"
          >
            <div class="p-2">
              <!-- google places -->
              <div class="form-group">
                <div class="form-row">
                  <label class="col-md-3 col-form-lable">Find Address</label>
                  <div class="col-md-9">
                    <goole-places
                      :value="place"
                      @input="placeChange($event, item)"
                    />
                  </div>
                </div>
              </div>
              <hr>

              <!-- Unit -->
              <div class="form-group">
                <div class="form-row">
                  <label class="col-md-3 col-form-lable">Unit</label>
                  <div class="col-md-9">
                    <input
                      v-model="item.unit"
                      type="text"
                      placeholder="please enter unit..."
                      class="form-control"
                      id="unit"
                    >
                  </div>
                </div>
              </div>

              <!-- Number -->
              <div class="form-group">
                <div class="form-row">
                  <label class="col-md-3 col-form-lable">
                    Number
                    <i class="field-required">*</i>
                  </label>
                  <div class="col-md-9">
                    <input
                      v-model="item.number"
                      type="text"
                      placeholder="please enter number..."
                      class="form-control"
                      id="number"
                    >
                  </div>
                </div>
              </div>

              <!-- Street -->
              <div class="form-group">
                <div class="form-row">
                  <label class="col-md-3 col-form-lable">
                    Street
                    <i class="field-required">*</i>
                  </label>
                  <div class="col-md-9">
                    <input
                      v-model="item.street"
                      type="text"
                      placeholder="please enter street..."
                      class="form-control"
                      id="street"
                    >
                  </div>
                </div>
              </div>

              <!-- Suburb -->
              <div class="form-group">
                <div class="form-row">
                  <label class="col-md-3 col-form-lable">
                    Suburb
                    <i class="field-required">*</i>
                  </label>
                  <div class="col-md-9">
                    <input
                      v-model="item.suburb"
                      type="text"
                      placeholder="please enter suburb..."
                      class="form-control"
                      id="suburb"
                    >
                  </div>
                </div>
              </div>

              <!-- State -->
              <div class="form-group">
                <div class="form-row">
                  <label class="col-md-3 col-form-lable">
                    State
                    <i class="field-required">*</i>
                  </label>
                  <div class="col-md-9">
                    <input
                      v-model="item.state"
                      type="text"
                      placeholder="please enter state..."
                      class="form-control"
                      id="unit"
                    >
                  </div>
                </div>
              </div>

              <!-- Country -->
              <div class="form-group">
                <div class="form-row">
                  <label class="col-md-3 col-form-lable">
                    Country
                    <i class="field-required">*</i>
                  </label>
                  <div class="col-md-9">
                    <input
                      v-model="item.country"
                      type="text"
                      placeholder="please enter country..."
                      class="form-control"
                      id="country"
                    >
                  </div>
                </div>
              </div>

              <!-- Post Code -->
              <div class="form-group">
                <div class="form-row">
                  <label class="col-md-3 col-form-lable">
                    Post Code
                    <i class="field-required">*</i>
                  </label>
                  <div class="col-md-9">
                    <input
                      v-model="item.postcode"
                      type="text"
                      placeholder="please enter post code..."
                      class="form-control"
                      id="postcode"
                    >
                  </div>
                </div>
              </div>
            </div>
          </template>
        </ts-grid>
      </ts-section>
    </template>
  </app-main>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { required } from "vuelidate/lib/validators";
import pageMixin from "../../../../mixins/page-mixin";
import GooglePlaces from "../../../base/GooglePlaces.vue";

export default {
  name: "ContactModify",

  mixins: [pageMixin],

  components: {
    "goole-places": GooglePlaces
  },

  props: ["id"],

  data() {
    return {
      place: {},
      form: {
        group: "",
        firstname: "",
        lastname: "",
        email: "",
        telephone: "",
        mobilephone: "",
        addresses: []
      },
      columns: [
        { key: 1, value: "Number" },
        { key: 2, value: "Unit" },
        { key: 3, value: "Street" },
        { key: 4, value: "Suburb" },        
        { key: 5, value: "State" },
        { key: 6, value: "Country" },
        { key: 7, value: "Post Code" }
      ]
    };
  },

  validations() {
    return {
      form: {
        group: { required },
        firstname: { required },
        lastname: { required },
        email: this.form.group === "customer" ? { required } : {},
        mobilephone: { required },
        addresses: {
          $each: {
            number: { required },
            street: { required },
            suburb: { required },
            state: { required },
            country: { required },
            postcode: { required }
          }
        }
      }
    };
  },

  watch: {
    contact: function(val) {
      this.populateForm(val);
    }
  },

  computed: {
    ...mapGetters({
      userFullname: "user/getFullname",
      getList: "resource/getList"
    }),

    contact: function() {
      if (!this.id) return null;
      return this.getList("contact")[0];
    }
  },

  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.clearForm();
      vm.loadAssets();
    });
  },

  beforeRouteLeave(to, from, next) {
    this.clear("contact");
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
      let p1 = this.id
        ? this.list({
            resource: "contact",
            query: { eq: `id,${this.id}`, with: "jobs,addresses" }
          })
        : new Promise(resolve => resolve());

      Promise.all([p1]).finally(() => {
        this.stopLoading();
      });
    },

    populateForm(contact) {
      this.form = Object.assign({}, this.form, contact);
    },

    placeChange(place, item) {
      this.$set(item, "number", place.street_number);
      this.$set(item, "street", place.route);
      this.$set(item, "suburb", place.locality);
      this.$set(item, "state", place.administrative_area_level_1);
      this.$set(item, "country", place.country);
      this.$set(item, "postcode", place.postal_code);
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
      form.group = this.form.group;
      form.firstname = this.form.firstname;
      form.lastname = this.form.lastname;
      if(this.form.group === 'customer') form.email = this.form.email;
      form.telephone = this.form.telephone;
      form.mobilephone = this.form.mobilephone;
      form.addresses = this.form.addresses;

      this.create({ resource: "contact", data: form })
        .then(() => {
          e.target.disabled = false;
          this.showMessage(
            `Contact <b>${this.form.firstname} ${this.form.lastname}</b> created successfuly.`,
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
      form.group = this.form.group;
      form.firstname = this.form.firstname;
      form.lastname = this.form.lastname;
      if(this.form.group === 'customer') form.email = this.form.email;
      form.telephone = this.form.telephone;
      form.mobilephone = this.form.mobilephone;
      form.addresses = this.form.addresses;

      this.update({ resource: "contact", id: this.id, data: form })
        .then(() => {
          e.target.disabled = false;
          this.showMessage(
            `Contact <b>${this.form.firstname} ${this.form.lastname}</b> updated successfuly.`,
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
      this.form.user_id = "";
      this.form.group = "";
      this.form.firstname = "";
      this.form.lastname = "";
      this.form.telephone = "";
      this.form.mobilephone = "";
      this.form.addresses = [];
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