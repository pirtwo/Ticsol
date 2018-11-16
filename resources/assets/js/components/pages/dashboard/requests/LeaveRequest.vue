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
          <button class="btn btn-light">                        
            New
          </button>
        </li>
        <li>
          <button class="btn btn-light">                        
            Suspend
          </button>
        </li>
        <li>
          <button 
            class="btn btn-light" 
            @click="onSubmit">                        
            Submit
          </button>
        </li>
        <li>
          <button class="btn btn-light">                        
            Cancel
          </button>
        </li>
        <li>
          <button class="btn btn-light">                        
            Print
          </button>
        </li>
        <li class="menu-title">Links</li>
        <li><router-link :to="{ name: 'jobList' }">Anuual Leave</router-link></li>
        <li><router-link :to="{ name: 'jobList' }">Sick Leave</router-link></li>                
        <li><router-link :to="{ name: 'jobList' }">Request History</router-link></li>
      </ul>

    </template>

    <template slot="content">

      <form>
                               
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Leave Type</label>
            <div class="col-sm-10">
              <select 
                v-model="form.meta.leave_type" 
                name="meta-leave_type" 
                class="custom-select">
                <option selected>please select leave type</option>
                <option value="annual">Annual</option>
                <option value="long service">Long Service</option>
                <option value="sick">Sick</option>                                
                <option value="bereavement">Bereavement</option>
                <option value="maternity/paternity">Maternity/Paternity</option>    
                <option value="study">Study</option>                            
                <option value="other">Other</option>                                
              </select>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Display</label>
            <div class="col-sm-10">

              <div class="custom-control custom-radio custom-control-inline">
                <input 
                  type="radio" 
                  id="display1" 
                  name="display" 
                  class="custom-control-input" 
                  checked>
                <label 
                  class="custom-control-label" 
                  for="display1">Days</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input 
                  type="radio" 
                  id="display2" 
                  name="display" 
                  class="custom-control-input">
                <label 
                  class="custom-control-label" 
                  for="display2">Hours</label>
              </div>

            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">From</label>
            <div class="col-sm-10">
              <input 
                v-model="form.meta.from" 
                type="date" 
                name="meta-from" 
                class="form-control">
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Return</label>
            <div class="col-sm-10">
              <input 
                v-model="form.meta.till" 
                type="date" 
                name="meta-till" 
                class="form-control">
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Days Requested</label>
            <div class="col-sm-10">
              <input 
                type="text" 
                class="form-control">
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Days Remaining</label>
            <div class="col-sm-10">
              <input 
                type="text" 
                class="form-control">
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Approver</label>
            <div class="col-sm-10">
              <select-box
                v-model="form.assigned_id"
                :data="users"
                :multi-select="false" 
                id="assigned_id"
                name="assigned_id"                                                                                                                                              
                placeholder="please selet approver"
                search-placeholder="search..."
              />
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Attachments</label>
            <div class="col-sm-10">
              <div class="custom-file">
                <input 
                  name="Attachments" 
                  id="customFile" 
                  type="file" 
                  class="custom-file-input">
                <label 
                  class="custom-file-label" 
                  for="customFile">choose files</label>
              </div>
            </div>
          </div>
        </div>            

      </form>
          
    </template>
  </nav-view>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import NavView from "../../../framework/NavView.vue";
import Selectbox from "../../../framework/BaseSelectBox.vue";

export default {
  name: "LeaveRequest",
  components: {
    "nav-view": NavView,
    "select-box": Selectbox
  },
  data() {
    return {
      loading: false,
      form: {
        type: "leave",
        assigned_id: "",
        attachments: "",
        meta: {
          leave_type: "",
          from: "",
          till: ""
        }
      }
    };
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    users: function() {
      return this.getList("user").map(item => {
        return { key: item.id, value: item.name };
      });
    }
  },

  mounted() {
    this.loading = true;
    this.fetch({ resource: "user" })
      .then(() => {
        this.loading = false;
      })
      .catch(error => {
        console.log(error);        
      });
  },

  methods: {
    ...mapActions({
      fetch: "resource/list",
      create: "resource/create"
    }),

    onSubmit(event) {
      this.create({ resource: "request", data: this.form })
        .then(respond => {
            console.log('Request created sucessfuly.');
        })
        .catch(error => {
            console.log(error.response);
            this.$formFeedback(error.response.data.errors);
        });
    }
  }
};
</script>

<style>
</style>
