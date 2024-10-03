import { defineStore } from 'pinia';
import { useForm } from '@inertiajs/vue3';

export const useFiltersStore = defineStore('filters', {
    state: () => ({
        search: '',
        reporterId: null,
        assigneeId: null,
        status: null,
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
        setAssigneeId(id) {
            this.assigneeId = id;
            this.updateURL();
        },
        setStatus(status) {
            this.status = status;
            this.updateURL();
        },
        updateURL() {
            const form = useForm({
                search: this.search,
                reporter_id: this.reporterId,
                assignee_id: this.assigneeId,
                status: this.status,
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
            this.assigneeId = props.filters?.assignee_id || null;
            this.status = props.filters?.status || null;
        },
    },
});
