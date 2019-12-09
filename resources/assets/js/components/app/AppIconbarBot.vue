<template>
  <div class="toolbar-bot d-none d-md-block">
    <!-- Home -->
    <router-link
      :to="{ name : 'dash' }"
      class="btn"
      role="button"
    >
      <i class="icon material-icons">dashboard</i>
      <span class="caption">HOME</span>
    </router-link>

    <!-- Inbox -->
    <router-link
      :to="{ name : 'inbox' }"
      class="btn"
      role="button"
    >
      <i class="icon material-icons">inbox</i>
      <span class="caption">INBOX</span>
    </router-link>

    <!-- Requests -->
    <div class="btn-group dropup">
      <button
        type="button"
        class="btn"
        role="button"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
      >
        <i class="icon material-icons">message</i>
        <span class="caption">REQUESTS</span>
      </button>
      <div class="dropdown-menu">
        <router-link
          :to="{ name : 'leaveCreate'}"
          class="dropdown-item"
        >
          Leave
        </router-link>
        <router-link
          :to="{ name : 'reimbCreate'}"
          class="dropdown-item"
        >
          Reimbursement
        </router-link>
      </div>
    </div>

    <!-- Timesheets -->
    <router-link
      to="/timesheet"
      class="btn"
      role="button"
    >
      <i class="icon material-icons">timer</i>
      <span class="caption">TIMESHEETS</span>
    </router-link>

    <!-- Scheduler -->
    <router-link
      :to="{ name : 'scheduler' }"
      class="btn"
      role="button"
    >
      <i class="icon material-icons">calendar_today</i>
      <span class="caption">SCHEDULE</span>
    </router-link>

    <!-- more -->
    <div class="btn-group dropup">
      <button
        type="button"
        class="btn"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
      >
        <i class="icon material-icons">more_horiz</i>
        <span class="caption">MORE</span>
      </button>
      <div class="dropdown-menu">
        <!-- Job -->
        <router-link
          :to="{ name : 'jobList'}"
          class="dropdown-item"
        >
          <i class="icon material-icons">work</i>Jobs
        </router-link>

        <!-- Job Profile -->
        <router-link
          v-if="userCan('job_profile', ['full', 'list', 'create', 'update'])"
          :to="{ name : 'profileList'}"
          class="dropdown-item"
        >
          <i class="icon material-icons">assignment</i>Job Profiles
        </router-link>

        <!-- Users -->
        <router-link
          v-if="userCan('user', ['full', 'list', 'create', 'update'])"
          :to="{ name : 'userList'}"
          class="dropdown-item"
        >
          <i class="icon material-icons">person</i>Users
        </router-link>

        <!-- Roles -->
        <router-link
          v-if="userCan('role', ['full', 'list', 'create', 'update'])"
          :to="{ name : 'roleList'}"
          class="dropdown-item"
        >
          <i class="icon material-icons">verified_user</i>Roles
        </router-link>

        <!-- Team -->
        <router-link
          :to="{ name : 'teamList'}"
          class="dropdown-item"
        >
          <i class="icon material-icons">group</i>Teams
        </router-link>

        <!-- Contacts -->
        <router-link
          :to="{ name : 'contactList'}"
          class="dropdown-item"
        >
          <i class="icon material-icons">contact_mail</i>Contacts
        </router-link>

        <!-- Activities -->
        <router-link
          :to="{ name : 'activityList'}"
          class="dropdown-item"
        >
          <i class="icon material-icons">description</i>Activity Reports
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "AppIconbarTop",
  computed: {
    ...mapGetters({
      userCan: "user/can"
    })
  }
};
</script>

<style scoped>
.dropdown-item i {
  color: inherit !important;
  margin-right: 5px;
  vertical-align: top;
}
</style>