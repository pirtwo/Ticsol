<template>
     <nav-view 
        :scrollbar="true" 
        :loading="loading" 
        padding="p-5">

        <template slot="toolbar">           
        </template>

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
                    <button class="btn btn-light">                        
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
                            <select name="leave_type" class="custom-select">
                                <option selected>please select leave type</option>
                                <option value="3">Public holidays</option>
                                <option value="1">Vacation days</option>
                                <option value="3">Voting</option>                                
                                <option value="3">Childbirth</option>
                                <option value="3">Bereavement</option>    
                                <option value="3">Personal leave</option>                            
                                <option value="3">Administrative leave</option>
                                <option value="2">Temporary disability leave</option>
                                <option value="3"></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-label">Display</label>
                        <div class="col-sm-10">

                             <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="display1" name="display" class="custom-control-input" checked>
                                <label class="custom-control-label" for="display1">Days</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="display2" name="display" class="custom-control-input">
                                <label class="custom-control-label" for="display2">Hours</label>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-label">From</label>
                        <div class="col-sm-10">
                            <input name="leave_date" type="date" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-label">Return</label>
                        <div class="col-sm-10">
                            <input name="return_date" type="date" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-label">Days Requested</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-label">Days Remaining</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control">
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
                            ></select-box>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-label">Attachments</label>
                        <div class="col-sm-10">
                            <div class="custom-file">
                                <input name="Attachments" id="customFile" type="file" class="custom-file-input">
                                <label class="custom-file-label" for="customFile">choose files</label>
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
        type: "",
        assigned_id: "",
        attachments: "",
        meta: {
          leave_type: "",
          leave_date: "",
          return_date: ""
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
    })
  }
};
</script>

<style>
</style>
