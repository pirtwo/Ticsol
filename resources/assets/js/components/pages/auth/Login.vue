<template>
  <div class="">
      <form method="POST" v-on:submit.prevent="onSubmit">
            <div class="form-group">
                <label>Username</label>
                <input type="email" placeholder="Enter username"/>                
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" placeholder="Enter password"/>
                <small class="text-muted">Never share your password in public.</small>
            </div>
            <div class="form-group">
                <input type="checkbox" data-role="checkbox" data-caption="Remember me">
            </div>
            <div class="form-group">
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
        body: [this.username, this.password]
      });
    }
  }
};
</script>
<style scoped>

</style>
