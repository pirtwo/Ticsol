<template>
  <nav-view 
    :scrollbar="true" 
    :loading="isLoading" 
    padding="p-2"
  >
    <template slot="toolbar">
      <pagination-view 
        v-model="pager" 
        :page-count="pager.pageCount"
      />
    </template>

    <template slot="drawer">
      <ul class="v-menu">
        <li class="menu-title">
          Actions
        </li>
        <li>
          <router-link 
            tag="button" 
            class="btn btn-light" 
            :to="{ name: 'activityCreate' }"
          >
            New
          </router-link>
        </li>
        <li class="menu-title">
          Links
        </li>                
      </ul>
    </template>

    <template slot="content">
      <table-view 
        class="table table-striped"
        :data="reports" 
        :header="header" 
        :selection="false" 
        order-by="id" 
        order="asc"
      >
        <template 
          slot="header" 
          slot-scope="{item}"
        >
          <div :data-orderBy="item.orderBy">
            {{ item.value }}
          </div>
        </template>
        <template 
          slot="body" 
          slot-scope="{item}"
        >
          <td>
            <router-link 
              class="btn btn-sm btn-light" 
              :to="{ name : 'activityDetails', params : { id: item.id } }"
            >
              <i class="material-icons">visibility</i>
            </router-link>                   
          </td>
          <td>{{ item.job.title }}</td>
          <td>{{ dateToString(item.from) }}</td>
          <td>{{ dateToString(item.till) }}</td>
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
import pageMixin from '../../../../mixins/page-mixin';

export default {
  name: "ActivityList",

  mixins:[pageMixin],

  components: {
    "nav-view": NavView,
    "table-view": TableView,
    "pagination-view": PaginationView
  },

  props: ["col", "opt", "val"],

  data() {
    return {
      pager: {
        page: 1,
        perPage: 10,
        pageCount: 10
      },      
      header: [
        { value: "", orderBy: "" },
        { value: "Job", orderBy: "job" },
        { value: "From", orderBy: "from" },
        { value: "Till", orderBy: "till" }
      ],
      order: "asc"
    };
  },

  watch: {
    pager: function(value) {
      this.feedTable();
    }
  },

  computed:{
    ...mapGetters({
      getList: "resource/getList"
    }),

    reports: function(){
      return this.getList("activity");
    },
  },

  mounted() {
    this.feedTable();
  },

  methods: {
    ...mapActions({ fetch: "resource/list" }),

    feedTable() {
      this.loadingStart();
      this.fetch({
        resource: "activity",
        query: {
          page: this.pager.page,
          perPage: this.pager.perPage,
          with: "schedule,job",
          [this.opt]: `${this.col},${this.val}`
        }
      })
        .then(respond => {         
          this.pager.pageCount = respond.last_page;
          this.loadingStop();
        })
        .catch(error => {
          console.log(error);
          this.loadingStop();
        });
    },

    dateToString(date) {
      return new DayPilot.Date(date).toString("ddd dd MMM yyyy");
    }
  }
};
</script>

<style scoped>
</style>