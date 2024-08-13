<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AssigneeSelector from '@/Components/Tasks/AssigneeSelector.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import StatusSelector from '@/Components/Tasks/StatusSelector.vue';

dayjs.extend(relativeTime);

const props = defineProps({
    task: {
        type: Object,
        required: true,
    },
    statuses: {
        type: Object,
    },
    users: {
        type: Object,
    },
});

const task = ref(props.task);

watch(() => [task.value.status, task.value.assignee_id], ([newStatus, newAssigneeId]) => {
    useForm({
        status: newStatus,
        assignee_id: newAssigneeId,
    }).patch(route('tasks.update', task.value.id), {
        preserveState: true,
        preserveScroll: true,
    });
}, { deep: true });
</script>

<template>
    <Head title="Task Details" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center space-x-4 justify-between">
                <div class="flex items-center space-x-4">
                    <Link
                        :href="route('tasks.index')"
                        class="text-gray-600 hover:text-gray-900"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </Link>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Task Details</h2>
                </div>

            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex flex-col md:flex-row">
                            <div class="w-full md:w-2/3 pr-0 md:pr-4">
                                <h1 class="text-2xl font-bold mb-4">{{ task.title }}</h1>
                                <hr class="my-4">
                                <div class="mt-4">
                                    <h2 class="text-lg font-semibold mb-2">Description</h2>
                                    <p>{{ task.description }}</p>
                                </div>
                            </div>
                            <div class="w-full md:w-1/3 mt-4 md:mt-0 pl-0 md:pl-4 border-t md:border-t-0 md:border-l border-gray-200">
                                <div class="mb-4">
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                                    <StatusSelector
                                        v-model="task.status"
                                        :statuses="statuses"
                                    />
                                </div>
                                <div class="mb-4">
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Assignee</h3>
                                    <AssigneeSelector
                                        :users="users"
                                        v-model="task.assignee_id"
                                    />
                                </div>
                                <div class="mb-4">
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Reporter</h3>
                                    <p>{{ task.reporter.name }}</p>
                                </div>
                                <div class="mt-6">
                                    <p class="text-xs text-gray-500">Created: {{ dayjs(task.created_at).format('DD/MMM/YYYY HH:mm') }}</p>
                                    <p class="text-xs text-gray-500">Updated: {{ dayjs(task.updated_at).format('DD/MMM/YYYY HH:mm') }}</p>
                                </div>
                                <div class="mt-4 text-right">
                                    <Link
                                        :href="route('tasks.destroy', task.id)"
                                        class="text-red-600 text-sm hover:text-red-900"
                                        method="delete"
                                        as="button"
                                    >
                                        Delete Task
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>