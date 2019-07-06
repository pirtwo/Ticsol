import { mapActions } from 'vuex';
import moment from "moment";

const SNACKBAR_THEME = {
    primary: 'alert alert-primary',
    secondary: 'alert alert-secondary',
    success: 'alert alert-success',
    danger: 'alert alert-danger',
    warning: 'alert alert-warning',
    info: 'alert alert-info',
    light: 'alert alert-light',
    dark: 'alert alert-dark',
}

export default {
    data() {
        return {
            isLoading: false
        }
    },

    methods: {
        ...mapActions({
            snackbar: 'core/snackbar'
        }),

        /**
         * Start the loading screen.
         */
        startLoading() {
            this.isLoading = true;
        },

        /**
         * Stop the loading screen.
         */
        stopLoading() {
            this.isLoading = false;
        },

        /**
         * Shows the message to user.
         * 
         * @param {string} message alert message
         * @param {string} theme bootstrap alert theme
         * @param {int} timeout hide after miliseconds
         * @param {boolean} fixed hide after timeout?
         */
        showMessage(message, theme = 'primary', timeout = 3000, fixed = false) {
            this.snackbar({
                show: true,
                message: message,
                theme: SNACKBAR_THEME[theme],
                timeout: timeout,
                fixed: fixed
            });
        },

        /**
         * Show the error to the user.
         * @param {object} error 
         * @param {string} message 
         */
        showError(error, message = '') {
            this.snackbar({
                show: true,
                message: '',
                theme: SNACKBAR_THEME.danger,
                fixed: true
            });
        },

        //-------- Date & Time ------

        /**
         * Converts utc date to local
         * 
         * @param {string} date utc date
         * @param {string} format output format
         */
        utcToLocal({ date, format = "YYYY-MM-DD" }) {
            let utc = moment().utc(date).toDate();
            return moment(utc).local().format(format);
        }

    }
}