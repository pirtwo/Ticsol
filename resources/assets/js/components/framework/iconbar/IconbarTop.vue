<template>
    <div class="toolbar-top">
        <a href="" class="btn btn-light" role="button" >
            <i class="icon material-icons">
                settings
            </i>
            <span class="caption">SETTINGS</span>
        </a>
        <a href="" class="btn btn-light" role="button" >
            <i class="icon material-icons">
                account_circle
            </i>
            <span class="caption">USER</span>
        </a>        
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