<template>
    <nav-view v-bind:scrollbar="true" v-bind:loading="this.loading" menu-title="Jobs" drawer-title="">

        <template slot="menu"></template>

        <template slot="drawer"></template>

        <template slot="content">

            <md-table v-model="jobs" md-sort="id" md-sort-order="asc" md-card>
                <md-table-toolbar>
                    
                </md-table-toolbar>
                <md-table-row slot="md-table-row" slot-scope="{ item }">
                    <md-table-cell md-label="ID" md-sort-by="id" md-numeric>{{ item.id }}</md-table-cell>
                    <md-table-cell md-label="Title" md-sort-by="title">{{ item.title }}</md-table-cell>
                    <md-table-cell md-label="Code" md-sort-by="code">{{ item.code }}</md-table-cell>
                    <md-table-cell md-label="Active" md-sort-by="isactive">{{ item.active ? 'Yes' : 'No' }}</md-table-cell>                    
                </md-table-row>
            </md-table>

        </template>
    </nav-view>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import NavView from "../../../framework/NavView.vue";
import TableView from "../../../framework/TableView.vue";
import PaginationView from "../../../framework/PaginationView.vue";

export default {
  name: "JobList",
  components: {
    "nav-view": NavView,
    "table-view": TableView,
    "pagination-view": PaginationView
  },
  data() {
    return {
      jobs: [],          
      page: 1,
      pageCount: 10,
      loading: true,  
    };
  },
  mounted() {
    this.feedTable();
  },
  methods: {
    ...mapActions({ jobList: "job/list" }),
    feedTable() {
      this.loading = true;
      this.jobList({query : [
          { key: "page", value: this.page },
          { key: "count", value: this.pageCount }
        ]})
        .then(respond => {
          this.jobs = respond.data.map(obj => {
            var nObj = {};
            nObj.id = obj.id;
            nObj.title = obj.title;
            nObj.active = obj.isactive;
            nObj.code = obj.code;
            return nObj;
          });          
          this.pageCount = respond.last_page;
          this.loading = false;
        })
        .catch(error => {
          console.log(error);
          this.loading = false;
        });
    }
  }
};
</script>

<style scoped>
</style>