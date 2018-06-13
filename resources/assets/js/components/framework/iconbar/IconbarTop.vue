<template>
    <div class="toolbar-top md-layout md-alignment-top-right">

        <md-menu md-size="small" md-align-trigger>
            <md-button class="md-plain" md-menu-trigger>
                <md-icon>settings</md-icon>
                <span class="caption">Settings</span>
            </md-button>
            <md-menu-content>
                <md-menu-item>My Item 1</md-menu-item>
                <md-menu-item>My Item 2</md-menu-item>
                <md-menu-item>My Item 3</md-menu-item>
            </md-menu-content>
        </md-menu>

        <md-menu md-size="small" md-align-trigger>
            <md-button class="md-plain" md-menu-trigger>
                <md-icon>account_circle</md-icon>
                <span class="caption">User</span>
            </md-button>
            <md-menu-content>
                <md-menu-item>Profile</md-menu-item>
                <md-menu-item>Info</md-menu-item>
                <md-menu-item>Logout</md-menu-item>
            </md-menu-content>
        </md-menu>       

    </div>
</template>
<script>
import { mapActions } from "vuex";
export default {
  name: "IconbarTop",
  methods: {
    ...mapActions(["auth/logout", "loading/start", "loading/stop"]),
    logoutHandler() {
      this.$store.dispatch("loading/start", { message: "Logout..." });
      this.$store
        .dispatch("auth/logout")
        .then(done => {
          console.log(done);
          this.$router.push('/logout');
          this.$store.dispatch("loading/stop", { message: "success" });
        })
        .catch(error => {
          console.log(error);
          this.$store.dispatch("loading/stop", {
            message: "Logout Failed!!!"
          });
        });
    }
  }
};
</script>
<style scoped>
</style>