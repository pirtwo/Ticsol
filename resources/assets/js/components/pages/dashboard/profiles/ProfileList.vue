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
            :to="{ name: 'profileCreate' }"
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
        class="table-responsive"
        :data="profiles"
        :headers="headers"
        orderby="Title"
      >
        <template
          slot="header"
          slot-scope="{item}"
        >
          <div>{{ item.value }}</div>
        </template>
        <template
          slot="body"
          slot-scope="{item}"
        >
          <td>
            <router-link
              class="btn btn-sm"
              :to="{ name : 'profileDetails', params : { id: item.id } }"
            >
              <i class="material-icons">visibility</i>
            </router-link>
          </td>
          <td>{{ item.name }}</td>
          <td>{{ item.parent ? item.parent.name : "-" }}</td>
          <td>{{ item.created_at }}</td>
          <td>{{ item.updated_at }}</td>
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
            return f.name;
          }
        },
        {
          value: "Parent",
          orderby: f => {
            return f.parent ? f.parent.name : "Z";
          }
        },
        {
          value: "Create",
          orderby: f => {
            return f.created_at;
          }
        },
        {
          value: "Update",
          orderby: f => {
            return f.updated_at;
          }
        }
      ]
    };
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    profiles: function() {
      return this.getList("form");
    },

    filterColumns: function() {
      return [
        {
          key: "name",
          value: "Name",
          type: "string",
          placeholder: "Search for Name..."
        },
        {
          key: "form.jobs.title",
          value: "Jobs\\Title",
          type: "string",
          placeholder: "Search for Jobs\\Title..."
        },
        {
          key: "form.jobs.code",
          value: "Jobs\\Code",
          type: "string",
          placeholder: "Search for Jobs\\Code..."
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
        resource: "form",
        query: this.$queryBuilder(
          this.pager.page,
          this.pager.perPage,
          ["parent"],
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
    }
  }
};
</script>

<style scoped>
</style>