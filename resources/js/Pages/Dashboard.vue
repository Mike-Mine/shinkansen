<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import TaskList from '@/Components/Tasks/List.vue';
import { formatStatus, getStatusClass } from '@/src/utils/taskStatus';

const props = defineProps({
    assignedTasks: {
        type: Object,
        required: true
    },
    reportedTasks: {
        type: Object,
        required: true
    },
    topReporter: {
        type: Object,
        required: true
    },
    topAssignee: {
        type: Object,
        required: true
    },
    taskCounts: {
        type: Object,
        required: true
    },
    totalUsers: {
        type: Number,
        required: true
    }
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex space-x-4">
                            <TaskList
                                :tasks="assignedTasks"
                                title="Assigned to you"
                                empty-text="No tasks assigned"
                                :view-all-route="'tasks.assigned.index'"
                            />
                            <TaskList
                                :tasks="reportedTasks"
                                title="Reported by you"
                                empty-text="No tasks reported"
                                :view-all-route="'tasks.reported.index'"
                            />
                        </div>
                        <div class="grid grid-cols-4 gap-4 mt-4">
                            <div class="bg-white p-4 rounded-lg shadow border border-gray-300">
                                <h4 class="text-lg font-semibold mb-4">Users Statistics</h4>
                                <p>Total users: {{ totalUsers }}</p>
                                <p>Top Reporter: {{ topReporter.name }} ({{ topReporter.reported_tasks_count }})</p>
                                <p>Top Assignee: {{ topAssignee.name }} ({{ topAssignee.assigned_tasks_count }})</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow border border-gray-300">
                                <h4 class="text-lg font-semibold mb-4">Tasks Summary</h4>
                                <p v-for="(count, status) in taskCounts">
                                    {{ formatStatus(status) }} tasks: {{ count }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
