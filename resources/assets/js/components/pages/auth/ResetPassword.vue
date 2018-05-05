<template>
<div class="cnt-resetPassword border-radius">
      <img src="/img/app.png" class="app-logo" />
      <form method="POST" v-on:submit.prevent="onSubmit">
            <div class="form-group">
                <label>Username</label>
                <input type="text" placeholder="Enter username here..."/>                
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" placeholder="Enter your email address..."/>                
            </div>  
            <div class="form-group">
                <captcha></captcha>             
            </div>  
            <div class="form-group text-center">
                <button class="button success">Send Reset Key</button>    
                <router-link class="button" :to="{ name : 'login' }">Cancel</router-link>            
            </div>
      </form>
</div>
</template>
<script>
import Captcha from '../auth/Captcha.vue'
export default {
  components:{
      'captcha' : Captcha,
  }
}
</script>
<style scoped>

</style>
