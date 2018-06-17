<template>
    <md-dialog :md-active.sync="showPopup">

        <md-dialog-title>Create Job</md-dialog-title>
        <md-dialog-content>          

            <form v-on:submit.prevent="onSubmit">
                
                <div class="md-layout md-gutter">
                    <div class="md-layout-item md-gutter">
                        <div class="md-layout-item">
                            <md-field>
                                <label>Job Name</label>
                                <md-input v-model="form.name"></md-input>
                            </md-field>
                        </div>
                        <div class="md-layout-item">
                            <md-field>
                                <label>Job Code</label>
                                <md-input v-model="form.code"></md-input>
                            </md-field>   
                        </div>                       
                    </div>
                </div>

                    <div class="md-layout-item">
                          <md-autocomplete v-model="form.parent" :md-options="jobs" md-dense>
                            <label>Parent</label>                    
                        </md-autocomplete>   
                         <md-autocomplete v-model="form.profile" :md-options="profiles" md-dense>
                            <label>Profile</label>                    
                        </md-autocomplete>          
                    </div>   
                    
                    <div class="md-layout-item">
                        <md-radio v-model="form.status" :value="true">Active</md-radio>
                        <md-radio v-model="form.status" :value="false">Inactive</md-radio>            
                    </div>                              
                                   

            </form>

        </md-dialog-content>
        <md-dialog-actions>
            <md-button class="md-primary" @click="onSubmit">Save</md-button>
            <md-button class="md-primary" @click="showPopup = false">Cancel</md-button>
        </md-dialog-actions>
    </md-dialog>
</template>

<script>
export default {
  name: "JobCreatePopup",
  data: () => ({
    form: {
      name: null,
      code: null,
      parent: null,
      status: null,
      profile: null
    },
    jobs: [],
    profiles: [],
    showPopup: true
  }),
  mounted() {
    console.log(this.scheduleItems);
  },
  methods: {
    onSubmit() {
      console.log(this.form.item);
      console.log(this.form.time);
      console.log(this.form.date);
      console.log(this.form.report);
    }
  }
};
</script>

<style scoped>
</style>
