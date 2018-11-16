import { mapActions, mapGetters } from 'vuex'
export default {
    computed: {
        ...mapGetters({
            getLogs: 'core/getAppLogs'
        })
    },

    methods: {

        ...mapActions({
            pushLog: 'core/pushLog',
            clearLog: 'core/clearLog'
        }),

        logError(error) {
            let e = {};
            e.type = 'danger';

            if (error.response !== undefined) {
                e.title = `Error ${error.response.status} : ${error.response.statusText}`;
                e.message = `Your request failed during the process because: ${error.response.data.message}`;
            } else {
                e.title = `Opss, something went wrong...`;
                e.message = error.message;
            }

            e.date = new Date();
            e.seen = false;
            this.pushLog(e);
        },

        /**
         * @param {string}  type     primary, secondary, success, danger, warning, info         
         * @param {string}  title    The title of log.
         * @param {string}  message  the log content.
         * @param {object}  date     date and time of log.
         * @param {boolean} seen     is seen by user.
         */
        logEvent(type, title, message, date = new Date(), seen = false) {
            let e = {};
            e.type = type;
            e.title = title;
            e.message = message;
            e.date = date;
            e.seen = seen;
            this.pushLog(e);
        }
    }
}