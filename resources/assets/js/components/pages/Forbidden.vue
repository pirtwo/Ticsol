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
            <h3>Sorry, Access Denied</h3>
            <p class="text-muted text-justify">
              You don't have permissions to see this page, for more information contact 
              your adminstrator. Go back to  
              <router-link to="/home">
                Dashboard
              </router-link>.
            </p>
          </div> 
        </div>     
      </div>
    </div>
  </app-layout>  
</template>
<script>
export default {
  name: "Forbidden",

  methods:{}
}
</script>
<style scoped>

</style>
