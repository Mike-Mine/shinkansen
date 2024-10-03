import { defineStore } from 'pinia';
import { useForm } from '@inertiajs/vue3';

export const useFiltersStore = defineStore('filters', {
    state: () => ({
        search: '',
        reporterId: null,
    }),
    actions: {
        setSearch(query) {
            this.search = query;
            this.updateURL();
        },
        setReporterId(id) {
            this.reporterId = id;
            this.updateURL();
        },
        updateURL() {
            const form = useForm({
                search: this.search,
                reporter_id: this.reporterId,
            });
            form.get(route('tasks.index'), {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            });
        },
        initializeFromProps(props) {
            this.search = props.filters?.search || '';
            this.reporterId = props.filters?.reporter_id || null;
        },
    },
});
