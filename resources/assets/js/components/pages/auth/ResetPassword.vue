<template>    
    <div class="wrap-auth md-layout-item">
        <img src="/img/app.png" class="app-logo" />

        <md-field md-clearable>
            <label>Username</label>
            <md-input v-model="username"></md-input>          
        </md-field>

        <md-field md-clearable>
            <label>Email</label>
            <md-input v-model="username"></md-input>          
        </md-field>                                   

        <div class="md-layout md-alignment-center-center">
            <captcha></captcha>
        </div>

        <div class="md-layout md-alignment-center-center">          
            <md-button class="md-raised">reset password</md-button>
        </div>
    </div>   
</template>

<script>
import Captcha from "../auth/Captcha.vue";
export default {
  components: {
    captcha: Captcha
  }
}
</script>

<style scoped>
</style>