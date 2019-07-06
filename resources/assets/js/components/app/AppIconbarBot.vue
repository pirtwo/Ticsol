<template>
  <div class="toolbar-bot">
    <router-link 
      :to="{ name : 'dash' }" 
      class="btn" 
      role="button"
    >
      <i class="icon material-icons">
        dashboard
      </i>
      <span class="caption">HOME</span>
    </router-link>

    <router-link 
      :to="{ name : 'inbox' }" 
      class="btn" 
      role="button"
    >
      <i class="icon material-icons">
        inbox
      </i>
      <span class="caption">INBOX</span>
    </router-link>

    <div class="btn-group dropup">
      <button 
        type="button" 
        class="btn" 
        data-toggle="dropdown" 
        aria-haspopup="true" 
        aria-expanded="false"
      >
        <i class="icon material-icons">
          message
        </i>
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

    <router-link 
      :to="{ name : 'timesheet' }" 
      class="btn" 
      role="button"
    >
      <i class="icon material-icons">
        timer
      </i>
      <span class="caption">TIMESHEETS</span>
    </router-link>

    <router-link 
      :to="{ name : 'scheduler' }" 
      class="btn" 
      role="button"
    >
      <i class="icon material-icons">
        calendar_today
      </i>
      <span class="caption">SCHEDULE</span>
    </router-link>

    <div class="btn-group dropup">
      <button 
        type="button" 
        class="btn" 
        data-toggle="dropdown" 
        aria-haspopup="true" 
        aria-expanded="false"
      >
        <i class="icon material-icons">
          more_horiz
        </i>
        <span class="caption">MORE</span>
      </button>
      <div class="dropdown-menu">                
        <router-link 
          :to="{ name : 'jobList'}" 
          class="dropdown-item"
        >
          <i class="icon material-icons">
            work
          </i>Jobs
        </router-link> 
        <router-link 
          :to="{ name : 'profileList'}" 
          class="dropdown-item"
        >
          <i class="icon material-icons">
            assignment
          </i>Job Profiles
        </router-link>  
        <router-link 
          :to="{ name : 'userList'}" 
          class="dropdown-item"
        >
          <i class="icon material-icons">
            person
          </i>Users
        </router-link> 
        <router-link 
          :to="{ name : 'roleList'}" 
          class="dropdown-item"
        >
          <i class="icon material-icons">
            verified_user
          </i>Roles
        </router-link> 
        <router-link 
          :to="{ name : 'teamList'}" 
          class="dropdown-item"
        >
          <i class="icon material-icons">
            group
          </i>Teams
        </router-link>           
        <router-link 
          :to="{ name : 'contactList'}" 
          class="dropdown-item"
        >
          <i class="icon material-icons">
            contact_mail
          </i>Contacts
        </router-link>      
        <router-link 
          :to="{ name : 'activityList'}" 
          class="dropdown-item"
        >
          <i class="icon material-icons">
            description
          </i>Activity Reports
        </router-link>           
      </div>
    </div>   
  </div>
</template>

<script>
export default {
  name: "AppIconbarTop", 
};
</script>

<style scoped>
.dropdown-item i{
  color: inherit !important;
  margin-right: 5px;
  vertical-align: top;
}
</style>