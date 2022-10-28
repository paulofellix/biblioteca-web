export const loadingMixin = {
    data() {
        return {
            loading: false
        }
    },
    methods: {
        startLoading() {
            this.loading = true
        },
        stopLoading() {
            this.loading = false
        },
        toggleLoading(state = null) {
            if (state === null) {
                this.loading = !this.loading
            } else {
                this.loading = state
            }
        }
    }
}