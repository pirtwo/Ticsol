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
                        Save
                    </button>
                </li>
                <li>
                    <button class="btn btn-light" @click="onCancel">                        
                        Cancel
                    </button>
                </li>
                <li class="menu-title">Links</li>
                <li><router-link :to="{ name: 'activityList' }">Activities</router-link></li>
            </ul>

        </template>

        <template slot="content">
            
            <form>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-lable">Schedule Item</label>
                        <div class="col-sm-10">
                            <select-box
                                v-model="schedule_id"
                                :data="scheduleItems"
                                :multi-select="false" 
                                id="schedule_id"
                                name="schedule_id"                                                                                                                                              
                                placeholder="select schedule item..."
                                search-placeholder="search..."
                            ></select-box>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-lable">From</label>
                        <div class="col">
                            <input id="from" v-model="form.fromDate" type="date" class="form-control" />
                        </div>
                        <div class="col">
                            <input v-model="form.fromTime" type="time" class="form-control" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-lable">Till</label>                        
                        <div class="col">
                            <input id="till" v-model="form.tillDate" type="date" class="form-control" />
                        </div>
                        <div class="col">
                            <input v-model="form.tillTime" type="time" class="form-control" />
                        </div>                        
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-lable">Report</label>                        
                        <div class="col-sm-10">
                            <textarea id="desc" v-model="form.desc" class="form-control" placeholder="write your report..." rows="7"></textarea>
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
import Selectbox from "../../../framework/BaseSelectBox.vue";

export default {
  name: "ActivityCreate",

  components: {
    "nav-view": NavView,
    "select-box": Selectbox
  },

  data() {
    return {
      schedule_id: null,
      form: {
        schedule_id: null,
        fromDate: "",
        fromTime: "",
        tillDate: "",
        tillTime: "",
        desc: null
      },
      loading: false
    };
  },

  watch: {
    schedule_id: function(value) {
      this.form.schedule_id = value;
      if (value !== null && value !== undefined) {
        let index = this.list.findIndex(item => item.id === value);
        this.form.fromDate = this.list[index].start.slice(0, 10);
        this.form.fromTime = this.list[index].start.slice(11, 16);
        this.form.tillDate = this.list[index].end.slice(0, 10);
        this.form.tillTime = this.list[index].end.slice(11, 16);
      }
    }
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    list: function() {
      return this.getList("schedule");
    },

    scheduleItems: function() {
      return this.getList("schedule").map(obj => {
        return {
          key: obj.id,
          value:
            new Date(obj.start.slice(0, 10)).toLocaleString("en-AU", {
              day: "2-digit",
              month: "short"
            }) +
            " - " +
            obj.job.title
        };
      });
    }
  },

  mounted() {
    this.loading = true;
    this.fetch({ resource: "schedule", query: { with: "job" } })
      .then(() => {
        this.loading = false;
      })
      .catch(error => {
        this.loading = false;
      });
  },

  methods: {
    ...mapActions({
      fetch: "resource/list",
      create: "resource/create"
    }),

    onSubmit(e) {
      let form = {};
      form.schedule_id = this.form.schedule_id;
      form.from =
        this.form.fromDate +
        "T" +
        (this.form.fromTime == "" ? "00:00" : this.form.fromTime);
      form.till =
        this.form.tillDate +
        "T" +
        (this.form.tillTime == "" ? "00:00" : this.form.tillTime);
      form.desc = this.form.desc;
      this.create({ resource: "activity", data: form })
        .then(() => {
          console.log("Report Created Successfuly.");
        })
        .catch(error => {
          this.$formFeedback(error.response.data.errors);
        });
    },

    clearForm() {
      this.form.schedule_id = null;
      this.form.fromDate = "";
      this.form.fromTime = "";
      this.form.tillDate = "";
      this.form.tillTime = "";
      this.form.desc = "";
    },

    onCancel(e) {}
  }
};
</script>

<style scoped>
</style>
