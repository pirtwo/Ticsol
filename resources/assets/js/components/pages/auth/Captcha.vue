<template>
  <div>
    <div class='g-recaptcha' id='g-recaptcha'></div>    
  </div>
</template>
<script>
export default {
  props: {
    theme: String,
    siteKey: String
  },
  data() {
    return {
      reCaptchaUrl:
        "https://www.google.com/recaptcha/api.js?onload=vueRecaptchaInit&render=explicit"
    };
  },
  created() {

    // Add script to body
    window.recaptchaLoaded = new Promise(resolve => {
      window.vueRecaptchaInit = resolve;
    });
    let reCaptcha = document.createElement("script");
    reCaptcha.type = "text/javascript";
    reCaptcha.src = this.reCaptchaUrl;
    reCaptcha.async = "true";
    reCaptcha.defer = "true";
    document.body.appendChild(reCaptcha);
    
    // Onload event handler
    window.recaptchaLoaded.then(() => {
      window.grecaptcha.render("g-recaptcha", {
        sitekey: "6LerNFcUAAAAAOZZMkOgpTyrUt-_zDG88MlLta7I",
        theme: "light"
      });
    });

  }
};
</script>
<style scoped>

</style>
