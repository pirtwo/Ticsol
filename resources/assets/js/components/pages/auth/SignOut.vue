<template>
  <div class="md-layout md-alignment-center-center">
    <div class="wrap-auth md-layout-item">
      <img 
        src="/img/logo.svg" 
        class="app-logo"
      >
      <div class="text-center">
        <h3>You signed out successfuly</h3>
        <p class="text-muted">
          Hope to see you soon :D
        </p>
      </div> 
      <div class="my-2 d-flex justify-content-center">        
        <a
          class="btn btn-primary w-50 mr-2"
          href="/login"
        >Login</a>
        <a
          class="btn btn-primary w-50"
          href="/login"
        >Ticsol.com</a>
      </div>
    </div>     
  </div>
</template>
<script>
export default {
  name: "SignOut"
}
</script>
<style scoped>

</style>
