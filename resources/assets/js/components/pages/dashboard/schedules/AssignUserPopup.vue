<template>
  <md-dialog :md-active.sync="showPopup">

    <md-dialog-title>Assign User</md-dialog-title>
    <md-dialog-content>

      <form v-on:submit.prevent="onSubmit">
        <md-field>
          <label>Username</label>
          <md-input v-model="form.userId"></md-input>
        </md-field>

        <div class="md-layout md-gutter">
          <div class="md-layout-item">
            <md-datepicker v-model="form.start" :md-open-on-focus="false">
              <label>Start Date</label>
            </md-datepicker>
          </div>
          <div class="md-layout-item">
            <md-field>
              <md-input v-model="form.startTime" type="time"></md-input>
            </md-field>
          </div>
        </div>

        <div class="md-layout md-gutter">
          <div class="md-layout-item">
            <md-datepicker v-model="form.end" :md-open-on-focus="false">
              <label>End Date</label>
            </md-datepicker>
          </div>
          <div class="md-layout-item">
            <md-field>
              <md-input v-model="form.endTime" type="time"></md-input>
            </md-field>
          </div>
        </div>
      </form>

    </md-dialog-content>
    <md-dialog-actions>
      <md-button class="md-primary" @click="onSubmit">Assign</md-button>
      <md-button class="md-primary" @click="showPopup = false">Cancel</md-button>
    </md-dialog-actions>
  </md-dialog>
</template>

<script>
export default {
  name: "AssignUserPopup",
  props: {
    show: {
      type: Boolean,
      default: false
    },
    event: {
      type: Object,
      default: null
    }
  },

  data() {
    return {
      form: {
        userId: null,
        resourceId: null,
        startTime: null,
        endTime: null,
        start: '',
        end: '',
        offsite: null
      },
      showPopup: false
    };
  },

  watch: {
    show: function(value) {
      if (value == true) this.toggleShow();
    },
    showPopup: function(value) {
      if (value == false) {
        this.$emit("close");
      }
    }
  },

  mounted() {},

  methods: {
    toggleShow() {
      this.showPopup = true;
    },

    onSubmit() {
      this.form.resourceId = this.event.resourceId;
      this.form.start = this.event.start;
      this.form.end = this.event.end;
      this.$emit("submit", this.form);
      this.showPopup = false;
    }
  }
};
</script>

<style scoped>
</style>