<template>
  <app-layout
    :header="false"
    :footer="false"
  >
    <div class="wrap-content d-flex justify-content-center align-items-center h-100">
      <div class="md-layout md-alignment-center-center">
        <div class="wrap-auth md-layout-item">
          <img
            src="/img/logo.svg"
            class="app-logo"
          >
          <div class="text-center">
            <h3>Welcome to Web.TicSol</h3>
            <div 
              v-show="loading" 
              class="py-3 text-muted d-flex align-items-center justify-content-center"
            >              
              <ts-spinner
                v-if="loading"
                :spinner-style="'border'"                
                :sr="message"
                class="spinner-border-sm"
              />
              <div class="ml-2">
                {{ message }}
              </div>
            </div>            
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { api } from "../../api/http";
import { mapGetters, mapActions } from "vuex";
import { setTimeout } from 'timers';

export default {
  name: "Index",

  data() {
    return {
      hash: "",
      error: "",
      message: "",
      loading: false
    };
  },

  beforeRouteEnter(to, from, next) {    
    next(vm => {
      vm.hash = to.hash;
      if (vm.hash) vm.resolveHash();
      else vm.requestAccessToken();
    });
  },

  computed:{
    ...mapGetters({
      authUrl: "getAuthUrl"
    })
  },

  methods: {
    ...mapActions({      
      extractToken: "user/extractToken",
      me: "user/me",
      successfulAuth: "user/successfulAuth",
      connectToChannels: "core/connectToChannels"
    }),

    requestAccessToken() {
      this.message = "redirecting to login page...";
      setTimeout(()=>{
        window.location.href = this.authUrl;
      }, 1000);      
    },

    async resolveHash() {
      this.loading = true;
      this.message = "resolving server response...";

      // extract the access token from the hash
      await this.extractToken(this.hash).catch(error => {
        console.log("Invalid token format.");
        return false;
      });

      this.message = "fetching your account details...";

      // test the token and fetch user information
      await this.me()
        .then(() => {
          this.message = "success, redirecting to dashboard...";

          // token is valid
          this.successfulAuth();

          // connect to channels
          this.connectToChannels();

          // redirect to dashboard
          this.$router.push({ name: "dash" });
        })
        .catch(error => {
          this.message = "can not fetch user information, try to refresh the page."
          console.log("Can not fetch user information.");
        });     

      this.loading = false;
    }
  }
};
</script>

<style scoped>
</style>
