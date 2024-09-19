<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import TaskList from '@/Components/Tasks/List.vue';

defineProps(['assignedTasks', 'reportedTasks']);
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
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
