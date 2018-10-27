<template>
    <nav-view :scrollbar="true" :loading="loading" padding="p-2">

        <template slot="toolbar">
          <pagination-view v-model="pager" :page-count="pager.pageCount"></pagination-view>
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
            v-model="selects" :data="requests" :header="header" :selection="false" order-by="type" order="asc">
               <template slot="header" slot-scope="{item}">
                 <div :data-orderBy="item.orderBy">{{ item.value }}</div>
               </template>
               <template slot="body" slot-scope="{item}">
                 <td>
                   <router-link class="btn btn-sm btn-light" :to="{ name : 'profileDetails', params : { id: item.id } }">
                    <i class="material-icons">visibility</i>
                  </router-link> 
                 </td>
                 <td>{{ item.type }}</td>
                 <td>{{ item.status }}</td>
                 <td>{{ item.assigned.name }}</td>
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
  name: "RequestList",

  components: {
    "nav-view": NavView,
    "table-view": TableView,
    "pagination-view": PaginationView
  },

  props: ["col", "opt", "val"],

  data() {
    return {
      requests: [],
      pager: {
        page: 1,
        perPage: 10,
        pageCount: 10
      },
      loading: true,
      selects: [],
      header: [
        { value: "", orderBy: "" },
        { value: "Type", orderBy: "type" },
        { value: "Status", orderBy: "status" },
        { value: "Approver", orderBy: "name" }
      ],
      order: "asc"
    };
  },

  watch: {
    pager: function(value) {
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
        resource: "request",
        query: {
          page: this.pager.page,
          perPage: this.pager.perPage,
          with: "job,assigned,schedule",
          [this.opt]: `${this.col},${this.val}`
        }
      })
        .then(respond => {
          this.requests = respond.data;
          this.pager.pageCount = respond.last_page;
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