<template>
  <div class="cnt-login border-radius">
      <img src="/img/app.png" class="app-logo" />
      <form method="POST" v-on:submit.prevent="onSubmit">
            <div class="form-group">
                <label>Username</label>
                <input type="text" v-model="username" placeholder="Enter username here..."/>                
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" v-model="password" placeholder="Enter password here..."/>
                <small class="text-muted">Keep your password secret.</small>
            </div>
            <div class="form-group text-center">
                <router-link :to="{ name : 'register' }">Create account</router-link> <br/>
                <router-link :to="{ name : 'resetpassword' }">Forgot my password...</router-link>                
            </div>
            <div class="form-group text-center">
                <button class="button success">Login</button>                
            </div>
      </form>
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
