<template>
    <div class="toolbar-bot">
        <router-link :to="{ name : 'dash' }" class="btn btn-light" role="button" >
            <i class="icon material-icons">
                dashboard
            </i>
            <span class="caption">HOME</span>
        </router-link>
        <router-link :to="{ name : 'inbox' }" class="btn btn-light" role="button" >
            <i class="icon material-icons">
                inbox
            </i>
            <span class="caption">INBOX</span>
        </router-link>
        <router-link :to="{ name : 'request' }" class="btn btn-light" role="button" >
            <i class="icon material-icons">
                message
            </i>
            <span class="caption">REQUESTS</span>
        </router-link>
        <router-link :to="{ name : 'timesheet' }" class="btn btn-light" role="button" >
            <i class="icon material-icons">
                timer
            </i>
            <span class="caption">TIMESHEETS</span>
        </router-link>
        <router-link :to="{ name : 'scheduler' }" class="btn btn-light" role="button" >
            <i class="icon material-icons">
                calendar_today
            </i>
            <span class="caption">SCHEDULE</span>
        </router-link>
        <div class="btn-group dropup">
            <button type="button" class="btn btn-light" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="icon material-icons">
                    more_horiz
                </i>
                <span class="caption">MORE</span>
            </button>
            <div class="dropdown-menu">                
                <router-link :to="{ name : 'jobList'}" class="dropdown-item">
                    Job List
                </router-link>               
                <router-link :to="{ name : 'profileList'}" class="dropdown-item">
                    Profiles List
                </router-link>  
                 <router-link :to="{ name : 'contactList'}" class="dropdown-item">
                    Contacts List
                </router-link>      
                 <router-link :to="{ name : 'activityList'}" class="dropdown-item">
                    Activities List
                </router-link>           
            </div>
        </div>        
    </div>
</template>
<script>
export default {
  name: "IconbarTop"
};
</script>
<style scoped>
</style>