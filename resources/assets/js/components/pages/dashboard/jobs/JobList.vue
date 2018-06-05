<template>
    <nav-view>
        <template slot="pane">
            <button class="pull-button">
                <span class="default-icon-menu"></span>
            </button>
            <ul class="navview-menu">
                <li class="item-header">Job List</li>
                <li class="item-separator"></li>

                <li class="item-header bg-dark">Actions</li>                
                <li>
                    <a href="#">
                        <!-- <span class="icon"><span class="mif-list-numbered"></span></span> -->
                        <span class="caption">List</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <!-- <span class="icon"><span class="mif-note-add"></span></span> -->
                        <span class="caption">New</span>
                    </a>
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
        </template>
    </nav-view>
</template>

<script>
import axios from "axios";
import NavView from "../../../framework/NavView.vue";
import TableView from "../../../framework/TableView.vue";
export default {
  name: "JobList",
  components: {
    "nav-view": NavView,
    "table-view": TableView
  },
  data() {
    return {
      tableData: null,
      tableLoading: true
    };
  },
  mounted() {
    this.feedTable();
  },
  methods: {
    feedTable() {
      this.tableLoading = true;
      axios.get("https://server.dev/api/jobs/list").then(respond => {        
        this.tableData = respond.data.map(obj => {
            var nObj = {};
            nObj.id = obj.id;
            nObj.title = obj.title;
            nObj.active = obj.isactive;
            nObj.code = obj.code;
            return nObj;
        });
        this.tableLoading = false;
      });
    }
  }
};
</script>

<style scoped>
</style>