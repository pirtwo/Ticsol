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
            :to="{ name: 'roleCreate' }"
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
        :data="roles"
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
              :to="{ name : 'roleDetails', params : { id: item.id } }"
            >
              <i class="material-icons">visibility</i>
            </router-link>
          </td>
          <td>{{ item.name }}</td>
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
  name: "RoleList",

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
          value: "Created At",
          orderby: f => {
            return f.created_at;
          }
        },
        {
          value: "Updated At",
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

    roles: function() {
      return this.getList("role");
    },

    filterColumns: function() {
      return [
        {
          key: "name",
          value: "Title",
          type: "string",
          placeholder: "Search for title..."
        },
        {
          key: "creator",
          value: "Creator",
          type: "string",
          placeholder: "Search for code..."
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
        resource: "role",
        query: this.$queryBuilder(
          this.pager.page,
          this.pager.perPage,
          [],
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