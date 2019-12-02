import AppLayout from '../components/app/AppLayout.vue';
import AppMain from '../components/app/AppMain';
import AppIconbarTop from '../components/app/AppIconbarTop.vue';
import AppIconbarBot from '../components/app/AppIconbarBot.vue';

import TsIcon from '../components/base/TsIcon.vue';
import TsBadge from '../components/base/TsBadge.vue';
import TsSelect from '../components/base/TsSelect.vue';
import TsSwitch from '../components/base/TsSwitch.vue';
import TsComment from '../components/base/TsComment.vue';
import TsDropdown from '../components/base/TsDropdown.vue';
import TsSnackbar from '../components/base/TsSnackbar';
import TsTimepicker from '../components/base/TsTimepicker.vue';
import TsNotification from '../components/base/TsNotification.vue';
import TsPagination from '../components/base/TsPagination.vue';
import TsModal from '../components/base/TsModal.vue';
import TsGrid from '../components/base/TsGrid.vue';
import TsTable from '../components/base/TsTable.vue';
import TsFilter from '../components/base/TsFilter.vue';
import TsSpinner from '../components/base/TsSpinner.vue';
import TsToast from '../components/base/TsToast.vue';
import TsDateScroller from '../components/base/TsDateScroller.vue';
import TsGroupbox from '../components/base/TsGroupbox.vue';
import TsChipsbox from '../components/base/TsChipsbox.vue';
import TsSection from '../components/base/TsSection.vue';


const VueBase = {
    install(Vue, options) {  
        // App sections 
        Vue.component('app-layout', AppLayout);
        Vue.component('app-main', AppMain);
        Vue.component('iconbar-top', AppIconbarTop);
        Vue.component('iconbar-bot', AppIconbarBot);
        
        // App components
        Vue.component('ts-icon', TsIcon);
        Vue.component('ts-badge', TsBadge);
        Vue.component('ts-select', TsSelect);
        Vue.component('ts-switch', TsSwitch);
        Vue.component('ts-comment', TsComment);
        Vue.component('ts-snackbar', TsSnackbar);
        Vue.component('ts-dropdown', TsDropdown);
        Vue.component('ts-timepicker', TsTimepicker);
        Vue.component('ts-notification', TsNotification);
        Vue.component('ts-pagination', TsPagination); 
        Vue.component('ts-modal', TsModal);
        Vue.component('ts-grid', TsGrid);
        Vue.component('ts-table', TsTable);
        Vue.component('ts-filter', TsFilter);
        Vue.component('ts-spinner', TsSpinner);
        Vue.component('ts-toast', TsToast);
        Vue.component('ts-datescroller', TsDateScroller); 
        Vue.component('ts-groupbox', TsGroupbox); 
        Vue.component('ts-chipsbox', TsChipsbox);
        Vue.component('ts-section', TsSection);           
    }
}

export default VueBase;