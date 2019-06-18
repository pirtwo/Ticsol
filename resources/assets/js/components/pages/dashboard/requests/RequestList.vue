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
          <router-link :to="{ name: 'profileCreate' }">
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
        :data="requests"
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
              v-if="item.type === 'timesheet'"
              class="btn btn-sm btn-light"
              :to="{ name : 'timesheetDetails', params : { id: item.timesheet.id, start: item.timesheet.week_start.slice(0,10), end: item.timesheet.week_end.slice(0,10) } }"
            >
              <i class="material-icons">visibility</i>
            </router-link>
            <router-link
              v-if="item.type === 'leave'"
              class="btn btn-sm btn-light"
              :to="{ name : 'leaveDetails', params : { id: item.id } }"
            >
              <i class="material-icons">visibility</i>
            </router-link>
            <router-link
              v-if="item.type === 'reimbursement'"
              class="btn btn-sm btn-light"
              :to="{ name : 'reimbDetails', params : { id: item.id } }"
            >
              <i class="material-icons">visibility</i>
            </router-link>
          </td>
          <td>{{ item.type }}</td>
          <td>{{ item.assigned === null ? "None" : item.assigned.name }}</td>
          <td>{{ item.status }}</td>
          <td>{{ summary(item) }}</td>
          <td>{{ item.created_at }}</td>
        </template>
      </ts-table>
      <!-- Filter view -->
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
  name: "RequestList",

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
        { value: "Type", orderBy: "type" },
        { value: "Approver", orderBy: "name" },
        { value: "Status", orderBy: "status" },
        { value: "Summary", orderBy: "" },
        { value: "Submitted date", orderBy: "created_at" }
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

    requests: function() {
      return this.getList("request");
    },

    filterColumns: function() {
      return [
        {
          key: "type",
          value: "Type",
          type: "string",
          placeholder: "Search for type..."
        },
        {
          key: "request.assigned.name",
          value: "Assigned",
          type: "string",
          placeholder: "Search for assigned..."
        },
        {
          key: "status",
          value: "Status",
          type: "string",
          placeholder: "Search for status..."
        },
        {
          key: "meta->leave_type",
          value: "Leave type",
          type: "string",
          placeholder: "Search for leave type..."
        },
        {
          key: "meta->amount",
          value: "Reimbursement amount",
          type: "number",
          placeholder: "Search for reimbursement amount..."
        },
        {
          key: "request.job.title",
          value: "Job Title",
          type: "string",
          placeholder: "Search for Request\\Job\\Title..."
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
      this.startLoading();
      if (this.opt)
        this.query.push({ opt: this.opt, col: this.col, val: this.val });
      this.fetchList({
        resource: "request",
        query: this.$queryBuilder(
          this.pager.page,
          this.pager.perPage,
          ["job",'assigned',"timesheet"],
          this.query
        )
      })
        .then(respond => {
          this.pager.pageCount = respond.last_page ? respond.last_page : 1;
          this.stopLoading();
        })
        .catch(error => {
          console.log(error);
          this.stopLoading();
        });
    },

    summary(item) {
      if (item.type === "leave") {
        let days = (
          (new Date(item.meta.till) - new Date(item.meta.from)) /
          86400000
        ).toFixed(0);

        return `From: ${item.meta.from} - Till: ${
          item.meta.till
        }  Days: ${days}`;
      } else if (item.type === "reimbursement") {
        return `${item.meta.date} - $${item.meta.amount}`;
      }else if(item.type === "timesheet"){
        let from = new DayPilot.Date(item.timesheet.week_start).toString('dd/MM/yyyy');
        let till = new DayPilot.Date(item.timesheet.week_end).toString('dd/MM/yyyy');
        let hours = item.timesheet.total_hours.slice(0,2);
        return `${from} - ${till}, ${hours} Hours`;
      }
    },

    getDate(date) {
      let d = new DayPilot.Date(Date.UTC(date));
      return d.toDateLocal();
    }
  }
};
</script>

<style scoped>
.table td {
  text-transform: capitalize;
}
</style>