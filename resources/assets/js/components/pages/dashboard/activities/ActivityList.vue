<template>
  <app-main
    :scrollbar="true"
    :loading="isLoading"
    padding="p-2"
  >
    <template slot="toolbar">
      <ts-pagination
        v-model="pager"
        :page-count="pager.pageCount"
        @input="feedTable"
      />
      <button
        type="button"
        class="btn btn-sm mr-auto"
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
            class="btn"
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
      <ts-table
        class="table table-striped"
        :data="reports"
        :header="header"
        :selection="false"
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
              class="btn btn-sm"
              :to="{ name : 'activityDetails', params : { id: item.id } }"
            >
              <i class="material-icons">visibility</i>
            </router-link>
          </td>
          <td>{{ item.creator.fullname }}</td>
          <td>{{ item.job.title }}</td>
          <td>{{ dateToString(item.from) }}</td>
          <td>{{ dateToString(item.till) }}</td>
        </template>
      </ts-table>
      <ts-filter
        v-model="query"
        :show.sync="showFilter"
        :columns="filterColumns"
        @apply="feedTable"
      />
    </template>
  </app-main>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import pageMixin from "../../../../mixins/page-mixin";

export default {
  name: "ActivityList",

  mixins: [pageMixin],

  components: {},

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
        { value: "Creator", orderBy: "creator.fullname" },
        { value: "Job", orderBy: "job.title" },
        { value: "From", orderBy: "from" },
        { value: "Till", orderBy: "till" }
      ]
    };
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    reports: function() {
      return this.getList("activity");
    },

    filterColumns: function() {
      return [
        {
          key: "activity.creator.firstname",
          value: "Creator\\FirstName",
          type: "date",
          placeholder: "Enter creator firstname"
        },
        {
          key: "activity.creator.lastname",
          value: "Creator\\LastName",
          type: "date",
          placeholder: "Enter creator lastname"
        },
        {
          key: "from",
          value: "From",
          type: "date",
          placeholder: "yyyy-mm-dd"
        },
        {
          key: "till",
          value: "Till",
          type: "date",
          placeholder: "yyyy-mm-dd"
        },
        {
          key: "activity.job.title",
          value: "Job\\Title",
          type: "string",
          placeholder: "Enter job title"
        },
        {
          key: "activity.job.profile.name",
          value: "Job\\Profile\\Title",
          type: "string",
          placeholder: "Enter job profile name"
        },
      ];
    }
  },

  mounted() {
    this.feedTable();
  },

  methods: {
    ...mapActions({ fetch: "resource/list" }),

    feedTable() {
      this.startLoading();
      if (this.opt)
        this.query.push({ opt: this.opt, col: this.col, val: this.val });
      this.fetch({
        resource: "activity",
        query: this.$queryBuilder(
          this.pager.page,
          this.pager.perPage,
          ["creator", "schedule", "job"],
          this.query
        )
      })
        .then(respond => {
          this.pager.pageCount = respond.last_page;
          this.stopLoading();
        })
        .catch(error => {
          console.log(error);
          this.stopLoading();
        });
    },

    dateToString(date) {
      return new window.DayPilot.Date(date).toString("ddd dd MMM yyyy");
    }
  }
};
</script>

<style scoped>
</style>