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
            <h3>Whoops, page not found</h3>
            <p class="text-muted">
              We didn't find the page. Go to  
              <router-link to="/home">
                home
              </router-link> or
              <button
                class="btn btn-link"
                type="button"
                @click="goBack"
              >
                back
              </button>
            </p>
          </div> 
        </div>     
      </div>
    </div>
  </app-layout>  
</template>
<script>
export default {
  name: "NotFound",

  methods:{
    goBack(){
      this.$router.go(-1);
    }
  }
}
</script>
<style scoped>

</style>
