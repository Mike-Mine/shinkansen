<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import TaskList from '@/Components/Tasks/List.vue';
import { formatStatus} from '@/src/utils/taskStatus';
import TaskCompletionChart from '@/Components/Charts/TaskCompletion.vue';
import Container from '@/Components/Container.vue';

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
        <Container>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex space-x-4">
                        <TaskList
                            :tasks="assignedTasks"
                            title="Assigned to you"
                            empty-text="No tasks assigned"
                            :view-all-route="route('tasks.index', { assignee_id: $page.props.auth.user.id })"
                        />
                        <TaskList
                            :tasks="reportedTasks"
                            title="Reported by you"
                            empty-text="No tasks reported"
                            :view-all-route="route('tasks.index', { reporter_id: $page.props.auth.user.id })"
                        />
                    </div>
                    <div class="grid grid-cols-4 gap-4 mt-4">
                        <div class="bg-white p-4 rounded-lg shadow h-48">
                            <h4 class="text-lg font-semibold mb-4">Users Statistics</h4>
                            <p>Total users: {{ totalUsers }}</p>
                            <p>Top Reporter: {{ topReporter.name }} ({{ topReporter.reported_tasks_count }})</p>
                            <p>Top Assignee: {{ topAssignee.name }} ({{ topAssignee.assigned_tasks_count }})</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow h-48">
                            <h4 class="text-lg font-semibold mb-4">Tasks Summary</h4>
                            <p v-for="(count, status) in taskCounts">
                                {{ formatStatus(status) }} tasks:
                                <Link
                                    v-if="status !== 'total'"
                                    :href="route('tasks.index', { status: status })"
                                    class="hover:underline"
                                >
                                    {{ count }}
                                </Link>
                                <span v-else>{{ count }}</span>
                            </p>
                        </div>
                        <div class="bg-white p-4 rounded shadow col-span-2">
                            <TaskCompletionChart :taskCounts="taskCounts" />
                        </div>
                    </div>
                </div>
            </div>
        </Container>
    </AuthenticatedLayout>
</template>
