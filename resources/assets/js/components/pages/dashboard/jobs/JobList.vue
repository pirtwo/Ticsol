<template>
    <nav-view :scrollbar="true" :loading="loading" menu-title="Jobs" drawer-title="">

        <template slot="menu"></template>

        <template slot="drawer"></template>

        <template slot="content">

            <table-view class="table table-striped" 
            v-model="selects" :data="jobs" :header="header" :selection="true" order-by="title" order="asc">
               <template slot="header" slot-scope="{item}">
                 <div :data-orderBy="item.orderBy">{{ item.value }}</div>
               </template>
               <template slot="body" slot-scope="{item}">
                 <td>{{ item.id }}</td>
                 <td>{{ item.title }}</td>
                 <td>{{ item.code }}</td>
                 <td>{{ item.active ? "Yes" : "No" }}</td>
               </template> 
            </table-view>

        </template>
    </nav-view>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import NavView from "../../../framework/NavView.vue";
import TableView from "../../../framework/BaseTable.vue";
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
      selects: [],
      header: [
        { value: "ID", orderBy: "id" },
        { value: "Title", orderBy: "title" },
        { value: "Code", orderBy: "code" },
        { value: "Active", orderBy: "active" }
      ],
      order: "asc"
    };
  },
  mounted() {
    this.feedTable();
  },
  methods: {
    ...mapActions({ jobList: "job/list" }),
    feedTable() {
      this.loading = true;
      this.jobList({
        query: [
          { key: "page", value: this.page },
          { key: "count", value: this.pageCount }
        ]
      })
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