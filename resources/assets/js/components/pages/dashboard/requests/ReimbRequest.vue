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
        <li><router-link :to="{ name: 'jobList' }">Reimbursement</router-link></li>
        <li><router-link :to="{ name: 'jobList' }">History</router-link></li>
      </ul>

    </template>

    <template slot="content">

      <form>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Details</label>
            <div class="col-sm-10">
              <textarea 
                v-model="form.meta.details"
                name="meta-details" 
                id="meta-details" 
                class="form-control" 
                rows="5"/>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Amount</label>
            <div class="col-sm-10">
              <input 
                v-model="form.meta.amount"
                name="meta-amount" 
                id="meta-amount"
                type="text" 
                class="form-control">
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Incl/Excl</label>
            <div class="col-sm-10">

              <div class="custom-control custom-radio custom-control-inline">
                <input 
                  v-model="form.meta.tax"
                  type="radio" 
                  id="meta-tax1" 
                  name="meta-tax"
                  value="Incl" 
                  class="custom-control-input" 
                  checked>
                <label 
                  class="custom-control-label" 
                  for="meta-tax1">Incl</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input 
                  v-model="form.meta.tax"
                  type="radio" 
                  id="meta-tax2" 
                  name="meta-tax" 
                  value="Excl" 
                  class="custom-control-input">
                <label 
                  class="custom-control-label" 
                  for="meta-tax2">Excl</label>
              </div>

            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Date</label>
            <div class="col-sm-10">
              <input 
                v-model="form.meta.date"
                name="meta-date"
                id="meta-date" 
                type="date" 
                class="form-control">
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-label">Expense To</label>
            <div class="col-sm-10">
              <select-box
                v-model="form.job_id"
                :data="jobs"
                :multi-select="false" 
                id="job_id"
                name="job_id"                                                                                                                                              
                placeholder="please select job"
                search-placeholder="search..."
              />
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
  name: "ReimbRequest",
  components: {
    "nav-view": NavView,
    "select-box": Selectbox
  },

  data() {
    return {
      loading: false,
      form: {
        job_id: "",
        assigned_id: "",
        type: 'reimbursement',
        meta: {
          tax: "Incl",
          date: "",
          details: "",
          amount: ""
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
    },

    jobs: function() {
      return this.getList("job").map(item => {
        return { key: item.id, value: item.title };
      });
    }
  },

  created() {
    this.clear("job");
    this.clear("user");
  },

  mounted() {
    this.loading = true;
    let p1 = this.fetch({ resource: "job" });
    let p2 = this.fetch({ resource: "user" });
    Promise.all([p1, p2])
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
      clear: "resource/clearList",
      create: "resource/create"
    }),

    onSubmit(e) {
      console.log(JSON.stringify(this.form));
      this.create({ resource: "request", data: this.form })
        .then(() => {
          console.log('request created successfuly.')
          this.$router.push({ name: "inbox" });
        })
        .catch(error => {
          console.log(error.response);
          this.$formFeedback(error.response.data.errors);
        });
    }
  }
};
</script>

<style scoped>
</style>
