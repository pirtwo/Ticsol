<template>
    <page-layout :header='true' :footer='false'>
        <template slot="header">
            <iconbar-top></iconbar-top>
        </template>
        <div class="wrap-dashboard d-flex justify-content-center align-items-center">            
            <div class="dashboard shadow-sm">

                <img src="/img/app.png" class="app-logo" />

                <router-link :to="{ name : 'inbox'}" class="btn btn-light" role="button" >
                    <i class="icon material-icons">
                        inbox
                    </i>
                    <span class="caption">INBOX</span>
                </router-link>
                <router-link :to="{ name : 'request'}" class="btn btn-light" role="button" >
                    <i class="icon material-icons">
                        message
                    </i>
                    <span class="caption">REQUESTS</span>
                </router-link>
                <router-link :to="{ name : 'timesheet'}" class="btn btn-light" role="button" >
                    <i class="icon material-icons">
                        timer
                    </i>
                    <span class="caption">TIMESHEETS</span>
                </router-link>
                <router-link :to="{ name : 'schedule'}" class="btn btn-light" role="button" >
                    <i class="icon material-icons">
                        calendar_today
                    </i>
                    <span class="caption">SCHEDULE</span>
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

<style scoped>
.wrap-dashboard{
    height: 80%;
}
</style>