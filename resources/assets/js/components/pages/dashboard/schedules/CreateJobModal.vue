<template>
  <div 
    class="wrap-panel" 
    v-show="show"
  >    
    <div class="jobModal">
      <div class="panel-title">
        <span>Create Job</span>
        <button 
          type="button" 
          class="close" 
          aria-label="Close" 
          @click="onHide"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="panel-body">
        <!-- Create Job Form -->
        <form>
          <!-- Title -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-sm-2 col-form-label col-form-label-sm">Title</label>
              <div class="col-sm-10">
                <input 
                  v-model="form.title" 
                  name="title" 
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
              <label class="col-sm-2 col-form-label col-form-label-sm">Code</label>
              <div class="col-sm-10">
                <input 
                  v-model="form.code" 
                  name="code" 
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
              <label class="col-sm-2 col-form-label col-form-label-sm">Parent</label>
              <div class="col-sm-10">
                <ts-select
                  v-model="form.parent"
                  :size="'sm'"
                  :data="jobs"
                  :multi="false"
                  id="parent_id"
                  name="jobParent"                                                                                                                                               
                  placeholder="select parent..."
                  search-placeholder="search..."
                />
              </div>
            </div>
          </div>                

          <!-- IsActive --> 
          <div class="form-group">
            <div class="form-row">
              <label class="col-sm-2 col-form-label col-form-label-sm">Status</label>
              <div class="col-sm-10">
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
          </div>
        </form>
      </div>
      <div class="panel-footer">
        <button 
          class="btn btn-sm btn-success" 
          @click="onSubmit"
        >                        
          Save
        </button>
        <button 
          class="btn btn-sm btn-success" 
          @click="onHide"
        >                        
          Cancel
        </button>
      </div>
    </div>
  </div>
</template>

<script>

import { required } from "vuelidate/lib/validators";
import { mapActions, mapGetters } from "vuex";

export default {
  name: "CreateJobModal",

  props: {
    jobName: {
      type: String,
      default: ""
    },
    value: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      form: {
        title: "",
        code: "",
        isactive: 1,
        parent: {},
        form_id: null,
        meta: null
      },
      show: false,
      loading: false
    };
  },

  validations:{
    form:{
      title: { required },
      code: { required }
    }
  },

  watch: {
    value: function(value) {
      this.show = value;
    }
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    jobs: function() {
      return this.getList("job").map(item => {
        return { key: item.id, value: item.title };
      });
    }
  },

  methods: {
    ...mapActions({
      fetch: "resource/list",
      create: "resource/create"
    }),

    onShow() {},

    onHide() {
      this.clearForm();
      this.show = false;
      this.$emit("input", this.show);
    },

    onSubmit(e) {
      e.preventDefault();
      e.target.disabled = true;      

      // Validate
      this.$v.$touch();
      if(this.$v.$invalid){
        e.target.disabled = false;
        return;
      }

      // Populate form data
      let form = {};
      form.title = this.form.title;
      form.code = this.form.code;
      form.parent_id = this.form.parent.key;
      form.form_id = this.form.form_id;
      form.isactive = this.form.isactive;
      form.meta = this.form.meta;
      
      e.target.innerHTML = "Saving...";
      this.create({ resource: "job", data: form })
        .then(respond => {          
          console.log("job created successfuly");
          this.onHide();
        })
        .catch(error => {
          console.log(error.response.data);
          this.$formFeedback(error.response.data.errors);
        })
        .finally(()=>{
          e.target.disabled = false;
          e.target.innerHTML = "Save";
        });
    },

    clearForm(){
      this.form.title = "";
      this.form.code = "";
      this.form.parent = null;
      this.form.isactive = 1;
      this.$v.form.$reset();
    }
  }
};
</script>

<style scoped>
.wrap-panel {
  top: 0px;
  left: 0px;
  width: 100%;
  height: 100%;
  z-index: 1050;
  position: absolute;
  background-color: rgba(0, 0, 0, 0.45);
}

.jobModal {
  top: 0;
  left: 0;
  width: 320px;
  padding: 20px;
  z-index: 1050;
  border-radius: 5px;
  position: absolute;
  font-size: 0.875rem;
  background-color: white;
  transform: translate(27%, 40%);
}

.jobModal .panel-title,
.jobModal .panel-body,
.jobModal .panel-footer {
  display: block;
  width: 100%;
}

.jobModal .panel-title {
  min-height: 50px;
}

.jobModal .panel-footer {
  text-align: right;
}
</style>
