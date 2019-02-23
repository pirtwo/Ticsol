<template>
  <nav-view 
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
        <li>
          <button 
            class="btn btn-light" 
            @click="clearForm"
          >
            New
          </button>
        </li>
        <li>
          <button 
            class="btn btn-light" 
            @click="onSubmit"
          >
            Submit
          </button>
        </li>
        <li>
          <button 
            class="btn btn-light" 
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
      <form>
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Group</label>
            <div class="col-sm-10">
              <select 
                v-model="form.group" 
                class="custom-select" 
                id="group"
              >
                <option value="user">
                  User
                </option>
                <option value="customer">
                  Customer
                </option>
              </select>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">First Name</label>
            <div class="col-sm-10">
              <input 
                v-model="form.firstname" 
                type="text" 
                class="form-control" 
                id="firstname"
              >
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Last Name</label>
            <div class="col-sm-10">
              <input 
                v-model="form.lastname" 
                type="text" 
                class="form-control" 
                id="lastname"
              >
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
              >
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Mobile Number</label>
            <div class="col-sm-10">
              <input 
                v-model="form.mobilephone" 
                type="tel" 
                class="form-control" 
                id="mobilephone"
              >
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Is a User</label>
            <div class="col-sm-10">
              <div class="custom-control custom-radio custom-control-inline">
                <input 
                  type="radio" 
                  id="isUser" 
                  name="isUser" 
                  value="1" 
                  class="custom-control-input"
                  checked
                >
                <label 
                  class="custom-control-label" 
                  for="isUser"
                >Yes</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input 
                  type="radio" 
                  id="notUser" 
                  name="isUser" 
                  value="0" 
                  class="custom-control-input"
                >
                <label 
                  class="custom-control-label" 
                  for="notUser"
                >No</label>
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
                    v-model="item.unit" 
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
  </nav-view>
</template>

<script>
import { mapActions } from "vuex";
import NavView from "../../../framework/NavView.vue";
import pageMixin from '../../../../mixins/page-mixin';
import GooglePlaces from "../../../Base/GooglePlaces.vue";

export default {
  name: "ContactCreate",

  mixins:[pageMixin],

  components: {
    "nav-view": NavView,
    "goole-places": GooglePlaces
  },

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
        { key: 5, value: "Country" },
        { key: 6, value: "Post Code" }
      ]
    };
  },

  methods: {
    ...mapActions({
      create: "resource/create"
    }),

    clearForm(){
      this.form.group= "",
      this.form.firstname= "",
      this.form.lastname= "",
      this.form.telephone= "",
      this.form.mobilephone= "",
      this.form.addresses= []
    },

    placeChange(place, item) {
      this.$set(item, "number", place.street_number);
      this.$set(item, "street", place.route);
      this.$set(item, "country", place.country);
      this.$set(item, "postcode", place.postal_code);      
    },

    onSubmit(e) {
      e.preventDefault();
      e.target.disabled = true;

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

    onCancel(e) {
      e.preventDefault();
      this.$router.push({name:'contactList'});
    }
  }
};
</script>

<style scoped>
</style>