<template>
    <nav-view v-bind:loading-content="tableLoading" v-bind:scrollbar="false">
        <template slot="pane">           
            <ul class="navview-menu">
                <li class="item-header">Job List</li>
                <li class="item-separator"></li>

                <li class="item-header bg-dark">Actions</li>                
                <li>
                    <router-link :to="{ name: 'jobList' }">
                        <span class="caption">List</span>
                    </router-link>
                </li>
                <li>
                    <router-link :to="{ name: 'jobCreate' }">
                        <span class="caption">New</span>
                    </router-link>
                </li>
                <li>
                    <a href="#">
                        <!-- <span class="icon"><span class="mif-alarm"></span></span> -->
                        <span class="caption">Delete</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <!-- <span class="icon"><span class="mif-bin"></span></span> -->
                        <span class="caption">Print</span>
                    </a>
                </li>


                <li class="item-header bg-dark">Links</li>
                <li>
                    <a href="#">
                        <span class="caption">Related Jobs</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="caption">Related Contacts</span>
                    </a>
                </li>
                 <li>
                    <a href="#">
                        <span class="caption">Related Requests</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="caption">Scheduled Items</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="caption">Activity Reports</span>
                    </a>
                </li>          
                
            </ul>
        </template>
        <template slot="content">            
            <table-view
                v-bind:data="tableData"
                v-bind:headers="[]"
                v-bind:table-classes="['table', 'striped', 'row-hover']"
                v-bind:row-classes="[]"
                v-bind:loading="tableLoading">
            </table-view> 
            <pagination-view 
                v-bind:base-url="'#'" 
                v-bind:page-count="pageCount" 
                v-bind:current-page="page" >
            </pagination-view>
        </template>
    </nav-view>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import NavView from "../../../framework/NavView.vue";
import TableView from "../../../framework/TableView.vue";
import PaginationView from "../../../framework/PaginationView.vue";

export default {
  name: "JobList",
  components: {
    "nav-view": NavView,
    "table-view": TableView,
    "pagination-view": PaginationView
  },
  data() {
    return {
      tableData: null,
      tableLoading: true,      
      page: 1,
      pageCount: 10
    };
  },
  mounted() {
    this.feedTable();
  },
  methods: {
    ...mapActions({ jobList: "job/list" }),
    feedTable() {
      this.tableLoading = true;
      this.jobList({query : [
          { key: "page", value: this.page },
          { key: "count", value: this.pageCount }
        ]})
        .then(respond => {
          this.tableData = respond.data.map(obj => {
            var nObj = {};
            nObj.id = obj.id;
            nObj.title = obj.title;
            nObj.active = obj.isactive;
            nObj.code = obj.code;
            return nObj;
          });
          this.pageCount = respond.last_page;
          this.tableLoading = false;
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>

<style scoped>
</style>