import VbIcon from '../components/Base/VbIcon.vue'
import VbSelect from '../components/Base/VbSelect.vue'
import VbSwitch from '../components/Base/VbSwitch.vue'
import VbTimepicker from '../components/Base/VbTimepicker.vue'

const VueBase = {
    install(Vue, options){
        Vue.component(VbIcon.name, VbIcon)
        Vue.component(VbSelect.name,VbSelect)
        Vue.component(VbSwitch.name, VbSwitch)
        Vue.component(VbTimepicker.name,VbTimepicker)
    }
}

export default VueBase;