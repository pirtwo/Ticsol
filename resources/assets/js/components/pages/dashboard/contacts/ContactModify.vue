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
              <div>
                Row #{{ 1 + (+index) }}:
              </div>
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

      <!-- Contact form -->
      <form>
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Group</label>
            <div class="col-sm-10">
              <select
                v-model="$v.form.group.$model"
                :class="[{'is-invalid' : $v.form.group.$error } ,'custom-select']"
                id="group"
              >
                <option
                  selected
                  disabled
                >
                  Select a group...
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

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">First Name</label>
            <div class="col-sm-10">
              <input
                v-model="$v.form.firstname.$model"
                type="text"
                :class="[{'is-invalid' : $v.form.firstname.$error } ,'form-control']"
                id="firstname"
                placeholder="Enter first name..."
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

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Last Name</label>
            <div class="col-sm-10">
              <input
                v-model="$v.form.lastname.$model"
                type="text"
                :class="[{'is-invalid' : $v.form.lastname.$error } ,'form-control']"
                id="lastname"
                placeholder="Enter last name..."
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

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Tel Number</label>
            <div class="col-sm-10">
              <input
                v-model="form.telephone"
                type="tel"
                class="form-control"
                id="telephone"
                placeholder="Enter Tel number..."
              >
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Mobile Number</label>
            <div class="col-sm-10">
              <input
                v-model="$v.form.mobilephone.$model"
                type="tel"
                :class="[{'is-invalid' : $v.form.mobilephone.$error } ,'form-control']"
                id="mobilephone"
                placeholder="Enter mobile number..."
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

      <ts-grid
        v-model="form.addresses"
        :columns="columns"
        :has-toolbar="false"
        @inserted="place = {}"
        @modalHide="place = {}"
      >
        <template slot-scope="{ item }">
          <td>{{ item.number }}</td>
          <td>{{ item.street }}</td>
          <td>{{ item.suburb }}</td>
          <td>{{ item.unit }}</td>
          <td>{{ item.state }}</td>
          <td>{{ item.country }}</td>
          <td>{{ item.postcode }}</td>
        </template>
        <template
          slot="grid-modal"
          slot-scope="{ item }"
        >
          <div class="p-2">
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">Find Address</label>
                <div class="col-sm-9">
                  <goole-places
                    :value="place"
                    @input="placeChange($event, item)"
                  />
                </div>
              </div>
            </div>
            <hr>

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">Unit</label>
                <div class="col-sm-10">
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

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">Number</label>
                <div class="col-sm-10">
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

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">Street</label>
                <div class="col-sm-10">
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

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">Suburb</label>
                <div class="col-sm-10">
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

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">State</label>
                <div class="col-sm-10">
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

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">Country</label>
                <div class="col-sm-10">
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

            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-2 col-form-lable">Post Code</label>
                <div class="col-sm-10">
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
    </template>
  </app-main>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { required } from "vuelidate/lib/validators";
import pageMixin from "../../../../mixins/page-mixin";
import GooglePlaces from "../../../Base/GooglePlaces.vue";

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
        telephone: "",
        mobilephone: "",
        addresses: []
      },
      columns: [
        { key: 1, value: "Number" },
        { key: 2, value: "Street" },
        { key: 3, value: "Suburb" },
        { key: 4, value: "Unit" },
        { key: 5, value: "State" },
        { key: 6, value: "Country" },
        { key: 7, value: "Post Code" }
      ]
    };
  },

  validations: {
    form: {
      group: { required },
      firstname: { required },
      lastname: { required },
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

    contact:function(){
      if(!this.id) return null;
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
            query: { eq: `id,${this.id}`, with: "addresses" }
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

      this.create({ resource: "contact", data: this.form })
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

      this.update({ resource: "contact", id: this.id, data: this.form })
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
      this.$router.push({ name: "contactList" });
    }
  }
};
</script>

<style scoped>
</style>