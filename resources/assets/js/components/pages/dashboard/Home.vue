<template>
    <page-layout v-bind:header='true' v-bind:footer='false'>
        <template slot="header">
            <iconbar-top></iconbar-top>
        </template>
        <div class="main-menu border-radius row">
            <div class="cell-md-12">
                <img src="/img/app.png" class="app-logo" />                
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
                <router-link :to="{ name: 'jobs' }" class="shortcut" role="button">
                    <span class="mif-location-city mif-5x"></span>
                    <span class="tag">3</span>
                    <span class="caption">Jobs</span>
                </router-link>
            </div>
        </div>
    </page-layout>
</template>

<script>
import PageLayout from "../../framework/PageLayout.vue";
import IconbarTop from "../../framework/iconbar/IconbarTop.vue";
export default {
  name: "Home",
  components: {
    "page-layout": PageLayout,
    "iconbar-top": IconbarTop
  }
};
</script>

<style>

</style>