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
              class="btn btn-sm"
              :to="{ name : 'activityDetails', params : { id: item.id } }"
            >
              <i class="material-icons">visibility</i>
            </router-link>
          </td>
          <td>{{ item.job.title }}</td>
          <td>{{ dateToString(item.from) }}</td>
          <td>{{ dateToString(item.till) }}</td>
        </template>
      </ts-table>
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

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    reports: function() {
      return this.getList("activity");
    }
  },

  mounted() {
    this.feedTable();
  },

  methods: {
    ...mapActions({ fetch: "resource/list" }),

    feedTable() {
      this.startLoading();
      this.fetch({
        resource: "activity",
        query: this.$queryBuilder(
          this.pager.page,
          this.pager.perPage,
          ["schedule", "job"],
          this.opt ? [{ opt: this.opt, col: this.col, val: this.val }] : []
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