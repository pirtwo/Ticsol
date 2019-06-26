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
          src="/img/app.png" 
          class="app-logo"
        >
        <router-link 
          :to="{ name : 'inbox'}" 
          class="btn" 
          role="button"
        >
          <i class="icon material-icons">
            inbox
          </i>
          <span class="caption">INBOX</span>
        </router-link>
        <router-link 
          :to="{ name : 'jobList'}" 
          class="btn" 
          role="button"
        >
          <i class="icon material-icons">
            work
          </i>
          <span class="caption">Jobs</span>
        </router-link>
        <router-link 
          :to="{ name : 'leaveCreate'}" 
          class="btn" 
          role="button"
        >
          <i class="icon material-icons">
            message
          </i>
          <span class="caption">REQUESTS</span>
        </router-link>
        <router-link 
          :to="{ name : 'timesheetCreate'}" 
          class="btn" 
          role="button"
        >
          <i class="icon material-icons">
            timer
          </i>
          <span class="caption">TIMESHEETS</span>
        </router-link>
        <router-link 
          :to="{ name : 'schedule'}" 
          class="btn" 
          role="button"
        >
          <i class="icon material-icons">
            calendar_today
          </i>
          <span class="caption">SCHEDULE</span>
        </router-link>
      </div>
    </div>
  </app-layout>
</template>

<script>
export default {
  name: "Home",
  components: {
    
  }
};
</script>

<style scoped>
.wrap-dashboard{
    height: 80%;
}
</style>