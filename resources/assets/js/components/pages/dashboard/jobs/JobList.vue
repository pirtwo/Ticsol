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
      <button
        type="button"
        class="btn btn-light btn-sm mr-auto"
        @click="showFilter = true"
      >
        <i class="material-icons">filter_list</i>
      </button>
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
            :to="{ name: 'jobCreate' }"
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
        :data="jobs"
        :header="header"
        :selection="false"
        order-by="title"
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
              :to="{ name : 'jobDetails', params : { id: item.id } }"
            >
              <i class="material-icons">visibility</i>
            </router-link>
          </td>
          <td>{{ item.title }}</td>          
          <td>{{ item.parent ? item.parent.title : "-" }}</td>
          <td>{{ item.profile ? item.profile.name : "-" }}</td>
          <td>{{ item.code }}</td>
          <td>{{ item.isactive ? "Yes" : "No" }}</td>
        </template>
      </table-view>
      <ts-filter
        v-model="query"
        :show.sync="showFilter"
        :columns="filterColumns"
        @apply="feedTable"
      />
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
  name: "JobList",

  mixins:[pageMixin],

  components: {
    "nav-view": NavView,
    "table-view": TableView,
    "pagination-view": PaginationView
  },

  props: ["col", "opt", "val"],

  data() {
    return {
      query: [],
      showFilter: false,
      pager: {
        page: 1,
        perPage: 10,
        pageCount: 10
      },       
      header: [
        { value: "", orderBy: "" },
        { value: "Title", orderBy: "title" },
        { value: "Parent", orderBy: "parent.title" },
        { value: "Profile", orderBy: "profile.name" },
        { value: "Code", orderBy: "code" },
        { value: "Active", orderBy: "active" }
      ],
      order: "asc"
    };
  },

  watch: {
    pager: function(value) {
      this.feedTable();
    }
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    jobs: function() {
      return this.getList("job");
    },

    filterColumns: function() {
      return [
        {
          key: "title",
          value: "Title",
          type: "string",
          placeholder: "Search for title..."
        },
        {
          key: "code",
          value: "Code",
          type: "string",
          placeholder: "Search for code..."
        },
        {
          key: "isactive",
          value: "Active",
          type: "boolean",
          placeholder: "1 for Active and 0 for Inactive jobs..."
        },
        {
          key: "job.parent.title",
          value: "Parent\\Title",
          type: "string",
          placeholder: "Search for Job\\Parent\\Title..."
        },
        {
          key: "job.profile.name",
          value: "Profle\\Name",
          type: "string",
          placeholder: "Search for Job\\Profile\\Name..."
        },
        {
          key: "job.contacts.firstname",
          value: "Contacts\\FirstName",
          type: "string",
          placeholder: "Search for Job\\Contacts\\FirstName..."
        },
        {
          key: "job.contacts.lastname",
          value: "Contacts\\LastName",
          type: "string",
          placeholder: "Search for Job\\Contacts\\LastName..."
        },
      ];
    }
  },

  mounted() {
    this.feedTable();
  },

  methods: {
    ...mapActions({ fetchList: "resource/list" }),

    feedTable() {
      this.loadingStart();
      if (this.opt)
        this.query.push({ opt: this.opt, col: this.col, val: this.val });
      this.fetchList({
        resource: "job",
        query: this.$queryBuilder(
          this.pager.page,
          this.pager.perPage,
          ["parent", "profile"],
          this.query
        )
      })
        .then(respond => {
          this.pager.pageCount = respond.last_page ? respond.last_page : 1;
          this.loadingStop();
        })
        .catch(error => {
          console.log(error);
          this.loadingStop();
        });
    }
  }
};
</script>

<style scoped>
</style>