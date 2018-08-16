<template>
    <nav-view :scrollbar="true" :loading="loading" padding="p-2">

        <template slot="toolbar">
          <pagination-view v-model="page" :page-count="pageCount"></pagination-view>
        </template>

        <template slot="drawer">
            <ul class="v-menu">
                <li class="menu-title">Actions</li>
                <li><router-link :to="{ name: 'profileCreate' }">New</router-link></li>
                <li class="menu-title">Links</li>                
            </ul>
        </template>

        <template slot="content">

            <table-view class="table table-striped" 
            v-model="selects" :data="profiles" :header="header" :selection="true" order-by="id" order="asc">
               <template slot="header" slot-scope="{item}">
                 <div :data-orderBy="item.orderBy">{{ item.value }}</div>
               </template>
               <template slot="body" slot-scope="{item}">
                 <td>{{ item.id }}</td>
                 <td><router-link :to="{ name:'profileDetails', params:{ id : item.id }}">{{ item.title }}</router-link></td>
                 <td>{{ item.create }}</td>
                 <td>{{ item.update }}</td>
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
      profiles: [],
      page: 1,
      perPage: 10,
      pageCount: 10,
      loading: true,
      selects: [],
      header: [
        { value: "ID", orderBy: "id" },
        { value: "Title", orderBy: "title" },
        { value: "Create", orderBy: "create" },
        { value: "Update", orderBy: "update" }
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
        resource: "form",
        query: { page: this.page, perPage: this.perPage }
      })
        .then(respond => {
          this.profiles = respond.data.map(obj => {
            return {
              id: obj.id,
              title: obj.name,
              create: obj.created_at,
              update: obj.updated_at
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