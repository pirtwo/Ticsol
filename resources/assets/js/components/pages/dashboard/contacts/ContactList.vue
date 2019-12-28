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
            :to="{ name: 'contactCreate' }"
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
        :data="contacts"
        :headers="headers"
        orderby="Name"
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
              :to="{ name : 'contactDetails', params : { id: item.id } }"
            >
              <i class="material-icons">visibility</i>
            </router-link>
          </td>
          <td>{{ `${item.firstname} ${item.lastname}` }}</td>
          <td>{{ item.group.toUpperCase() }}</td>
          <td>{{ item.telephone ? item.telephone : "-" }}</td>
          <td>{{ item.mobilephone ? item.mobilephone : "-" }}</td>
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
  name: "ContactList",

  mixins: [pageMixin],

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
          value: "Name",
          orderby: f => {
            return `${f.firstname} ${f.lastname}`;
          }
        },
        {
          value: "Group",
          orderby: f => {
            return f.group;
          }
        },
        {
          value: "Tel",
          orderby: f => {
            return f.telephone;
          }
        },
        {
          value: "Mobile",
          orderby: f => {
            return f.mobilephone;
          }
        }
      ],
      order: "asc"
    };
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    contacts: function() {
      return this.getList("contact");
    },

    filterColumns: function() {
      return [
        {
          key: "firstname",
          value: "Firstname",
          type: "string",
          placeholder: "Enter contact first name"
        },
        {
          key: "lastname",
          value: "Lastname",
          type: "string",
          placeholder: "Enter contact last name"
        },
        {
          key: "group",
          value: "Group",
          type: "string",
          placeholder: "Enter contact group name"
        },
        {
          key: "contact.addresses.street",
          value: "Address\\Street",
          type: "string",
          placeholder: "Enter address street"
        },
        {
          key: "contact.addresses.state",
          value: "Address\\State",
          type: "string",
          placeholder: "Enter address state"
        },
        {
          key: "contact.addresses.country",
          value: "Address\\Country",
          type: "string",
          placeholder: "Enter address country"
        },
        {
          key: "contact.jobs.title",
          value: "Contact\\Jobs\\Title",
          type: "array",
          placeholder: "seperate values with comma..."
        },
        {
          key: "contact.jobs.profile.name",
          value: "Contact\\Jobs\\Profile",
          type: "array",
          placeholder: "seperate values with comma..."
        }
      ];
    }
  },

  beforeRouteLeave(to, from, next) {
    this.clear("contact");
    next();
  },

  mounted() {
    this.feedTable();
  },

  methods: {
    ...mapActions({
      fetchList: "resource/list",
      clear: "resource/clearResource"
    }),

    feedTable() {
      this.startLoading();
      if (this.opt)
        this.query.push({ opt: this.opt, col: this.col, val: this.val });
      this.fetchList({
        resource: "contact",
        query: this.$queryBuilder(
          this.pager.page,
          this.pager.perPage,
          [],
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