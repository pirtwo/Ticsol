<template>
    <nav-view :scrollbar="true" :loading="loading" padding="p-2">

        <template slot="toolbar">
          <pagination-view v-model="page" :page-count="pageCount"></pagination-view>
        </template>

        <template slot="drawer">

        </template>

        <template slot="content">

            <table-view class="table table-striped" 
            v-model="selects" :data="jobs" :header="header" :selection="true" order-by="id" order="asc">
               <template slot="header" slot-scope="{item}">
                 <div :data-orderBy="item.orderBy">{{ item.value }}</div>
               </template>
               <template slot="body" slot-scope="{item}">
                 <td>{{ item.id }}</td>
                 <td><router-link :to="{ name:'jobDetails', params:{ id : item.id }}">{{ item.title }}</router-link></td>
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
import PaginationView from "../../../framework/BasePagination.vue";

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
      perPage: 10,
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

  watch: {
    page: function(value) {
      this.feedTable();
    }
  },

  mounted() {
    this.feedTable();
  },

  methods: {
    ...mapActions({ fetch: "resource/list" }),

    feedTable() {
      this.loading = true;
      this.fetch({
        resource: "job",
        query: { page: this.page, perPage: this.perPage }
      })
        .then(respond => {
          this.jobs = respond.data.map(obj => {
            return {
              id: obj.id,
              title: obj.title,
              active: obj.isactive,
              code: obj.code
            };
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