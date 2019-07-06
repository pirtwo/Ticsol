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
              class="btn btn-sm"
              :to="{ name : 'timesheetDetails', params : { id: item.timesheet.id, start: item.timesheet.week_start, end: item.timesheet.week_end } }"
            >
              <i class="material-icons">visibility</i>
            </router-link>
            <router-link
              v-if="item.type === 'leave'"
              class="btn btn-sm"
              :to="{ name : 'leaveDetails', params : { id: item.id } }"
            >
              <i class="material-icons">visibility</i>
            </router-link>
            <router-link
              v-if="item.type === 'reimbursement'"
              class="btn btn-sm"
              :to="{ name : 'reimbDetails', params : { id: item.id } }"
            >
              <i class="material-icons">visibility</i>
            </router-link>
          </td>
          <td>{{ item.type }}</td>
          <td>{{ item.assigned === null ? "None" : item.assigned.name }}</td>
          <td>{{ item.status }}</td>
          <td>{{ summary(item) }}</td>
          <td>{{ utcToLocal(item.created_at) }}</td>
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
import moment from "moment";
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
        { value: "Submitted At", orderBy: "created_at" }
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
        }
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
          ["job", "assigned", "timesheet"],
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
      let dateFormat = moment.localeData().longDateFormat('L');
      if (item.type === "leave") {
        // Show summary for leave request
        let from = moment(item.meta.from);
        let till = moment(item.meta.till);
        let days = till.diff(from, "days");
        let hours = till.diff(from, "hours");
        return `From: ${from.format(dateFormat)} - Till: ${till.format(dateFormat)}, ${
          days > 0 ? `Days: ${days}` : `Hours: ${hours}`
        }`;
      } else if (item.type === "timesheet") {
        // Show summary for timesheet request
        let from = moment(item.timesheet.week_start);
        let till = moment(item.timesheet.week_end);
        let hours = item.timesheet.total_hours.slice(0, 2);
        return `${from.format(dateFormat)} - ${till.format(dateFormat)}, ${hours} Hours`;
      } else if (item.type === "reimbursement") {
        // Show summary for reimbursement request
        return `${item.meta.date} - $${item.meta.amount}`;
      }
    },

    utcToLocal(date) {
      let utcDate = moment.utc(date).toDate();
      return moment(utcDate)
        .local()
        .format("YYYY-MM-DD HH:mm");
    }
  }
};
</script>

<style scoped>
.table td {
  text-transform: capitalize;
}
</style>