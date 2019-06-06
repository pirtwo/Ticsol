import { mapActions } from 'vuex';

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

        showMessage(message, theme = 'primary', timeout = 3000, fixed = false) {
            this.snackbar({
                show: true,
                message: message,
                theme: SNACKBAR_THEME[theme],
                timeout: timeout,
                fixed: fixed
            });
        },

        showError(error, message = '') {
            this.snackbar({
                show: true,
                message: '',
                theme: SNACKBAR_THEME.danger,
                fixed: true
            });
        },

        startLoading() {
            this.isLoading = true;
        },

        stopLoading() {
            this.isLoading = false;
        }
    }
}