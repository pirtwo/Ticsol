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
  mounted() {
    // console.log(this.$store);
    // console.log(this.path);
    // console.log(this.$store.state.user.isAuth);
    // console.log(this.$store.state.user.counter);
    // console.log(this.userIsAuth);
    // console.log(this.userToken);
  },
  computed: {
    ...mapGetters({ isAuth : 'user/userIsAuth', path: 'user/routePath'}),
  },
  methods: {
    ...mapActions(["user/login"]),
    onSubmit() {
      this.$store
        .dispatch("user/login", {
          username: this.username,
          password: this.password
        })
        .then(fulfilled => {
          this.$router.push('/home');
        })
        .catch(reason => {
          console.log(reason);
        });
    }
  }
};
</script>
<style scoped>

</style>
