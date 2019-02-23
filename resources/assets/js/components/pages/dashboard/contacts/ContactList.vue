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
      <table-view 
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
              class="btn btn-sm btn-light" 
              :to="{ name : 'contactDetails', params : { id: item.id } }"
            >
              <i class="material-icons">visibility</i>
            </router-link>  
          </td>
          <td>{{ `${item.firstname} ${item.lastname}` }}</td>
          <td>{{ item.group }}</td>
          <td>{{ item.telephone }}</td>
          <td>{{ item.mobilephone }}</td>
        </template> 
      </table-view>
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
  name: "ContactList",

  mixins:[pageMixin],

  components: {
    "nav-view": NavView,
    "table-view": TableView,
    "pagination-view": PaginationView
  },

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
  },

  mounted() {
    this.feedTable();
  },

  methods: {
    ...mapActions({ fetchList: "resource/list" }),

    feedTable() {
      this.loadingStart();
      this.fetchList({
        resource: "contact",
        query: {
          page: this.pager.page,
          perPage: this.pager.perPage,
          [this.opt]: `${this.col},${this.val}`
        }
      })
        .then(respond => {         
          this.pager.pageCount = respond.last_page;
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