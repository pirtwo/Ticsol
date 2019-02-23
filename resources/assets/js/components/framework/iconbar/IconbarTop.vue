<template>
  <div class="toolbar-top">
    <app-statusbar />
    
    <div class="dropdown">
      <button 
        class="btn btn-light" 
        type="buttom" 
        role="button" 
        id="dropdownMenuLink" 
        data-toggle="dropdown"
        aria-haspopup="true" 
        aria-expanded="false"
      >
        <i class="icon material-icons">
          settings
        </i>
        <span class="caption">SETTINGS</span>
      </button>
      <div 
        class="dropdown-menu dropdown-menu-right" 
        aria-labelledby="dropdownMenuLink"
      >
        <a 
          class="dropdown-item" 
          href="#"
        >Action</a>
        <a 
          class="dropdown-item" 
          href="#"
        >Another action</a>
        <a 
          class="dropdown-item" 
          href="#"
        >Something else here</a>
      </div>
    </div>

    <div class="dropdown">
      <button 
        class="btn btn-light" 
        type="buttom" 
        role="button" 
        id="dropdownMenuLink" 
        data-toggle="dropdown"
        aria-haspopup="true" 
        aria-expanded="false"
      >
        <img 
          class="user-avatar rounded" 
          :src="avatar" 
          alt="Avtar"
        >
        <span class="caption user-name">{{ userName }}</span>
      </button>
      <div 
        class="dropdown-menu dropdown-menu-right" 
        aria-labelledby="dropdownMenuLink"
      >
        <a 
          class="dropdown-item" 
          href="#" 
          @click.prevent="logoutHandler"
        >Logout</a>        
      </div>
    </div>
  </div>
</template>
<script>
import { mapActions, mapGetters } from "vuex";
import Statusbar from "../../framework/AppStatusbar.vue";

export default {
  name: "IconbarTop",

  components: {
    "app-statusbar": Statusbar
  },

  computed: {
    ...mapGetters({
      userName: "user/getUsername",
      avatar: "user/getAvatar"
    })
  },

  methods: {
    ...mapActions({ logout: "user/logout" }),
    logoutHandler() {
      this.logout()
        .then(() => {
          console.log("successful logout");
          this.$router.push("/logout");
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>

<style scoped>
.user-avatar {
  width: 40px;
  height: 40px;
}

.user-name {
  width: 90px;
  line-height: 1;
  overflow: hidden;
  text-overflow: ellipsis;
  font-size: 0.7rem !important;
  display: inline-block !important;
}

.dropdown {
  display: inline-block !important;
}
</style>