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
            <h3>You logged out successfuly</h3>
            <p class="text-muted">
              Hope to see you soon :D
            </p>
          </div> 
        </div>     
      </div>
    </div>
  </app-layout>  
</template>
<script>
export default {
  name: "LoggedOut",

  methods:{}
}
</script>
<style scoped>

</style>
