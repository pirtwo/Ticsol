<template>
  <div class="wrap-auth md-layout-item">
    <img src="/img/app.png" class="app-logo" />

    <md-field md-clearable>
      <label>Username</label>
      <md-input v-model="username"></md-input>
    </md-field>

    <md-field>
      <label>Password</label>
      <md-input v-model="password" type="password"></md-input>
    </md-field>

    <div class="md-layout md-alignment-center-center">
      <md-button href="/ResetPassword">Forgot my password ...</md-button>
    </div>

    <div class="md-layout md-alignment-center-center">
      <md-button class="md-raised">Login</md-button>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  name: "Login",
  data() {
    return {
      username: "",
      password: ""
    };
  },
  methods: {
    ...mapActions(["auth/login", "loading/start", "loading/stop"]),
    onSubmit() {
      this.$store.dispatch("loading/start", { message: "Login..." });
      this.$store
        .dispatch("auth/login", {
          username: this.username,
          password: this.password
        })
        .then(fulfilled => {
          this.$router.push("/dash");
          this.$store.dispatch("loading/stop", { message: "redirecting..." });
        })
        .catch(reason => {
          console.log(reason);
          this.$store.dispatch("loading/stop", { message: "error..." });
        });
    }
  }
};
</script>

<style scoped>
</style>