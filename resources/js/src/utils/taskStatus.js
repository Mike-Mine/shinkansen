import { computed } from 'vue';

export function useTaskStatus(status) {
    const formattedStatus = computed(() => formatStatus(status));
    const statusClass = computed(() => getStatusClass(status));

    return {
        formattedStatus,
        statusClass
    };
}

export const formatStatus = (status) => {
    return status
        .split('_')
        .map(word => word[0].toUpperCase() + word.slice(1))
        .join(' ');
};

export const getStatusClass = (status) => {
    return `status-${status.replace(/_/g, '-')}`;
};
