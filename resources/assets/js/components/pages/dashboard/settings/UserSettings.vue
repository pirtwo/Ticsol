<template>
  <app-main
    :scrollbar="false"
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
        <!-- theme -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Theme</label>
            <div class="col-sm-10">
              <ts-select
                v-model="form.theme"
                :data="themes"
                id="parent_id"
                class="form-control"
                name="jobParent"
                placeholder="select app theme"
                search-placeholder="search..."
              />
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
                  value="Week"
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
                  value="Month"
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

        <!-- ical -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">ICal</label>
            <div class="col-sm-10">
              <div class="custom-control custom-radio custom-control-inline">
                <input
                  v-model="form.ical"
                  type="radio"
                  id="icalEnable"
                  name="ical"
                  :value="true"
                  class="custom-control-input"
                >
                <label
                  class="custom-control-label"
                  for="icalEnable"
                >Enable</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input
                  v-model="form.ical"
                  type="radio"
                  id="icalDisable"
                  name="ical"
                  :value="false"
                  class="custom-control-input"
                >
                <label
                  class="custom-control-label"
                  for="icalDisable"
                >Disable</label>
              </div>
            </div>
          </div>
        </div>

        <!-- ical url -->
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">ICal URL</label>
            <div class="col-sm-10">
              <div class="input-group mb-3">
                <input
                  v-model="form.icalUrl"
                  type="text"
                  class="form-control"
                  placeholder="Enable ICal to recive the URL"
                  aria-label="ical url"
                  aria-describedby="button-addon2"
                >
                <div class="input-group-append">
                  <button
                    class="btn btn-secondary"
                    type="button"
                    id="button-addon2"
                  >
                    <ts-icon
                      icon="file_copy"
                      style="font-size:inherit;"
                    />
                  </button>
                </div>
              </div>
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
  name: "UserSettings",

  mixins: [pageMixin],

  data() {
    return {
      themes: [
        { key: 0, value: "Default" },
        { key: 1, value: "Urban" },
        { key: 2, value: "Jungle" },
        { key: 3, value: "Beach" },
        { key: 4, value: "Night" }
      ],
      form: {
        theme: {},
        ical: false,
        icalUrl: "",
        scheduleView: "user",
        scheduleRange: "Month"
      }
    };
  },

  computed: {
    ...mapGetters({
      userId: "user/getId",
    })
  },

  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.loadAssets();
    });
  },

  beforeRouteLeave(to, from, next) {
    this.clear("user");
    next();
  },

  methods: {
    ...mapActions({
      setTheme: "core/theme",
      list: "resource/list",
      update: "resource/update",      
      clear: "resource/clearResource",
      updateSettings: "user/updateSettings",
    }),

    loadAssets() {
      this.startLoading();
      this.list({ resource: "user", query: { eq: `id,${this.userId}` } }).then(
        data => {
          this.populateForm(data[0].meta);
          this.stopLoading();
        }
      );
    },

    populateForm(settings) {   
      if(!settings) return;
         
      this.form.theme = this.themes.find(
        item => item.value.toLowerCase() === settings.theme
      );

      if(this.form.theme)
        this.setTheme(this.form.theme.value);

      this.form.scheduleView = settings.schedule_view ? settings.schedule_view : "";
      this.form.scheduleRange = settings.schedule_range ? settings.schedule_range : "";
      this.form.ical = settings.ical ? true : false;
      this.form.icalUrl = settings.ical
        ? `https://${window.location.hostname}/api/ical/${this.userId}/${settings.ical}`
        : "";
    },

    onSave(e) {
      e.preventDefault();
      e.target.disable = true;

      // setup the req body
      let form = {};
      form.theme = this.form.theme.value.toLowerCase();
      form.ical = this.form.ical;
      form.schedule_view = this.form.scheduleView;
      form.schedule_range = this.form.scheduleRange;

      this.update({ resource: "user", id: this.userId, data: form })
        .then(data => {
          this.populateForm(data.meta);
          this.updateSettings(data.meta);
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
