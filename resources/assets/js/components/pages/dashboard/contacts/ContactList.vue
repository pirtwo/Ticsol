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
        class="table table-striped"         
        :data="contacts" 
        :header="header" 
        :selection="false" 
        order-by="name" 
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
import pageMixin from '../../../../mixins/page-mixin';

export default {
  name: "ContactList",

  mixins:[pageMixin],

  components: {
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
        { value: "Name", orderBy: "name" },
        { value: "Group", orderBy: "group" },
        { value: "Tel", orderBy: "telephone" },
        { value: "Mobile", orderBy: "mobilephone" }
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

    contacts: function(){
      return this.getList("contact");
    },

    filterColumns: function() {
      return [
        {
          key: "firstname",
          value: "Firstname",
          type: "string",
          placeholder: "Search for Firstname..."
        },
        {
          key: "lastname",
          value: "Lastname",
          type: "string",
          placeholder: "Search for Lastname..."
        },
        {
          key: "group",
          value: "group",
          type: "string",
          placeholder: "Search for Group..."
        },
        {
          key: "contact.addresses.street",
          value: "Address\\Street",
          type: "string",
          placeholder: "Search for Contact\\Addresses\\Street..."
        },
        {
          key: "contact.addresses.state",
          value: "Contact\\State",
          type: "string",
          placeholder: "Search for Contact\\Addresses\\State..."
        },
        {
          key: "contact.addresses.country",
          value: "Contacts\\Country",
          type: "string",
          placeholder: "Search for Contact\\Addresses\\Country..."
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