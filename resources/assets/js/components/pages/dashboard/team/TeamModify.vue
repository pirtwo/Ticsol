<template>
  <app-main :scrollbar="true" :loading="isLoading" padding="p-5">
    <template slot="toolbar" />

    <template slot="drawer">
      <ul class="v-menu">
        <li class="menu-title">Actions</li>
        <li v-if="!this.team">
          <button class="btn" @click="onSubmit">Submit</button>
        </li>
        <li v-if="this.team">
          <button class="btn" @click="onSave">Save</button>
        </li>
        <li>
          <button class="btn" @click="onCancel">Cancel</button>
        </li>
        <li class="menu-title">Links</li>
        <li>
          <router-link class="btn" :to="{name:'teamList'}">Teams</router-link>
        </li>
      </ul>
    </template>

    <template slot="content">
      <form class="needs-validation">
        <div class="form-group">
          <div class="form-row">
            <div class="col-sm-6">
              <div class="row">
                <label class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                  <input
                    v-model="form.name"
                    id="name"
                    type="text"
                    class="form-control"
                    placeholder="Please enter team name here..."
                  />
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="row">
                <label class="col-sm-2 col-form-label">Members</label>
                <div class="col-sm-10">
                  <ts-select
                    v-model="form.users"
                    :data="users"
                    :multi="true"
                    :placeholder="'Please select team members...'"
                  >
                    <template slot-scope="{ item }">
                      <img
                        :src="item.pic"
                        :alt="`${item.value}-avatar`"
                        class="rounded"
                        width="30"
                        height="30"
                      />
                      <span>&nbsp; {{ item.value }}</span>
                    </template>
                  </ts-select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <ts-table
          class="table table-striped"
          :data="teamMembers"
          :header="header"
          :selection="false"
          order-by="title"
          order="asc"
        >
          <template slot="header" slot-scope="{item}">
            <div :data-orderBy="item.orderBy">{{ item.value }}</div>
          </template>
          <template slot="body" slot-scope="{item}">
            <td>
              <router-link
                class="btn btn-sm"
                :to="{ name : 'userDetails', params : { id: item.id } }"
              >
                <i class="material-icons">visibility</i>
              </router-link>
            </td>
            <td>{{ item.fullname }}</td>
            <td>
              <router-link
                v-for="item in item.teams"
                :key="item.id"
                class="btn btn-sm btn-link"
                :to="{ name : 'teamDetails', params : { id: item.id } }"
              >{{ item.name }}</router-link>
              <span v-if="item.teams.length === 0">None</span>
            </td>
          </template>
        </ts-table>
      </form>
    </template>
  </app-main>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import pageMixin from "../../../../mixins/page-mixin";

export default {
  name: "TeamModify",

  mixins: [pageMixin],

  props: ["id"],

  data() {
    return {
      form: {
        name: "",
        users: []
      },
      header: [
        { value: "", orderBy: "" },
        { value: "Name", orderBy: "name" },
        { value: "Teams", orderBy: "teams" }
      ]
    };
  },

  watch: {
    /**
     * Watching the current team, update form on change.
     */
    team: function(val) {
      this.populateForm();
    }
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    /**
     * Fetch the current team from store.
     */
    team: function() {
      return this.getList("team")[0];
    },

    /**
     * Fetch users list from store.
     */
    users: function() {
      return this.getList("user").map(item => {
        return { key: item.id, value: item.fullname, pic: item.meta.avatar, fullname:item.fullname, teams: item.teams };
      });
    },

    /**
     * Return the current team members.
     */
    teamMembers: function() {
      if (!this.team) return this.form.users;
      return this.team.users;
    }
  },

  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.loadAssets();
    });
  },

  beforeRouteLeave(to, from, next) {
    // clear the store
    this.clear("user");
    this.clear("team");
    next();
  },

  mounted() {
    //this.loadAssets();
  },

  methods: {
    ...mapActions({
      clear: "resource/clearResource",
      list: "resource/list",
      create: "resource/create",
      update: "resource/update"
    }),

    /**
     * Load the assets of this page from server.
     */
    async loadAssets() {
      this.startLoading();
      let p1 = await this.list({ resource: "user", query: { with: "teams" } });
      let p2 = !this.id
        ? new Promise(resolve => resolve())
        : this.list({
            resource: "team",
            id: this.id,
            query: { with: "users.teams", eq: `id,${this.id}` }
          });

      Promise.all([p1, p2]).then(() => {
        this.stopLoading();
      });
    },

    /**
     * Populate the form with current team.
     */
    populateForm() {
      this.form.name = this.team.name;
      this.form.users = this.team.users.map(item => {
        return { key: item.id, value: item.fullname, pic: item.meta.avatar };
      });
    },

    /**
     * Create a new team with form data.
     */
    onSubmit(e) {
      this.startLoading();
      e.preventDefault();
      e.target.disabled = true;

      // validate

      // Set request body
      let form = {};
      form.name = this.form.name;
      form.users = this.form.users.map(item => item.key);

      this.create({ resource: "team", data: form })
        .then(() => {
          this.showMessage("<b>Team</b> created successfuly.", "success");
        })
        .catch(error => {
          this.showMessage(error.message, "danger");
        })
        .finally(() => {
          e.target.disabled = false;
          this.stopLoading();
        });
    },

    /**
     * Update the current team with form data.
     */
    onSave(e) {
      this.startLoading();
      e.preventDefault();
      e.target.disabled = true;

      // validate

      // Set request body
      let form = {};
      form.name = this.form.name;
      form.users = this.form.users.map(item => item.id);

      this.update({ resource: "team", id: this.id, data: form })
        .then(() => {
          this.showMessage("<b>Team</b> updated successfuly.", "success");
        })
        .catch(error => {
          this.showMessage(error.message, "danger");
        })
        .finally(() => {
          e.target.disabled = false;
          this.stopLoading();
        });
    },

    onCancel(e) {
      e.preventDefault();
      this.$router.push({ name: "teamList" });
    }
  }
};
</script>

<style scoped>
</style>