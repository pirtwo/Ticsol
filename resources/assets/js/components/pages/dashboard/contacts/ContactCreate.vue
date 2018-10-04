<template>
    <nav-view :scrollbar="true" :loading="loading" padding="p-5">
        <template slot="toolbar"></template>
        
        <template slot="drawer">
             <ul class="v-menu">
                <li class="menu-title">Actions</li>
                <li>
                    <button class="btn btn-light" @click="onSubmit">                        
                        Save
                    </button>
                </li>
                <li>
                    <button class="btn btn-light" @click="onCancel">                        
                        Cancel
                    </button>
                </li>
                <li class="menu-title">Links</li>
                <li><router-link :to="{ name: 'contactList' }">Contacts</router-link></li>
            </ul>
        </template>
        
        <template slot="content">
            
            <form>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-lable">Group</label>
                        <div class="col-sm-10">
                            <select v-model="form.group" class="custom-select" id="group">
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
                             <input v-model="form.firstname" type="text" class="form-control" id="firstname" >
                         </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                         <label class="col-sm-2 col-form-lable">Last Name</label>
                         <div class="col-sm-10">
                             <input v-model="form.lastname" type="text" class="form-control" id="lastname" >
                         </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                         <label class="col-sm-2 col-form-lable">Tel Number</label>
                         <div class="col-sm-10">
                             <input v-model="form.telephone" type="tel" class="form-control" id="telephone" >
                         </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                         <label class="col-sm-2 col-form-lable">Mobile Number</label>
                         <div class="col-sm-10">
                             <input v-model="form.mobilephone" type="tel" class="form-control" id="mobilephone" >
                         </div>
                    </div>
                </div>

                 <div class="form-group">
                    <div class="form-row">
                         <label class="col-sm-2 col-form-lable">Is a User</label>
                         <div class="col-sm-10">

                             <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="isUser" name="isUser" value="1" class="custom-control-input" checked>
                                <label class="custom-control-label" for="isUser">Yes</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="notUser" name="isUser" value="0" class="custom-control-input">
                                <label class="custom-control-label" for="notUser">No</label>
                            </div>   

                         </div>
                    </div>
                </div>

                 <div class="form-group">
                    <div class="form-row">
                         
                         <div class="col">
                             <label class="col-form-lable">Number</label>
                             <input v-model="address.number" type="text" class="form-control" id="mobilephone" >
                         </div>
                         <div class="col">
                             <label class="col-form-lable">Street</label>
                             <input v-model="address.street" type="text" class="form-control" id="mobilephone" >
                         </div>
                         <div class="col">
                             <label class="col-form-lable">Suburb</label>
                             <input v-model="address.suburb" type="text" class="form-control" id="mobilephone" >
                         </div>                         
                         <div class="col">
                             <label class="col-form-lable">Unit</label>
                             <input v-model="address.unit" type="text" class="form-control" id="mobilephone" >
                         </div>
                         <div class="col">
                             <label class="col-form-lable">Country</label>
                             <input v-model="address.country" type="text" class="form-control" id="mobilephone" >
                         </div>
                         <div class="col">
                             <label class="col-form-lable">Post Code</label>
                             <input v-model="address.postcode" type="text" class="form-control" id="mobilephone" >
                         </div>
                         <div class="col text-center">
                            <button type="button" class="btn btn-sm btn-light mt-4"
                                @click="onAddressAdd"
                                v-show="mode == 'add'">
                                <i class="material-icons">add</i>
                            </button>
                            <button type="button" class="btn btn-sm btn-light mt-4"
                                @click="onAddressSave"
                                v-show="mode == 'edit'">
                                <i class="material-icons">save</i>
                            </button>  
                            <button type="button" class="btn btn-sm btn-light mt-4"
                                @click="onAddressCancel"
                                v-show="mode == 'edit'">
                                <i class="material-icons">clear</i>
                            </button>                                                       
                         </div>
                    </div>
                </div>
                
            </form>    

            <div class="table-responsive"  v-show="form.addresses.length != 0">  
                <table class="table table-sm table-hover table-light">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Number</th>
                        <th scope="col">Street</th>
                        <th scope="col">Subrb</th>
                        <th scope="col">Unit</th>
                        <th scope="col">Country</th>
                        <th scope="col">Post Code</th>
                        <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in form.addresses" :key="index">
                            <th scope="row">{{ index + 1 }}</th>                        
                            <td >
                                <div :id="'addresses-' + index + '-number'">{{ item.number }}</div>                            
                            </td>
                            <td>
                                <div :id="'addresses-' + index + '-street'">{{ item.street }}</div>
                            </td>
                            <td>
                                <div :id="'addresses-' + index + '-suburb'">{{ item.suburb }}</div>
                            </td>
                            <td>
                                <div :id="'addresses-' + index + '-unit'">{{ item.unit }}</div>
                            </td>
                            <td>
                                <div :id="'addresses-' + index + '-country'">{{ item.country }}</div>
                            </td>
                            <td>
                                <div :id="'addresses-' + index + '-postcode'">{{ item.postcode }}</div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-light" @click="onAddressEdit(item, index)">
                                    <i class="material-icons">edit</i>
                                </button>
                                <button type="button" class="btn btn-sm btn-light" @click="onAddressDelete(item, index)">
                                    <i class="material-icons">remove</i>
                                </button>   
                            </td>
                        </tr>
                    </tbody>
                </table>
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

  data() {
    return {
      loading: false,
      mode: "add",
      index: 0,
      form: {
        group: "",
        firstname: "",
        lastname: "",
        telephone: "",
        mobilephone: "",
        addresses: []
      },
      address: {
        number: "",
        street: "",
        suburb: "",
        unit: "",
        country: "",
        postcode: ""
      }
    };
  },

  computed: {},

  mounted() {},

  methods: {
    ...mapActions({
      create: "resource/create"
    }),

    onAddressAdd() {
      this.form.addresses.push(Object.assign({}, this.address));
      this.clearAddress();
    },

    onAddressEdit(item, index) {
      this.clearAddress();
      this.mode = "edit";
      this.index = index;
      Object.assign(this.address, this.form.addresses[index]);
    },

    onAddressDelete(item, index) {
      this.clearAddress();
      this.mode = "add";
      this.form.addresses.splice(index, 1);
    },

    onAddressCancel() {
      this.mode = "add";
      this.clearAddress();
    },

    onAddressSave() {
      Object.assign(this.form.addresses[this.index], this.address);
    },

    clearAddress() {
      this.address = {};
    },

    onSubmit() {
      this.create({ resource: "contact", data: this.form })
        .then(() => {
          console.log("Contact created successfuly.");
          this.$formFeedback([]);
        })
        .catch(error => {
          console.log(error.response);
          this.$formFeedback(error.response.data.errors);
        });
    },

    onCancel() {
      this.$router.go(-1);
    }
  }
};
</script>

<style scoped>
.btn {
  line-height: 1;
}
</style>
