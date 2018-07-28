
export const coreModule = {

    namespaced: true,

    state: {
        app: {
            errors: [],
            message: ''
        },
        ui: {
            fullscreen: false,
            contentWidth: 0,
            contentHeight: 0,
            documentWidth: 0,
            documentHeight: 0
        },
        drawer: {
            show: true,
            message: ''
        },
        statusbar: {
            show: false,
            message: ''
        },
        toolbar: {
            show: true,
            height: 0
        },
        header:{
            show: true,
            height: 0
        },
        footer:{
            show: true,
            height: 0
        },
        loading:{
            show: false,
            message: ''
        }

    },

    getters: {

    },

    mutations: {

    },

    actions: {

    }

}