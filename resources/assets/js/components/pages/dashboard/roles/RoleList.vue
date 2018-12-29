<template>
  <nav-view 
    :scrollbar="true" 
    :loading="loading" 
    padding="p-2">

    <template slot="toolbar">      
      <pagination-view 
        v-model="pager" 
        :page-count="pager.pageCount"/>
    </template>

    <template slot="drawer">
      <ul class="v-menu">
        <li class="menu-title">Actions</li>
        <li><router-link 
          tag="button" 
          class="btn btn-light" 
          :to="{ name: 'roleCreate' }">New</router-link></li>
        <li class="menu-title">Links</li>                
      </ul>
    </template>

    <template slot="content">

      <table-view 
        class="table table-striped" 
        v-model="selects" 
        :data="jobs" 
        :header="header" 
        :selection="false" 
        order-by="title" 
        order="asc">
        <template 
          slot="header" 
          slot-scope="{item}">
          <div :data-orderBy="item.orderBy">{{ item.value }}</div>
        </template>
        <template 
          slot="body" 
          slot-scope="{item}">
          <td>
            <router-link 
              class="btn btn-sm btn-light" 
              :to="{ name : 'roleDetails', params : { id: item.id } }">
              <i class="material-icons">visibility</i>
            </router-link> 
          </td>
          <td>{{ item.name }}</td>
          <td>{{ item.created_at }}</td>
          <td>{{ item.updated_at }}</td>
        </template> 
      </table-view>     

    </template>
  </nav-view>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import FilterView from "../../../framework/BaseFilter.vue";
import NavView from "../../../framework/NavView.vue";
import TableView from "../../../framework/BaseTable.vue";
import PaginationView from "../../../framework/BasePagination.vue";

export default {
  name: "RoleList",

  components: {
    "nav-view": NavView,
    "table-view": TableView,
    "filter-view": FilterView,
    "pagination-view": PaginationView
  },

  props: ["col", "opt", "val"],

  data() {
    return {
      jobs: [],
      pager: {
        page: 1,
        perPage: 10,
        pageCount: 10
      },
      loading: true,
      selects: [],
      query: [],
      showFilter: false,
      header: [
        { value: "", orderBy: "" },
        { value: "Title", orderBy: "name" },
        { value: "Created At", orderBy: "created_at" },
        { value: "Updated At", orderBy: "updated_at" }
      ],
      order: "asc"
    };
  },

  watch: {
    pager: function(value) {
      this.feedTable();
    },

    query: function(value) {
      console.log("update");
      console.log(value);
    }
  },

  computed: {
    columnList: function() {
      return [
        { key: "title", value: "Title" },
        { key: "code", value: "Code" },
        { key: "isactive", value: "Active" }
      ];
    }
  },

  mounted() {    
    this.feedTable();
  },

  methods: {
    ...mapActions({ fetch: "resource/list", logger: "core/pushLog" }),

    feedTable() {
      this.loading = true;
      this.fetch({
        resource: "role",
        query: this.$queryBuilder(
          this.pager.page,
          this.pager.perPage,
          this.opt,
          this.col,
          this.val,
          this.query
        )
      })
        .then(respond => {
          this.jobs = respond.data;
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