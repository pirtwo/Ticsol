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
    </template>

    <template slot="drawer">
      <ul class="v-menu">
        <li class="menu-title">
          Actions
        </li>
        <li>
          <button
            class="btn"
            @click="inviteModal = true"
          >
            Invite Users
          </button>
        </li>
        <li class="menu-title">
          Links
        </li>
      </ul>
    </template>

    <template slot="content">
      <ts-table
        class="table table-striped"
        :data="users"
        :header="header"
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
              :to="{ name : 'userProfile', params : { id: item.id } }"
            >
              <i class="material-icons">visibility</i>
            </router-link>
          </td>
          <td>
            <img
              :src="item.avatar"
              alt="user-avatar"
              width="40"
              height="40"
            >
            {{ item.fullname }}
          </td>
          <td>
            <span
              v-for="role in item.roles"
              :key="role.id"
            >
              <router-link
                class="btn btn-sm btn-link"
                :to="{ name : 'roleDetails', params : { id: role.id } }"
              >{{ role.name }}</router-link>
            </span>
          </td>
          <td>
            <span
              v-for="team in item.teams"
              :key="team.id"
            >
              <router-link
                class="btn btn-sm btn-link"
                :to="{ name : 'teamDetails', params : { id: team.id } }"
              >{{ team.name }}</router-link>
            </span>
          </td>
          <td>
            <span
              v-for="contact in item.contacts"
              :key="contact.id"
            >
              <router-link
                class="btn btn-sm btn-link"
                :to="{ name : 'contactDetails', params : { id: contact.id } }"
              >{{ `${contact.firstname} ${contact.lastname}` }}</router-link>
            </span>
          </td>
        </template>
      </ts-table>

      <!-- ADD NEW USER -->
      <ts-modal
        title="Invite Users"
        :show.sync="inviteModal"
      >
        <p class="">
          Please provide the details of any employees you would like to invite. The new accounts
          will be created immediately and account credentials will be sent to account owner by email.
        </p>
        <ts-grid
          v-model="invites"
          :columns="[{key: 0, value:'First Name'}, {key: 1, value:'Last Name'}, {key: 2, value:'E-Mail'}]"
          :has-toolbar="false"
        >
          <template slot-scope="{ item }">
            <td>{{ item.firstname }}</td>
            <td>{{ item.lastname }}</td>
            <td>{{ item.email }}</td>
          </template>
          <template
            slot="grid-modal"
            slot-scope="{ item }"
          >
            <!-- firstname -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">
                  First Name
                  <i class="field-required">*</i>
                </label>
                <div class="col-sm-9">
                  <input
                    v-model="item.firstname"
                    type="text"
                    placeholder="user first name"
                    class="form-control"
                    id="firstname"
                  >
                </div>
              </div>
            </div>

            <!-- lastname -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">
                  Last Name
                  <i class="field-required">*</i>
                </label>
                <div class="col-sm-9">
                  <input
                    v-model="item.lastname"
                    type="text"
                    placeholder="user last name"
                    class="form-control"
                    id="lastname"
                  >
                </div>
              </div>
            </div>

            <!-- e-mail -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">
                  E-Mail
                  <i class="field-required">*</i>
                </label>
                <div class="col-sm-9">
                  <input
                    v-model="item.email"
                    type="text"
                    placeholder="user e-mail"
                    class="form-control"
                    id="email"
                  >
                </div>
              </div>
            </div>

            <span class="d-none">{{ item.teams = Array.isArray(item.teams) ? item.teams : [] }}</span>
            <span class="d-none">{{ item.roles = Array.isArray(item.roles) ? item.roles : [] }}</span>

            <!-- teams -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">Teams</label>
                <div class="col-sm-9">
                  <ts-select
                    class="form-control"
                    v-model="item.teams"
                    :data="teams"
                    :multi="true"
                    id="userTeams"
                    name="userTeams"
                    placeholder="user teams"
                    search-placeholder="search..."
                  />
                </div>
              </div>
            </div>

            <!-- roles -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-sm-3 col-form-lable">Roles</label>
                <div class="col-sm-9">
                  <ts-select
                    class="form-control"
                    v-model="item.roles"
                    :data="roles"
                    :multi="true"
                    id="userRoles"
                    name="userRoles"
                    placeholder="user roles"
                    search-placeholder="search..."
                  />
                </div>
              </div>
            </div>
          </template>
        </ts-grid>
        <template slot="footer">
          <button
            type="button"
            class="btn btn-light"
            @click="inviteModal = false"
          >
            Cancel
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="createUsers"
          >
            Invite
          </button>
        </template>
      </ts-modal>
    </template>
  </app-main>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import { required, email } from "vuelidate/lib/validators";
import pageMixin from "../../../../mixins/page-mixin";

export default {
  name: "UserList",

  mixins: [pageMixin],

  props: ["col", "opt", "val"],

  data() {
    return {
      invites: [],
      inviteModal: false,
      pager: {
        page: 1,
        perPage: 10,
        pageCount: 10
      },
      header: [
        { value: "", orderBy: "" },
        { value: "Name", orderBy: "fullname" },
        { value: "Roles", orderBy: "roles" },
        { value: "Teams", orderBy: "team" },
        { value: "Contacts", orderBy: "contact" }
      ]
    };
  },

  validations: {
    invites: {
      $each: {
        firstname: { required },
        lastname: { required },
        email: { required, email }
      }
    }
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    users: function() {
      return this.getList("user");
    },

    roles: function() {
      return this.getList("role").map(item => {
        return { key: item.id, value: item.name };
      });
    },

    teams: function() {
      return this.getList("team").map(item => {
        return { key: item.id, value: item.name };
      });
    }
  },

  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.loadAssets();
      vm.feedTable();
    });
  },

  beforeRouteLeave(to, from, next) {
    this.clear("user");
    this.clear("role");
    this.clear("team");
    next();
  },

  methods: {
    ...mapActions({
      list: "resource/list",
      create: "resource/create",
      clear: "resource/clearResource"
    }),

    loadAssets() {
      this.startLoading();
      let p1 = this.list({ resource: "role" });
      let p2 = this.list({ resource: "team" });

      Promise.all([p1, p2]).then(() => {
        this.stopLoading();
      });
    },

    feedTable() {
      this.startLoading();
      this.list({
        resource: "user",
        query: {
          with: "roles,teams,contacts",
          page: this.pager.page,
          perPage: this.pager.perPage,
          [this.opt]: `${this.col},${this.val}`
        }
      })
        .then(respond => {
          this.pager.pageCount = respond.last_page;
          this.stopLoading();
        })
        .catch(error => {
          console.log(error);
          this.stopLoading();
        });
    },

    async createUsers(e) {
      e.preventDefault();
      e.target.disabled = true;

      // validate the list
      this.$v.$touch();
      if (this.$v.$invalid) {
        e.target.disabled = false;
        return;
      }

      for (let i = 0; i < this.invites.length; i++) {
        let payload = {};
        payload.firstname = this.invites[i].firstname;
        payload.lastname = this.invites[i].lastname;
        payload.email = this.invites[i].email;
        payload.teams = this.invites[i].teams.map(item => item.key);
        payload.roles = this.invites[i].roles.map(item => item.key);
        await this.create({ resource: "user", data: payload })
        .then(()=>{
          this.showMessage(`<b>${payload.firstname + ' ' + payload.lastname}</b> invited successfuly.`, "success");
        })
        .catch(err => {
          this.showMessage(err.message, "danger");
        });
      }

      e.target.disabled = false;
      this.inviteModal = false;      
    }
  }
};
</script>

<style scoped>
</style>