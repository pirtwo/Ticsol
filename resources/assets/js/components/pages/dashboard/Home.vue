<template>
  <app-layout
    :header="true"
    :footer="false"
  >
    <template slot="header">
      <iconbar-top />
    </template>
    <div class="wrap-dashboard d-flex justify-content-center align-items-center">
      <div class="dashboard shadow-sm">
        <img
          src="/img/logo.svg"
          class="app-logo"
        >
        <router-link
          :to="{ name : 'inbox'}"
          class="btn"
          role="button"
        >
          <i class="icon material-icons">inbox</i>
          <span class="caption">INBOX</span>
        </router-link>
        <router-link
          :to="{ name : 'leaveCreate'}"
          class="btn"
          role="button"
        >
          <i class="icon material-icons">message</i>
          <span class="caption">REQUESTS</span>
        </router-link>
        <router-link
          :to="{ name : 'timesheetCreate'}"
          class="btn"
          role="button"
        >
          <i class="icon material-icons">timer</i>
          <span class="caption">TIMESHEETS</span>
        </router-link>
        <router-link
          :to="{ name : 'schedule'}"
          class="btn"
          role="button"
        >
          <i class="icon material-icons">calendar_today</i>
          <span class="caption">SCHEDULE</span>
        </router-link>
        <router-link
          :to="{ name : 'jobList'}"
          class="btn"
          role="button"
        >
          <i class="icon material-icons">work</i>
          <span class="caption">Jobs</span>
        </router-link>

        <div class="d-flex w-100 mt-5">
          <div class="ml-auto">
            <router-link
              :to="{ name : 'userWizard'}"
              class="btn btn-link"
              role="button"
            >
              Setup My Account
            </router-link>
            <router-link
              v-show="isOwner"
              :to="{ name : 'clientWizard'}"
              class="btn btn-link"
              role="button"
            >
              Setup My Company
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  name: "Home",
  computed: {
    ...mapGetters({
      isOwner: "user/isOwner"
    })
  }
};
</script>

<style scoped>
.wrap-dashboard {
  height: 100%;
}
</style>