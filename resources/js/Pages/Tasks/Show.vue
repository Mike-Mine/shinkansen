<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';

import AssigneeSelector from '@/Components/Tasks/AssigneeSelector.vue';
import StatusSelector from '@/Components/Tasks/StatusSelector.vue';
import EditableTitle from '@/Components/Tasks/EditableTitle.vue';
import EditableDescription from '@/Components/Tasks/EditableDescription.vue';
import CommentsList from '@/Components/Comments/List.vue';

dayjs.extend(relativeTime);

const props = defineProps({
    task: {
        type: Object,
        required: true,
    },
    comments: {
        type: Object,
    },
    statuses: {
        type: Object,
    },
    assignees: {
        type: Object,
    },
    can: {
        type: Object,
    },
});

console.log(props.comments);

const task = ref(props.task);

const updateTask = (updatedData) => {
    task.value = { ...task.value, ...updatedData };
};

watch(() => [
    task.value.status,
    task.value.assignee_id,
    task.value.title,
    task.value.description
], ([newStatus, newAssigneeId]) => {
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
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex flex-col md:flex-row">
                            <div class="w-full md:w-2/3 pr-0 md:pr-4">
                                <EditableTitle :task="task" @update="updateTask" />

                                <hr class="my-4">

                                <EditableDescription :task="task" @update="updateTask" />
                            </div>
                            <div class="w-full md:w-1/3 mt-4 md:mt-0 pl-0 md:pl-4 border-t md:border-t-0 md:border-l border-gray-200">
                                <div class="mb-4">
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                                    <StatusSelector
                                        v-model="task.status"
                                        :statuses="statuses"
                                        :disabled="!can.updateStatus"
                                    />
                                </div>
                                <div class="mb-4">
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Assignee</h3>
                                    <AssigneeSelector
                                        :assignees="assignees"
                                        v-model="task.assignee_id"
                                        :disabled="!can.updateAssignee"
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
                                <div v-if="can.delete" class="mt-4 text-right">
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
                        <div class="mt-4 border-t">
                            <CommentsList :task="task" :comments="comments"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>