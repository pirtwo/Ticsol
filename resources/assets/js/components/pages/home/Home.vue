<template>
    <div class="row">
        <header class="col-xs-12">
            <iconbar-top></iconbar-top>
        </header>
        <main class="col-xs-12">
            <div class="main-menu row">
                <div class="col-md-12">
                    <img src="/img/app.png" class="app-logo" />
                    <router-link :to="{ name: 'dash'}" class="shortcut" role="button">
                        <span class="tag"></span>
                        <span class="caption">DASHBOARD</span>
                        <span class="mif-dashboard mif-5x"></span>
                    </router-link>
                    <router-link :to="{ name:'inbox' }" class="shortcut" role="button">
                        <span class="tag">14</span>
                        <span class="caption">INBOX</span>
                        <span class="mif-envelop mif-5x"></span>
                    </router-link>
                    <router-link :to="{ name: 'hr' }" class="shortcut" role="button">
                        <span class="tag"></span>
                        <span class="caption">H.R</span>
                        <span class="mif-users mif-5x"></span>
                    </router-link>
                    <router-link :to="{ name: 'requests' }" class="shortcut" role="button">
                        <span class="tag">3</span>
                        <span class="caption">REQUESTS</span>
                        <span class="mif-user-plus mif-5x"></span>
                    </router-link>
                    <router-link :to="{ name: 'timesheets' }" class="shortcut" role="button">
                        <span class="tag"></span>
                        <span class="caption">TIMESHEETS</span>
                        <span class="mif-alarm mif-5x"></span>
                    </router-link>
                    <router-link :to="{ name: 'schedule' }" class="shortcut" role="button">
                        <span class="tag">2</span>
                        <span class="caption">SCHEDULE</span>
                        <span class="mif-calendar mif-5x"></span>
                    </router-link>
                    <router-link to="" class="shortcut" role="button">
                        <span class="tag"></span>
                        <span class="caption">SETTINGS</span>
                        <span class="mif-cog mif-5x"></span>
                    </router-link>
                </div>
            </div>
        </main>
        <footer class="col-xs-12">
            <iconbar-bot></iconbar-bot>
        </footer>
    </div>
</template>

<script>
import IconbarTop from "../../framework/iconbar/IconbarTop.vue";
export default {
  components: {
    "iconbar-top": IconbarTop
  }
};
</script>

<style scoped>

</style>