<template>    
        <router-view>
            <job-create-popup></job-create-popup>
            <activity-create-popup></activity-create-popup>
        </router-view>    
</template>

<script>
import JobCreatePopup from './jobs/JobCreatePopup.vue';
import ActivityCreatePopup from './activities/ActivityCreatePopup.vue';

export default {
    name: 'Dashboard',
    components:{
        'job-create-popup' : JobCreatePopup,
        'activity-create-popup' : ActivityCreatePopup,
    }
};
</script>

<style scoped>

</style>