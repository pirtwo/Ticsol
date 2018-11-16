import VbIcon from '../components/Base/VbIcon.vue'
import VbSelect from '../components/Base/VbSelect.vue'
import VbSwitch from '../components/Base/VbSwitch.vue'
import VbDropdown from '../components/Base/VbDropdown.vue'
import VbTimepicker from '../components/Base/VbTimepicker.vue'
import VbNotification from '../components/Base/VbNotification.vue'

const VueBase = {
    install(Vue, options) {
        Vue.component('vb-icon', VbIcon)
        Vue.component('vb-select', VbSelect)
        Vue.component('vb-switch', VbSwitch)
        Vue.component('vb-dropdown', VbDropdown)
        Vue.component('vb-timepicker', VbTimepicker)
        Vue.component('vb-notification', VbNotification)
    }
}

export default VueBase;