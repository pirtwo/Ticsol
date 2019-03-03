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
       
        <li class="menu-title">
          Links
        </li>                
      </ul>
    </template>

    <template slot="content">
      <table-view 
        class="table table-striped" 
        v-model="selects" 
        :data="users" 
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
              :to="{ name : 'profileDetails', params : { id: item.id } }"
            >
              <i class="material-icons">visibility</i>
            </router-link> 
          </td>
          <td> 
            <img :src="item.meta.avatar" alt="user-avatar" width="40" height="40">
            {{ item.name }}
          </td>
          <td>
            <span v-for="contact in item.contacts" :key="contact.id">
              <router-link 
                class="btn btn-sm btn-link" 
                :to="{ name : 'contactDetails', params : { id: contact.id } }"
              >
                {{ `${contact.firstname} ${contact.lastname}` }}
              </router-link> 
            </span>
          </td>
          <td>
            <span v-for="role in item.roles" :key="role.id">
              <router-link 
                class="btn btn-sm btn-link" 
                :to="{ name : 'roleDetails', params : { id: role.id } }"
              >
                {{ role.name }}
              </router-link> 
            </span>
          </td>
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
  name: "UserList",

  mixins:[pageMixin],

  components: {
    "nav-view": NavView,
    "table-view": TableView,
    "pagination-view": PaginationView
  },

  props: ["col", "opt", "val"],

  data() {
    return {
      selects: [],
      pager: {
        page: 1,
        perPage: 10,
        pageCount: 10
      },      
      header: [
        { value: "", orderBy: "" },
        { value: "Name", orderBy: "name" },
        { value: "Contacts", orderBy: "contact" },
        { value: "Roles", orderBy: "roles" }
      ],
      order: "asc"
    };
  },

  watch: {
    pager: function(value) {
      this.feedTable();
    }
  },

  computed:{
    ...mapGetters({
      getList: "resource/getList"
    }),

    users:function(){
      return this.getList("user");
    }
  },

  mounted() {
    this.feedTable();
  },

  methods: {
    ...mapActions({ fetchList: "resource/list" }),

    feedTable() {
      this.loadingStart();
      this.fetchList({
        resource: "user",
        query: {
          with: "roles,contacts",
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