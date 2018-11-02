import VuexPersist from 'vuex-persist';

export const localStorage = new VuexPersist({
    key: 'Vuex',
    storage: window.localStorage,
    modules: ['user', 'core']
})