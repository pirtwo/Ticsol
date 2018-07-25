<template>
    <nav-view :scrollbar="true" :loading="loading" padding="p-5">
        <template slot="toolbar"></template>
        
        <template slot="drawer"></template>
        
        <template slot="content">
            <form>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-lable">Group</label>
                        <div class="col-sm-10">
                            <select v-model="form.group" class="custom-select" id="group">
                                <option value="User">User</option>
                                <option value="Customer">Customer</option>                                
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
                             <label class="col-form-lable">Street</label>
                             <input v-model="address.street" type="tel" class="form-control" id="mobilephone" >
                         </div>
                         <div class="col">
                             <label class="col-form-lable">Suburb</label>
                             <input v-model="address.suburb" type="tel" class="form-control" id="mobilephone" >
                         </div>
                         <div class="col">
                             <label class="col-form-lable">Number</label>
                             <input v-model="address.number" type="number" class="form-control" id="mobilephone" >
                         </div>
                         <div class="col">
                             <label class="col-form-lable">Unit</label>
                             <input v-model="address.unit" type="number" class="form-control" id="mobilephone" >
                         </div>
                         <div class="col">
                             <label class="col-form-lable">Country</label>
                             <input v-model="address.country" type="tel" class="form-control" id="mobilephone" >
                         </div>
                         <div class="col">
                             <label class="col-form-lable">Post Code</label>
                             <input v-model="address.postcode" type="number" class="form-control" id="mobilephone" >
                         </div>
                         <div class="col text-center">
                             <button type="button" class="btn btn-sm btn-success mt-4"><i class="material-icons">add</i></button>
                         </div>

                    </div>
                </div>

            </form>
        </template>
    </nav-view>
</template>

<script>
import NavView from "../../../framework/NavView.vue";

export default {
  name: "ContactCreate",

  components: {
    "nav-view": NavView
  },

  data() {
    return {
      loading: false,
      form: {
        group: "",
        firstname: "",
        lastname: "",
        telephone: "",
        mobilephone: "",
        addreses: []
      },
      address: {
        street: "",
        suburb: "",
        number: "",
        unit: "",
        country: "",
        postcode: ""
      }
    };
  },

  computed: {},

  mounted() {},

  methods: {}
};
</script>

<style scoped>
.btn{
    line-height: 1;
}
</style>
