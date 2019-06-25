import VuexPersist from 'vuex-persist';

export const localStorage = new VuexPersist({
  key: 'app.ticsol',
  storage: window.localStorage,
  reducer: state => ({
    user: state.user,
    core: {
      theme: state.core.theme,
      drawer: state.core.drawer,
      notifications: state.core.notifications
    }
  })
});