<template>
    <div class="toolbar-bot">
        <ul class="t-menu open horizontal compact right float-left">        
            <li>
                <router-link :to="{ name: 'home' }" class="shortcut button" role="button">
                    <span class="mif-dashboard mif-2x"></span>
                    <!-- <span class="tag"></span> -->
                    <span class="caption">Dashboard</span>
                </router-link>
            </li>        
            <li>
                <router-link :to="{ name: 'inbox' }" class="shortcut button" role="button">
                    <span class="mif-envelop mif-2x"></span>
                    <span class="tag">15</span>
                    <span class="caption">Inbox</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{ name: 'hr' }" class="shortcut button" role="button">
                    <span class="mif-users mif-2x"></span>
                    <span class="tag">3</span>
                    <span class="caption">H.R</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{ name: 'requests' }" class="shortcut button" role="button">
                    <span class="mif-stack2 mif-2x"></span>
                    <span class="tag">5</span>
                    <span class="caption">Requests</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{ name: 'timesheets' }" class="shortcut button" role="button">
                    <span class="mif-alarm mif-2x"></span>
                    <!-- <span class="tag"></span> -->
                    <span class="caption">TimeSheets</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{ name: 'scheduler' }" class="shortcut button" role="button">
                    <span class="mif-calendar mif-2x"></span>
                    <span class="tag">15</span>
                    <span class="caption">Schedule</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{ name: 'jobs' }" class="shortcut button" role="button">
                    <span class="mif-location-city mif-2x"></span>
                    <span class="tag">3</span>
                    <span class="caption">Jobs</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{ name: 'jobs' }" class="shortcut button" role="button">
                    <span class="mif-more-horiz mif-2x"></span>
                    <!-- <span class="tag">3</span> -->
                    <span class="caption">More</span>
                </router-link>
            </li>
        </ul>
    </div>
</template>
<script>
export default {
  name: "IconbarTop"
};
</script>
<style scoped>
</style>more-horiz