<template>
    <md-dialog :md-active.sync="showPopup">

        <md-dialog-title>Activity Report</md-dialog-title>
        <md-dialog-content>          

            <form class="md-layout" v-on:submit.prevent="onSubmit">

                <md-autocomplete v-model="form.item" :md-options="scheduleItems" md-dense>
                    <label>Schedule Item</label>                    
                </md-autocomplete>

                <div class="md-layout md-gutter">
                    <div class="md-layout-item">
                         <md-field>                    
                            <md-input v-model="form.time" type="time"></md-input>
                            <span class="md-helper-text">Enter Time (exp: 12:30 AM)</span>
                        </md-field>
                    </div>
                    <div class="md-layout-item">
                        <md-datepicker v-model="form.date" :md-open-on-focus="false">
                            <label>Enter Date (exp: YYYY:MM:DD)</label>
                        </md-datepicker>                        
                    </div>                    
                </div>              

                 <md-field>
                    <label>Report</label>
                    <md-textarea v-model="form.report"></md-textarea>
                    <md-icon>description</md-icon>
                </md-field>

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
  name: "ActivityCreatePopup",
  data: () => ({
    form: {
      item: null,
      time: null,
      date: null,
      report: null
    },
    scheduleItems: ["dayone", "daytow", "daythree"],
    showPopup: true
  }),
  mounted() {
      console.log(this.scheduleItems);
  },
  methods: {
      onSubmit(){
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
