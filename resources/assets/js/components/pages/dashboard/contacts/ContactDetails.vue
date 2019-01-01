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
          <router-link 
            class="btn btn-light" 
            :to="{ name: 'contactCreate' }">New</router-link>
        </li>
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
            :to="{ name: 'contactList' }">Contacts</router-link>
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
                id="group">
                <option value="user">User</option>
                <option value="customer">Customer</option>
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
                id="firstname">
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
                id="lastname">
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
                id="telephone">
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
                id="mobilephone">
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
                  for="isUser">Yes</label>
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
                  for="notUser">No</label>
              </div>
            </div>
          </div>
        </div>
      </form>

      <div class="table-responsive">
        <ts-grid
          v-model="form.addresses"
          :columns="columns"
          :has-toolbar="false"
          @insert="addAddress"
          @edit="editAddress"
          @select="(selects)=>{ this.selects = selects }"
        >
          <template slot-scope="{ item }">
            <td>{{ item.number }}</td>
            <td>{{ item.street }}</td>
            <td>{{ item.suburb }}</td>
            <td>{{ item.unit }}</td>
            <td>{{ item.country }}</td>
            <td>{{ item.postcode }}</td>
          </template>
        </ts-grid>
      </div>
    </template>
  </nav-view>
</template>

<script>
import { mapActions } from "vuex";
import NavView from "../../../framework/NavView.vue";

export default {
  name: "ContactCreate",

  components: {
    "nav-view": NavView
  },

  props: ["id"],

  data() {
    return {
      loading: false,
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

  watch: {
    selects: function(val) {
      console.log(val);
    }
  },

  mounted() {
    this.loading = true;
    this.show({
      id: this.id,
      resource: "contact",
      query: { with: "addresses" }
    })
      .then(respond => {
        this.form = Object.assign({}, this.form, respond);
        this.loading = false;
      })
      .catch(error => {
        console.log(error);
        this.loading = false;
      });
  },

  methods: {
    ...mapActions({
      show: "resource/show",
      update: "resource/update"
    }),

    addAddress() {
      this.form.addresses.push({
        number: "",
        street: "",
        suburb: "",
        unit: "",
        country: "",
        postcode: ""
      });
    },

    onSubmit() {
      this.update({ resource: "contact", id: this.id, data: this.form })
        .then(() => {
          console.log("Contact updated successfuly.");
          this.$router.push({ name: "contactList" });
        })
        .catch(error => {
          console.log(error.response);
          this.$formFeedback(error.response.data.errors);
        });
    },

    onCancel() {
      this.$router.push({ name: "contactList" });
    }
  }
};
</script>

<style scoped>
.btn {
  line-height: 1;
}
</style>
