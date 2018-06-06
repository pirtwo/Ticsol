<template>
    <nav-view v-bind:scrollbar="false">
        <template slot="pane">
            <ul class="navview-menu">
                <li class="item-header">Create Job</li>
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
                        <span class="caption">Delete</span>
                    </a>
                </li>
                <li>
                    <a href="#">
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
            <form class="wrap-form">
                <div class="form-group">
                    <label>Name</label>
                    <textarea data-role="textarea" data-auto-size="true" data-max-height="200"></textarea>
                </div>
                <div class="form-group">
                    <label>Parent</label>
                    <input type="text" placeholder="select parent job" />
                </div>
                <div class="form-group">
                    <label>Profile</label>
                    <input type="text" placeholder="select job profile" />
                </div>
                <div class="form-group">
                    <label>Code</label>
                    <input type="text" placeholder="enter code for job" />
                </div>
                <div class="form-group">
                    <input type="checkbox" data-role="checkbox" data-caption="Status (is active)">
                </div>
                <div class="form-group">
                    <button type="submit" class="button success">Save</button>
                    <input type="button" class="button" value="Cancel">
                </div>
            </form>
        </template>
    </nav-view>
</template>

<script>
import NavView from "../../../framework/NavView.vue";
export default {
  name: "JobList",
  components: {
    "nav-view": NavView
  },
  data() {
    return {};
  },
  mounted() {},
  methods: {}
};
</script>

<style scoped>
.wrap-form {
  height: 100%;
  padding: 10px 20px;
  background-color: rgba(255, 255, 255, 0.8);
}
</style>