<template>
    <div class="toolbar-top">
        <div class="dropdown-button place-right">
            <button class="button shortcut">                
                <span class="caption">User</span>
                <span class="mif-user mif-2x"></span>
            </button>
            <ul class="d-menu place-right" data-role="dropdown">
                <li><a href="#">Profile</a></li>
                <li><a href="#">Change Password</a></li>                
                <li><a href="#" @click.prevent="logoutHandler">Logout</a></li>
            </ul>
        </div>
        <div class="dropdown-button place-right">
            <button class="button shortcut">                
                <span class="caption">Settings</span>
                <span class="tag">3000</span>
                <span class="mif-cog mif-2x"></span>
            </button>
            <ul class="d-menu place-right" data-role="dropdown">
                <li><a href="#">Reply</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </div>
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