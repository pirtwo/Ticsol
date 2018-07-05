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
                <input  type="text" class="form-control" id="username" placeholder="Enter your username">
                </div>
            </div>

            <div class="form-group row">
                <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                    <i class="icon material-icons">email</i>
                    </div>
                </div>
                <input  type="email" class="form-control" id="email" placeholder="Enter your email">
                </div>
            </div> 

            <div class="form-group row">
                <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                    <i class="icon material-icons">lock</i>
                    </div>
                </div>
                <input  type="password" class="form-control" id="password" placeholder="Enter your password">
                </div>
            </div> 

            <div class="form-group row">
                <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                    <i class="icon material-icons">lock</i>
                    </div>
                </div>
                <input  type="password" class="form-control" id="confirm" placeholder="Confirm your password">
                </div>
            </div>                                 


            <div class="form-group">
                <captcha></captcha>
            </div>

            <button class="btn btn-primary btn-block">
                <i class="icon material-icons">done</i>  
                <span>REGISTER</span>
            </button> 
        </form>   

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
.btn span{
  font-size: 1.5rem;
  line-height: 1.3;
  vertical-align: top;
}

.btn i{
  font-size: 1.5rem;
  line-height: 1.5;
}
</style>