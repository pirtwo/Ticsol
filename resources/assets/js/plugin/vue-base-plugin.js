import VbIcon from '../components/Base/VbIcon.vue'
import VbSelect from '../components/Base/VbSelect.vue'
import VbSwitch from '../components/Base/VbSwitch.vue'
import VbComment from '../components/Base/VbComment.vue'
import VbDropdown from '../components/Base/VbDropdown.vue'
import VbSnackbar from '../components/Base/VbSnackbar'
import VbTimepicker from '../components/Base/VbTimepicker.vue'
import VbNotification from '../components/Base/VbNotification.vue'
import VbPagination from '../components/Base/VbPagination.vue'
import TsGrid from '../components/Base/TsGrid.vue'

const VueBase = {
    install(Vue, options) {        
        Vue.component('vb-icon', VbIcon);
        Vue.component('vb-select', VbSelect);
        Vue.component('vb-switch', VbSwitch);
        Vue.component('vb-comment', VbComment);
        Vue.component('vb-snackbar', VbSnackbar);
        Vue.component('vb-dropdown', VbDropdown);
        Vue.component('vb-timepicker', VbTimepicker);
        Vue.component('vb-notification', VbNotification);
        Vue.component('vb-pagination', VbPagination); 
        Vue.component('ts-grid', TsGrid);
    }
}

export default VueBase;