import VbIcon from '../components/Base/VbIcon.vue'
import VbSelect from '../components/Base/VbSelect.vue'
import VbSwitch from '../components/Base/VbSwitch.vue'
import VbTimepicker from '../components/Base/VbTimepicker.vue'

const VueBase = {
    install(Vue, options) {
        Vue.component('vb-icon', VbIcon)
        Vue.component('vb-select', VbSelect)
        Vue.component('vb-switch', VbSwitch)
        Vue.component('vb-timepicker', VbTimepicker)
    }
}

export default VueBase;