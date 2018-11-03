<template>
  <nav-view 
    :scrollbar="true" 
    :loading="loading" 
    padding="p-2">
    <template slot="drawer">

      <template slot="toolbar"/>
            
      <ul class="v-menu">
        <li class="menu-title">Actions</li>
        <li><router-link 
          class="btn btn-light" 
          :to="{ name: 'profileCreate' }">New</router-link></li>
        <li>
          <button 
            class="btn btn-light" 
            @click="onSubmit">                        
            Save
          </button>
        </li>
        <li>
          <button 
            class="btn btn-light" 
            @click="onCancel">                        
            Cancel
          </button>
        </li>
        <li class="menu-title">Links</li>
        <li><router-link 
          class="btn btn-link" 
          :to="{ name: 'profileList' }">Profiles</router-link></li>
      </ul>

    </template>
        
    <template slot="content">

      <div class="form-group">
        <div class="form-row">
          <label class="col-sm-2 col-form-lable">Profile Name</label>
          <div class="col-sm-10">
            <input 
              v-model="form.name" 
              id="title" 
              type="text" 
              class="form-control" 
              placeholder="enter name for profile...">
          </div>
        </div>
      </div>

      <form-builder 
        v-model="frmBuilder"
        :disable-fields="['hidden', 'button', 'paragraph', 'header']" 
        :disabled-attrs="['className', 'value']" 
        :disabled-action-buttons="[]"
        :control-order="[]"/>
    </template>
  </nav-view>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import NavView from "../../../framework/NavView.vue";
import FormBuilder from "../../../framework/FormBuilder.vue";
export default {
  name: "JobProfile",
  components: {
    "nav-view": NavView,
    "form-builder": FormBuilder
  },

  props: ["id"],

  data() {
    return {
      loading: false,
      frmBuilder: {},
      form: {
        name: "",
        body: "",
        values: ""
      }
    };
  },

  mounted() {
    this.loading = true;
    this.show({ resource: "form", id: this.id })
      .then(respond => {
        this.form.name = respond.name;
        this.frmBuilder.actions.setData(respond.body);
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

    onSubmit() {
      this.form.body = this.form.values = this.frmBuilder.actions.getData(
        "json",
        false
      );

      this.update({ resource: "form", id: this.id, data: this.form })
        .then(() => {
          console.log("Form updated successfuly.");
          this.$router.push({ name: "profileList" });
        })
        .catch(error => {
          this.$formFeedback(error.response.data.errors);
        });
    },

    onCancel() {
      this.$router.push({ name: "profileList" });
    }
  }
};
</script>

<style scoped>
</style>
