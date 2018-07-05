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
        <router-link :to="{ name : 'requests' }" class="btn btn-light" role="button" >
            <i class="icon material-icons">
                message
            </i>
            <span class="caption">REQUESTS</span>
        </router-link>
        <router-link :to="{ name : 'timesheets' }" class="btn btn-light" role="button" >
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
        <router-link to="jobs" class="btn btn-light" role="button" >
            <i class="icon material-icons">
                more_horiz
            </i>
            <span class="caption">MORE</span>
        </router-link>
    </div>
</template>
<script>
export default {
  name: "IconbarTop"
};
</script>
<style scoped>
</style>