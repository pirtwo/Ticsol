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
                    <button class="btn btn-light" @click="onSubmit">
                        <i class="material-icons">save_alt</i>
                        Save
                    </button>
                </li>
                <li>
                    <button class="btn btn-light" @click="onCancel">
                        <i class="material-icons">cancel</i>
                        Cancel
                    </button>
                </li>
                <li class="menu-title">Links</li>
            </ul>

        </template>

        <template slot="content">

            <form class="needs-validation" novalidate>
                
                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-lable">Title</label>
                        <div class="col-sm-10">
                            <input v-model="form.title" id="title" type="text" class="form-control" placeholder="job title"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-lable">Code</label>
                        <div class="col-sm-10">
                            <input v-model="form.code" id="code" type="text" class="form-control" placeholder="display code"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-lable">Parent</label>
                        <div class="col-sm-10">
                            <auto-complete
                                v-model="form.parent_id"
                                :data="jobs"
                                id="parent_id"
                                name="jobParent"
                                place-holder="click to select parent..."
                            ></auto-complete>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-lable">Profile</label>
                        <div class="col-sm-10">
                            <auto-complete
                                v-model="form.form_id"
                                :data="profileList"
                                id="form_id"
                                name="jobProfile"
                                place-holder="click to select profile..."
                            ></auto-complete>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-12 col-form-lable">Status</label>

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

            </form>

        </template>
    </nav-view>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import NavView from "../../../framework/NavView.vue";
import AutoComplete from "../../../framework/BaseAutoComplete.vue";

export default {
  name: "JobCreate",

  components: {
    "nav-view": NavView,
    "auto-complete": AutoComplete
  },

  data() {
    return {
      form: {
        title: "",
        code: "",
        isactive: 1,
        parent_id: null,
        form_id: null
      },
      jobList: [],
      profileList: [],
      loading: false
    };
  },

  computed: {
    ...mapGetters({
      getList: "resource/getResource"
    }),

    jobs: function() {
      return this.getList("job").map(item => {
        return { key: item.id, value: item.title };
      });
    },

    profiles: function() {}
  },

  mounted() {
    this.loading = true;
    this.fetch({ resource: "job" })
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

    onSubmit(e) {
      e.target.disabled = true;
      console.log(this.form.title);
      console.log(this.form.code);
      console.log(this.form.isactive);
      console.log(this.form.parent_id);
      console.log(this.form.form_id);

      this.create({ resource: "job", data: this.form })
        .then(respond => {
          e.target.disabled = false;
          console.log("job created successfuly");
        })
        .catch(error => {
          e.target.disabled = false;
          this.$formFeedback(error.response.data.errors);
        });

      e.preventDefault();
    },

    onCancel(e) {
      this.$router.go(-1);
      e.preventDefault();
    }
  }
};
</script>

<style scoped>
</style>