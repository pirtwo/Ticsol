<template>
  <div class="wrap-auth shadow-sm">
    <img 
      src="/img/app.png" 
      class="app-logo"
    >

    <form>
      <div 
        v-show="haveError" 
        class="alert alert-danger" 
        role="alert"
      >
        {{ errorMsg }}
      </div>

      <div class="form-group row">
        <div class="col-sm-12">
          <div class="input-group">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <i class="icon material-icons">account_circle</i>
              </div>
            </div>
            <input 
              v-model="form.username" 
              type="text" 
              class="form-control" 
              id="username" 
              placeholder="Enter your username"
            >
          </div>
        </div>
      </div>

      <div class="form-group row">
        <div class="col-sm-12">
          <div class="input-group">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <i class="icon material-icons">lock_open</i>
              </div>
            </div>
            <input 
              v-model="form.password" 
              type="password" 
              class="form-control" 
              id="password" 
              placeholder="Enter your password"
            >
          </div>
        </div>
      </div>

      <div class="form-group text-center">  
        <a 
          href="/resetpassword" 
          class="btn btn-link d-block mx-auto"
        >Forgot Password</a>                        
      </div>

      <button 
        type="button" 
        class="btn btn-primary btn-block" 
        @click="onSubmit"
      >
        <i class="icon material-icons">exit_to_app</i>  
        <span>LOGIN</span>
      </button>    
    </form>
  </div>
</template>

<script>
import Echo from "laravel-echo";
import { mapGetters, mapActions } from "vuex";
export default {
  name: "Login",
  data() {
    return {
      haveError: false,
      errorMsg: "",
      form: {
        username: "",
        password: ""
      }
    };
  },
  methods: {
    ...mapActions({
      login: "user/login",
      logout: "user/logout",
      info: "user/info"
    }),
    onSubmit() {
      this.haveError = false;
      this.login(this.form)
        .then(respond => {
          console.log("login success");
          this.info().then(data => {
            console.log("info success");           
            this.$router.push("/home");
          });
        })
        .catch(error => {
          this.haveError = true;
          this.errorMsg = error.response.data.message;
          console.log("login error");
          console.log(error.response);
          this.$formFeedback(error.response.data.errors);
        });
    }
  }
};
</script>

<style scoped>
.btn span {
  font-size: 1.5rem;
  line-height: 1.35;
  vertical-align: top;
}

.btn i {
  font-size: 1.5rem;
  line-height: 1.5;
}
</style>