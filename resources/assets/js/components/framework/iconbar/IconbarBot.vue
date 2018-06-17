<template>
    <div class="toolbar-bot">

       <md-menu md-size="small" md-align-trigger>
            <md-button class="md-plain" md-menu-trigger :to="{name : 'home'}">
                <md-icon>dashboard</md-icon>
                <span class="caption">Dashboard</span>
            </md-button>
            <md-menu-content>
                <md-menu-item>My Item 1</md-menu-item>
                <md-menu-item>My Item 2</md-menu-item>
                <md-menu-item>My Item 3</md-menu-item>
            </md-menu-content>
        </md-menu>

        <md-menu md-size="small" md-align-trigger>
            <md-button class="md-plain" md-menu-trigger :to="{name : 'inboxList'}">
                <md-icon>inbox</md-icon>
                <span class="caption">Inbox</span>
            </md-button>
            <md-menu-content>
                <md-menu-item>My Item 1</md-menu-item>
                <md-menu-item>My Item 2</md-menu-item>
                <md-menu-item>My Item 3</md-menu-item>
            </md-menu-content>
        </md-menu>

        <md-menu md-size="small" md-align-trigger>
            <md-button class="md-plain" md-menu-trigger :to="{name : 'requests'}">
                <md-icon>message</md-icon>
                <span class="caption">Requests</span>
            </md-button>
            <md-menu-content>
                <md-menu-item>My Item 1</md-menu-item>
                <md-menu-item>My Item 2</md-menu-item>
                <md-menu-item>My Item 3</md-menu-item>
            </md-menu-content>
        </md-menu>

        <md-menu md-size="small" md-align-trigger>
            <md-button class="md-plain" md-menu-trigger>
                <md-icon>timer</md-icon>
                <span class="caption">TimeSheets</span>
            </md-button>
            <md-menu-content>
                <md-menu-item>My Item 1</md-menu-item>
                <md-menu-item>My Item 2</md-menu-item>
                <md-menu-item>My Item 3</md-menu-item>
            </md-menu-content>
        </md-menu>

        
        <md-button class="md-plain" :to="{name : 'scheduler'}">
            <md-icon>calendar_today</md-icon>
            <span class="caption">Schedules</span>
        </md-button>
            
        

        <md-menu md-size="small" md-align-trigger>
            <md-button class="md-plain" md-menu-trigger>
                <md-icon>more_horiz</md-icon>
                <span class="caption">More</span>
            </md-button>
            <md-menu-content>
                <md-menu-item>
                    <md-button class="md-plain" :to="{name : 'jobs'}">
                        <md-icon>business_center</md-icon>
                        <span class="caption">Jobs</span>
                    </md-button>
                </md-menu-item>
                <md-menu-item>
                    <md-button class="md-plain">
                        <md-icon>event_note</md-icon>
                        <span class="caption">activity report</span>
                    </md-button>
                </md-menu-item>
                <md-menu-item>My Item 3</md-menu-item>
            </md-menu-content>
        </md-menu>


    </div>
</template>
<script>
export default {
  name: "IconbarTop"
};
</script>
<style scoped>
</style>