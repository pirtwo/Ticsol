<template>
  <nav-view 
    :scrollbar="true" 
    :loading="loading" 
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
            class="btn btn-light" 
            @click="onSave"
          >
            New
          </button>
        </li>
        <li>
          <button 
            class="btn btn-light" 
            @click="onSave"
          >
            Save
          </button>
        </li>
        <li>
          <button 
            class="btn btn-light" 
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
            Role Maintenance
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
                  <vb-select 
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
                  </vb-select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <table-view 
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
                class="btn btn-sm btn-light" 
                :to="{ name : 'userDetails', params : { id: item.id } }"
              >
                <i class="material-icons">visibility</i>
              </router-link>
            </td>
            <td>{{ item.name }}</td>
            <td>
              <router-link 
                v-for="item in item.roles" 
                :key="item.id"
                class="btn btn-sm btn-link" 
                :to="{ name : 'roleDetails', params : { id: item.id } }"
              >
                {{ item.name }}
              </router-link>
            </td>
          </template>
        </table-view>
      </form>
    </template>
  </nav-view>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import NavView from "../../../framework/NavView.vue";
import TableView from "../../../framework/BaseTable.vue";

export default {
  name: "RoleUsers",

  components: {
    "nav-view": NavView,
    "table-view": TableView
  },

  props: ["id"],

  data() {
    return {
      currentRole: null,
      form: {
        name: "",
        users: []
      },
      header: [
        { value: "", orderBy: "" },
        { value: "Name", orderBy: "name" },
        { value: "Roles", orderBy: "roles" }
      ],
      loading: false
    };
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    userList: function() {
      return this.getList("user").map(item => {
        return { key: item.id, value: item.name, pic: item.meta.avatar };
      });
    },

    roleUsers: function() {
      return this.getList("user").filter(
        item =>
          item.roles !== undefined
            ? item.roles.find(role => role.id == this.id) != null
            : false
      );
    }
  },

  mounted() {
    this.loading = true;
    this.fetch({ resource: "user", query: { with: "roles" } }).then(() => {
      this.show({
        resource: "role",
        id: this.id,
        query: { with: "users" }
      }).then(data => {
        this.loading = false;
        this.currentRole = data;
        this.form.name = this.currentRole.name;
        this.form.users = this.currentRole.users.map(item => {
          return { key: item.id, value: item.name };
        });
      });
    });
  },

  methods: {
    ...mapActions({
      show: "resource/show",
      fetch: "resource/list",
      clear: "resource/show",
      update: "resource/update"
    }),

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
          users: this.form.users.map(item => item.key)
        }
      })
        .then(respond => {
          e.target.disabled = false;
          console.log("role updated successfuly");
          this.$router.push({ name: "roleList" });
        })
        .catch(error => {
          console.log(error.response);
          e.target.disabled = false;
          this.$formFeedback(error.response.data.errors);
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