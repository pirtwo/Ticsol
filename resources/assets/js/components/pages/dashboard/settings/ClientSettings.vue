<template>
  <app-main
    :scrollbar="true"
    :loading="isLoading"
    padding="p-5"
  >
    <template slot="drawer">
      <ul class="v-menu">
        <li class="menu-title">
          Actions
        </li>

        <li>
          <button
            type="button"
            class="btn"
            @click="onSave"
          >
            Save
          </button>
        </li>

        <li>
          <button
            type="button"
            class="btn"
            @click="onCancel"
          >
            Cancel
          </button>
        </li>

        <li class="menu-title">
          Links
        </li>
      </ul>
    </template>
    <template slot="content">
      <!-- user settings -->
      <form>
        <!-- business hours -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Business Hours</label>
            <div class="col-sm-10">
              <div
                v-for="(item, index) in form.businessHours"
                :key="item.day"
                class="form-row mt-1"
              >
                <!-- day label -->
                <div class="col-md-2">
                  {{ getDayName(item.day) }}
                </div>

                <!-- isopen -->
                <div class="col-md-2">
                  <div class="custom-control custom-checkbox">
                    <input
                      v-model="item.isopen"
                      type="checkbox"
                      :id="`isopen-${index}`"
                      class="custom-control-input"
                    >
                    <label
                      :for="`isopen-${index}`"
                      class="custom-control-label"
                    >Open</label>
                  </div>
                </div>

                <!-- start -->
                <div class="col mr-md-2">
                  <div class="form-row">
                    <label class="col-sm-2">From</label>
                    <input
                      v-model="item.start"
                      type="time"
                      class="form-control col"
                      :disabled="!item.isopen"
                    >
                  </div>                  
                </div>

                <!-- end -->
                <div class="col ml-md-2">
                  <div class="form-row">
                    <label class="col-sm-2">Till</label>
                    <input
                      v-model="item.end"
                      type="time"
                      class="form-control col"
                      :disabled="!item.isopen"
                    >
                  </div>                  
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- schedule view -->
        <div class="form-group mt-4">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Schedule View</label>
            <div class="col-sm-10">
              <div class="custom-control custom-radio custom-control-inline">
                <input
                  v-model="form.scheduleView"
                  type="radio"
                  id="view1"
                  name="views"
                  value="user"
                  class="custom-control-input"
                >
                <label
                  class="custom-control-label"
                  for="view1"
                >Employee</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input
                  v-model="form.scheduleView"
                  type="radio"
                  id="view2"
                  name="views"
                  value="job"
                  class="custom-control-input"
                >
                <label
                  class="custom-control-label"
                  for="view2"
                >Jobs</label>
              </div>
            </div>
          </div>
        </div>

        <!-- schedule range -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Schedule Range</label>
            <div class="col-sm-10">
              <div class="custom-control custom-radio custom-control-inline">
                <input
                  v-model="form.scheduleRange"
                  type="radio"
                  id="range1"
                  name="ranges"
                  value="week"
                  class="custom-control-input"
                >
                <label
                  class="custom-control-label"
                  for="range1"
                >Week</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input
                  v-model="form.scheduleRange"
                  type="radio"
                  id="range2"
                  name="ranges"
                  value="month"
                  class="custom-control-input"
                >
                <label
                  class="custom-control-label"
                  for="range2"
                >Month</label>
              </div>
            </div>
          </div>
        </div>

        <!-- hour per day -->
        <div class="form-group mt-4">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Hour Per Day</label>
            <div class="col-sm-10">
              <input
                v-model="form.hourPerDay"
                type="text"
                class="form-control"
              >
            </div>
          </div>
        </div>
      </form>
    </template>
  </app-main>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import pageMixin from "../../../../mixins/page-mixin";

export default {
  name: "ClientSettings",

  mixins: [pageMixin],

  data() {
    return {
      form: {
        businessHours: [
          { day: 1, start: "00:00", end: "00:00", isopen: false },
          { day: 2, start: "00:00", end: "00:00", isopen: false },
          { day: 3, start: "00:00", end: "00:00", isopen: false },
          { day: 4, start: "00:00", end: "00:00", isopen: false },
          { day: 5, start: "00:00", end: "00:00", isopen: false },
          { day: 6, start: "00:00", end: "00:00", isopen: false },
          { day: 0, start: "00:00", end: "00:00", isopen: false }
        ],
        scheduleView: "",
        scheduleRange: "",
        hourPerDay: 0
      }
    };
  },

  computed: {
    ...mapGetters({
      clientId: "user/getClientId"
    })
  },

  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.loadAssets();
    });
  },

  beforeRouteLeave(to, from, next) {
    this.clear("client");
    next();
  },

  methods: {
    ...mapActions({
      show: "resource/show",
      update: "resource/update",
      clear: "resource/clearResource"
    }),

    loadAssets() {
      this.startLoading();
      this.show({ resource: "client", id: this.clientId }).then(
        data => {
          this.populateForm(data.meta);
          this.stopLoading();
        }
      );
    },

    populateForm(settings) {
      if(settings.business_hours){
        for (let i = 0; i < settings.business_hours.length; i++) {
          this.form.businessHours[i].isopen = settings.business_hours[i].isopen; 
          this.form.businessHours[i].start = settings.business_hours[i].start; 
          this.form.businessHours[i].end = settings.business_hours[i].end;    
        }
      }

      this.form.scheduleView = settings.schedule_view;
      this.form.scheduleRange = settings.schedule_range;
      this.form.hourPerDay = settings.hour_per_day;
    },

    getDayName(dayNum) {
      switch (dayNum) {
        case 0:
          return "Sunday";
        case 1:
          return "Monday";
        case 2:
          return "Tuesday";
        case 3:
          return "Wednesday";
        case 4:
          return "Thursday";
        case 5:
          return "Friday";
        case 6:
          return "Saturday";
        default:
          return "";
      }
    },

    onSave(e) {
      e.preventDefault();
      e.target.disable = true;

      // setup the req body
      let form = {};     
      form.business_hours = this.form.businessHours; 
      form.schedule_view = this.form.scheduleView;
      form.schedule_range = this.form.scheduleRange;
      form.hour_per_day = this.form.hourPerDay;

      this.update({ resource: "client", id: this.clientId, data: form })
        .then(data => {
          this.populateForm(data.meta);
          this.showMessage(`Settings updated successfuly.`, "success");
        })
        .catch(error => {
          this.showMessage(error.message, "danger");
        })
        .finally(() => {
          e.target.disable = false;
        });
    },

    onCancel(e) {
      e.preventDefault();
      this.$router.go(-1);
    }
  }
};
</script>

<style>
</style>
