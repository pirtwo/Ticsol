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
                <small class="text-muted">Never share your password in public.</small>
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
import axios from "axios";
export default {
  name: "Login",
  data() {
    return {
      username: "",
      password: ""
    };
  },
  methods: {
    onSubmit() {
      axios.post("https://server.dev/api/login", {
        headers: {
            'Accept' : 'application/json'
        },
        body: { 'username' : this.username, 'password' : this.password}
      });
    }
  }
};
</script>
<style scoped>

</style>
