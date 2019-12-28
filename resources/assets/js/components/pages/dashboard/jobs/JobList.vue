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
        <template v-if="userCan('job', ['full', 'create'])">
          <li class="menu-title">
            Actions
          </li>
          <li>
            <router-link
              tag="button"
              class="btn"
              :to="{ name: 'jobCreate' }"
            >
              New
            </router-link>
          </li>
        </template>
        <li class="menu-title">
          Links
        </li>
      </ul>
    </template>

    <template slot="content">
      <ts-table
        class="table-responsive"
        :data="jobs"
        :headers="headers"
        orderby="Profile"
      >
        <template
          slot="header"
          slot-scope="{item}"
        >
          <div>
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
              :to="{ name : 'jobDetails', params : { id: item.id } }"
            >
              <i class="material-icons">visibility</i>
            </router-link>
          </td>
          <td>{{ item.title }}</td>
          <td>{{ item.profile ? item.profile.name : "-" }}</td>
          <td>{{ item.parent ? item.parent.title : "-" }}</td>
          <td>{{ item.code }}</td>
          <td>{{ item.isactive ? "Yes" : "No" }}</td>
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
  name: "JobList",

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
      headers: [
        { value: "", orderby: "" },
        {
          value: "Title",
          orderby: f => {
            return f.title;
          }
        },        
        {
          value: "Profile",
          orderby: f => {
            return f.profile ? f.profile.name : "Z";
          }
        },
        {
          value: "Parent",
          orderby: f => {
            return f.parent ? f.parent.title : "Z";
          }
        },  
        {
          value: "Code",
          orderby: f => {
            return f.code;
          }
        },      
        {
          value: "Active",
          orderby: f => {
            return f.isactive;
          }
        }
      ]
    };
  },

  computed: {
    ...mapGetters({
      userCan: "user/can",
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
          this.stopLoading();
        })
        .catch(error => {
          console.log(error);
          this.stopLoading();
        });
    }
  }
};
</script>

<style scoped>
</style>