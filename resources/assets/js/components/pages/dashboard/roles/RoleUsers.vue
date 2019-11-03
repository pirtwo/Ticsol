<template>
  <app-main
    :scrollbar="true"
    :loading="isLoading"
    padding="p-5"
  >
    <template slot="toolbar" />

    <template slot="drawer">
      <ul class="v-menu">
        <li class="menu-title">
          Actions
        </li>
        <li>
          <button
            class="btn"
            @click="onSave"
          >
            Save
          </button>
        </li>
        <li>
          <button
            class="btn"
            @click="onCancel"
          >
            Cancel
          </button>
        </li>
        <li class="menu-title">
          Links
        </li>
        <li>
          <router-link :to="{ name: 'roleList' }">
            Roles
          </router-link>
          <router-link :to="{ name : 'roleDetails', params : { id: this.id } }">
            Permissions
          </router-link>
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
                    placeholder="role name"
                    readonly
                  >
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="row">
                <label class="col-sm-2 col-form-label">Users</label>
                <div class="col-sm-10">
                  <ts-select
                    v-model="form.users"
                    :data="userList"
                    :multi="true"
                    :placeholder="'Please select users...'"
                  >
                    <template slot-scope="{ item }">
                      <img
                        :src="item.pic"
                        :alt="`${item.value}-avatar`"
                        class="rounded"
                        width="30"
                        height="30"
                      >
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
          :data="roleUsers"
          :header="header"
          :selection="false"
          order-by="title"
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
                :to="{ name : 'userProfile', params : { id: item.key } }"
              >
                <i class="material-icons">visibility</i>
              </router-link>
            </td>
            <td>
              <img
                :src="item.pic"
                :alt="`${item.fullname} profile pic`"
                width="40"
                height="40"
              >
              {{ item.value }}
            </td>
            <td>
              <router-link
                v-for="role in item.roles"
                :key="role.id"
                class="btn btn-sm btn-link"
                :to="{ name : 'roleDetails', params : { id: role.id } }"
              >
                {{ role.name }}
              </router-link>
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
  name: "RoleUsers",

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
        { value: "Roles", orderBy: "roles" }
      ]
    };
  },

  watch: {
    role: function(val) {
      this.populateForm(val);
    }
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    /**
     * return current role.
     */
    role: function() {
      return this.getList("role")[0];
    },

    userList: function() {
      return this.getList("user").map(item => {
        return {
          key: item.id,
          value: item.fullname,
          pic: item.avatar,
          roles: item.roles
        };
      });
    },

    roleUsers: function() {
      return this.form.users;
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
    this.clear("role");
    next();
  },

  methods: {
    ...mapActions({
      show: "resource/show",
      list: "resource/list",
      update: "resource/update",      
      clear: "resource/clearResource",
    }),

    async loadAssets() {
      this.startLoading();
      let p1 = await this.list({ resource: "user", query: { with: "roles" } });
      let p2 = !this.id
        ? new Promise(resolve => resolve())
        : this.list({
            resource: "role",
            query: { eq: `id,${this.id}`, with: "users.roles" }
          });

      Promise.all([p1, p2]).then(() => {
        this.stopLoading();
      });
    },

    populateForm(role) {
      this.form.name = role.name;
      this.form.users = role.users.map(item => {
        return {
          key: item.id,
          value: item.fullname,
          pic: item.avatar,
          roles: item.roles
        };
      });
    },

    getRoles(item) {
      if (item.roles.length !== 0)
        return item.roles.reduce(
          (a, c, i) => a + (i == 0 ? "" : ",") + c.name,
          ""
        );
      else return "None";
    },

    onSave(e) {
      e.target.disabled = true;

      this.update({
        resource: "role",
        id: this.id,
        data: {
          name: this.form.name,
          users: this.form.users.map(item => item.key)
        }
      })
        .then(respond => {
          e.target.disabled = false;
          console.log("role updated successfuly");
          this.showMessage(`<b>${this.form.name}</b> updated successfuly.`, "success");
        })
        .catch(error => {
          console.log(error);
          this.showMessage(error.message, "danger");
          e.target.disabled = false;
        });
      e.preventDefault();
    },

    onCancel(e) {
      this.$router.push({ name: "roleList" });
      e.preventDefault();
    }
  }
};
</script>

<style scoped>
</style>