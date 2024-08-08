import { computed } from 'vue';

export function useTaskStatus(taskRef) {
    const formattedStatus = computed(() => formatStatus(taskRef.status));
    const statusClass = computed(() => getStatusClass(taskRef.status));

    return {
        formattedStatus,
        statusClass
    };
}

const formatStatus = (status) => {
    return status
        .split('_')
        .map(word => word[0].toUpperCase() + word.slice(1))
        .join(' ');
};

const getStatusClass = (status) => {
    return `status-${status.replace(/_/g, '-')}`;
};
