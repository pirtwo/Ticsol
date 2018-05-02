<template>
    <div class="cnt-register border-radius">
      <img src="/img/app.png" class="app-logo" />
      <form method="POST" v-on:submit.prevent="onSubmit">
            <div class="form-group">
                <label>Username</label>
                <input type="text" v-model="username" placeholder="Chose a username..."/>                
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" v-model="email" placeholder="Your email address..."/>                
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" v-model="password" placeholder="Enter password here..."/>
                <small class="text-muted">Chose a strong password.</small>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" v-model="password" placeholder="Confirm your password here..."/>                
            </div>           
            <div class="form-group text-center">
                <button class="button success">Register</button>                
            </div>
      </form>
  </div>
</template>
<script>
export default {
  
}
</script>
<style scoped>

</style>
