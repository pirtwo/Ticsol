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
            
            <form>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-lable">Schedule Item</label>
                        <div class="col-sm-10">
                            <auto-complete
                                v-model="form.schedule_id"
                                :data="scheduleItems"
                                name="scheduleItems"
                                place-holder="click to select item..."
                            ></auto-complete>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-lable">From</label>
                        <div class="col-sm-5">
                            <input type="date" class="form-control" />
                        </div>
                        <div class="col-sm-5">
                            <input type="time" class="form-control" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-lable">Till</label>                        
                        <div class="col-sm-5">
                            <input type="date" class="form-control" />
                        </div>
                        <div class="col-sm-5">
                            <input type="time" class="form-control" />
                        </div>                        
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-lable">Report</label>                        
                        <div class="col-sm-10">
                            <textarea class="form-control" placeholder="write your report..." rows="10"></textarea>
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
  name: "ActivityCreate",

  components: {
    "nav-view": NavView,
    "auto-complete": AutoComplete
  },

  data() {
    return {
      form: {
        schedule_id: null,
        from: null,
        till: null,
        desc: null
      },
      loading: false
    };
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    scheduleItems: function() {
      return this.getList("schedule").map(obj => {
        return {
          key: obj.id,
          value:
            new Date(obj.start.slice(0, 10)).toLocaleString("en-US", {
              month: "short",
              day: "2-digit"
            }) +
            " - " +
            obj.job.title
        };
      });
    }
  },

  mounted() {
    this.loading = true;
    this.fetch({ resource: "schedule" })
      .then(() => {        
        this.loading = false;
      })
      .catch(error => {        
        this.loading = false;
      });
  },

  methods: {
    ...mapActions({
      fetch: "resource/list"
    }),

    onSubmit(e) {
      console.log(this.form.item);
      console.log(this.form.time);
      console.log(this.form.date);
      console.log(this.form.report);
    },

    onCancel(e) {}
  }
};
</script>

<style scoped>
</style>
