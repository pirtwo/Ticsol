<template>
  <nav-view 
    :scrollbar="true" 
    :loading="isLoading" 
    padding="p-2">
    <template slot="drawer">

      <template slot="toolbar"/>
            
      <ul class="v-menu">
        <li class="menu-title">Actions</li>
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
import pageMixin from '../../../../mixins/page-mixin';

export default {
  name: "ProfileCreate",

  mixins: [pageMixin],

  components: {
    "nav-view": NavView,
    "form-builder": FormBuilder
  },

  data() {
    return {
      frmBuilder: {},
      form: {
        name: "",
        schema: ""
      }
    };
  },

  methods: {
    ...mapActions({
      create: "resource/create"
    }),

    onSubmit(e) {
      e.preventDefault();      
      e.target.disabled = true;
      this.form.schema = this.frmBuilder.actions.getData();
      this.create({ resource: "form", data: this.form })
        .then(() => {
          e.target.disabled = false;
          this.showMessage(
            `Profile <b>${this.form.name}</b> created successfuly.`,
            "success"
          );
        })
        .catch(error => {
          e.target.disabled = false;
          this.showMessage(error.message, "danger");  
          this.$formFeedback(error.response.data.errors);
        });
    },

    onCancel(e) {
      e.preventDefault();   
      this.$router.push({ name: "profileList" });
    }
  }
};
</script>

<style scoped>
</style>
