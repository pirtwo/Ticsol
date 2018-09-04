<template>
    <nav-view 
        :scrollbar="true" 
        :loading="loading" 
        padding="p-2">

        <template slot="toolbar">
            <date-picker v-model="weekStart" range="Week"></date-picker>
        </template>

        <template slot="drawer">

            <ul class="v-menu">
                <li class="menu-title">Actions</li>
                <li>
                    <button class="btn btn-light" @click="onSave">                        
                        Save
                    </button>
                </li>
                <li>
                    <button class="btn btn-light" @click="onSubmit">                        
                        Submit
                    </button>
                </li>
                <li>
                    <button class="btn btn-light" @click="onCancel">                        
                        Cancel
                    </button>
                </li>
                <li class="menu-title">Links</li>
            </ul>

        </template>

        <template slot="content">

            <div class="table-responsive">
                <table class="table table-hover table-light">
                    <thead>
                        <tr>                        
                        <th scope="col">Day</th>
                        <th scope="col">Link</th>
                        <th scope="col">Job</th>
                        <th scope="col">Start Time</th>
                        <th scope="col">End Time</th>
                        <th scope="col">Break</th>
                        <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in timesheet" :key="index">
                            <td>{{ item.day }}</td>
                            <td></td>
                            <td>{{ item.job.title }}</td>
                            <td>{{ item.start }}</td>
                            <td>{{ item.end }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
           
        </template>
    </nav-view>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import NavView from "../../../framework/NavView.vue";
import DatePicker from "../../../framework/BaseDatePicker.vue";

export default {
  name: "TimesheetCreate",

  components: {
    "nav-view": NavView,
    "date-picker": DatePicker
  },

  data() {
    return {
      loading: false,
      scheduleItems: [],
      timesheetItems: [],
      weekStart: "",
      weekEnd: ""
    };
  },

  watch: {
    weekStart: function(value) {
      this.weekEnd = value.addDays(7);
      this.fetchItems();
    }
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    timesheet: function() {
      let day = this.weekStart;
      for (let i = 0; i < 7; i++) {
        this.scheduleItems.forEach(item => {
          if (item.start <= day && item.end >= day) {
            item.day = day.toString("ddd dd MMM");
            this.timesheetItems.push(Object.assign({}, item));
          }
        });
        day = day.addDays(1);
      }
      return this.timesheetItems;
    }
  },

  created() {
    this.weekStart = DayPilot.Date.today().firstDayOfWeek();
    this.weekEnd = this.weekStart.addDays(7);
  },

  mounted() {
    this.fetchItems();
  },

  methods: {
    ...mapActions({
      fetch: "resource/list",
      create: "resource/create"
    }),

    fetchItems() {
      this.loading = true;
      this.fetch({
        resource: "schedule",
        query: {
          inRange: `${this.weekStart},${this.weekEnd}`,
          with: "job"
        }
      })
        .then(data => {
          this.scheduleItems = [];
          this.timesheetItems = [];
          this.scheduleItems = data;
          this.loading = false;
        })
        .catch(error => {
          console.log(error);
          this.loading = false;
        });
    },

    onSubmit(event) {},

    onSave(event) {},

    onCancel() {
      this.$router.go(-1);
    }
  }
};
</script>

<style scoped>
</style>