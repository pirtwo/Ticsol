<template>
  <div class="wrap-auth shadow-sm">
    <img src="/img/app.png" class="app-logo" />

    <form>

      <div class="form-group row">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text">
              <i class="icon material-icons">account_circle</i>
            </div>
          </div>
          <input v-model="form.username" type="text" class="form-control" id="username" placeholder="Enter your username">
        </div>
      </div>

      <div class="form-group row">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text">
              <i class="icon material-icons">lock_open</i>
            </div>
          </div>
          <input v-model="form.password" type="password" class="form-control" id="password" placeholder="Enter your password">
        </div>
      </div>

      <div class="form-group text-center">  
          <a href="/resetpassword" class="btn btn-link d-block mx-auto">Forgot Password</a>                        
      </div>

      <button class="btn btn-primary btn-block">
          <i class="icon material-icons">exit_to_app</i>  
          <span>LOGIN</span>
      </button>    
    </form>
    
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  name: "Login",
  data() {
    return {
      form:{
        username: "",
        password: ""
      }      
    };
  },
  methods: {
    ...mapActions({
      login: "user/login"
    }),
    onSubmit() {
      this.$store.dispatch("loading/start", { message: "Login..." });
      this.$store
        .dispatch("auth/login", {
          username: this.form.username,
          password: this.form.password
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
.btn span{
  font-size: 1.5rem;
  line-height: 1.35;
  vertical-align: top;
}

.btn i{
  font-size: 1.5rem;
  line-height: 1.5;
}
</style>