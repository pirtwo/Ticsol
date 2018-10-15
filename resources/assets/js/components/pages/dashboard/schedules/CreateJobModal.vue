<template>
    <div class="wrap-panel" v-show="show">    
        <div class="jobModal">
            <div class="panel-title">
                <span>Create Job</span>
                <button type="button" class="close" aria-label="Close" @click="onHide">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="panel-body">
            <form>
                    
                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-label col-form-label-sm">Title</label>
                        <div class="col-sm-10">
                            <input v-model="form.title" name="title" id="title" type="text" class="form-control form-control-sm" placeholder="job title"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-label col-form-label-sm">Code</label>
                        <div class="col-sm-10">
                            <input v-model="form.code" name="code" id="code" type="text" class="form-control form-control-sm" placeholder="display code"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-label col-form-label-sm">Parent</label>
                        <div class="col-sm-10">
                            <select-box
                                v-model="form.parent_id"
                                :size="'sm'"
                                :data="jobs"
                                :multi-select="false"
                                id="parent_id"
                                name="jobParent"                                                                                                                                               
                                placeholder="select parent..."
                                search-placeholder="search..."
                            ></select-box>
                        </div>
                    </div>
                </div>                

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-label col-form-label-sm">Status</label>
                        <div class="col-sm-10">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input v-model="form.isactive" type="radio" id="jobEnable" name="status" value="1" class="custom-control-input" checked>
                                <label class="custom-control-label" for="jobEnable">Enable</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input v-model="form.isactive" type="radio" id="jobDisable" name="status" value="0" class="custom-control-input">
                                <label class="custom-control-label" for="jobDisable">Disable</label>
                            </div>     
                        </div>                   
                    </div>
                </div>

            </form>
            </div>
            <div class="panel-footer">
                <button class="btn btn-sm btn-success" @click="onSubmit">                        
                    Save
                </button>
                <button class="btn btn-sm btn-success" @click="onHide">                        
                    Cancel
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import Selectbox from "../../../framework/BaseSelectBox.vue";

export default {
  name: "CreateJobModal",

  components: {
    "select-box": Selectbox
  },

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
        parent_id: null,
        form_id: null,
        meta: null
      },
      show: false,
      loading: false
    };
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

  mounted() {
    //
  },

  methods: {
    ...mapActions({
      fetch: "resource/list",
      create: "resource/create"
    }),

    onShow() {},

    onHide() {
      this.show = false;
      this.$emit("input", this.show);
    },

    onSubmit(e) {
      e.target.disabled = true;
      e.target.innerHTML = "Saving...";
      this.form.meta = JSON.stringify(this.formData);
      this.create({ resource: "job", data: this.form })
        .then(respond => {
          e.target.disabled = false;
          e.target.innerHTML = "Save";
          console.log("job created successfuly");
          this.onHide();
        })
        .catch(error => {
          e.target.disabled = false;
          e.target.innerHTML = "Save";
          console.log(error.response.data);
          this.$formFeedback(error.response.data.errors);
        });

      e.preventDefault();
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
