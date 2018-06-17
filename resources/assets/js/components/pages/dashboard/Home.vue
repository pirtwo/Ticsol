<template>
    <page-layout v-bind:header='true' v-bind:footer='false'>
        <template slot="header">
            <iconbar-top></iconbar-top>
        </template>
        <div class="md-layout md-alignment-center-center">            
            <div class="wrap-dashboard md-layout-item">

                <img src="/img/app.png" class="app-logo" />

                <md-button class="md-plain" :to="{ name : 'inbox'}">
                    <md-icon>inbox</md-icon>
                    <span class="caption">inbox</span>
                </md-button>

                <md-button class="md-plain" :to="{ name : 'requests'}">
                    <md-icon>message</md-icon>
                    <span class="caption">Requests</span>
                </md-button>

                <md-button class="md-plain" :to="{ name : 'timesheets'}">
                    <md-icon>timer</md-icon>
                    <span class="caption">timesheets</span>
                </md-button>

                <md-button class="md-plain" :to="{ name : 'scheduler'}">
                    <md-icon>calendar_today</md-icon>
                    <span class="caption">schedules</span>
                </md-button>

                <md-button class="md-plain" :to="{ name : 'jobs'}">
                    <md-icon>business_center</md-icon>
                    <span class="caption">jobs</span>
                </md-button>

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

<style scoped>
.md-layout{
    height: 100% !important;
}
</style>