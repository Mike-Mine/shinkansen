<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Task from '@/Components/Task.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { onMounted, ref, watch } from 'vue';
import { useFiltersStore } from '@/stores/filtersStore';
import Container from '@/Components/Container.vue';
import PaginationLinks from '@/Components/PaginationLinks.vue';

const props = defineProps({
    tasks: Object,
    can: Object,
    filters: Object,
    deleted: {
        type: Boolean,
        default: false
    }
});

const filtersStore = useFiltersStore();

onMounted(() => {
    filtersStore.initializeFromProps(props);
    filtersStore.setControllerName(props.deleted ? 'tasks.deleted' : 'tasks.index');
})

const search = ref(props.filters.search);

const getReporterName = (id) => {
    return props.tasks.data.find(task => task.reporter_id === Number(id)).reporter.name;
}

const getAssigneeName = (id) => {
    return props.tasks.data.find(task => task.assignee_id === Number(id)).assignee.name;
}

watch(search, debounce(
    (query) => {
        filtersStore.setSearch(query);
    }, 500
));
</script>

<template>
    <Head title="Tasks" />

    <AuthenticatedLayout>
        <Container>
            <div class="flex justify-between mb-4">
                <div class="flex items-center gap-2">
                    <button
                        v-if="filtersStore.status"
                        @click="filtersStore.clearParam('status')"
                        class="px-2 py-1 rounded-md bg-gray-500 text-white flex items-center gap-1"
                    >
                        {{ filtersStore.formattedStatus }}

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <button
                        v-if="filtersStore.reporterId"
                        @click="filtersStore.clearParam('reporterId')"
                        class="px-2 py-1 rounded-md bg-gray-500 text-white flex items-center gap-1"
                    >
                        Reporter: {{ getReporterName(filtersStore.reporterId) }}

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <button
                        v-if="filtersStore.assigneeId"
                        @click="filtersStore.clearParam('assigneeId')"
                        class="px-2 py-1 rounded-md bg-gray-500 text-white flex items-center gap-1"
                    >
                        Assignee: {{ getAssigneeName(filtersStore.assigneeId) }}

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="w-1/4">
                    <input
                        type="search"
                        v-model="search"
                        placeholder="Search..."
                        class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                    />
                </div>
            </div>
            <Link v-if="can.create" :href="route('tasks.create')" class="text-gray-600">
                <div class="sm:p-4 bg-white shadow sm:rounded-lg hover:bg-gray-200">
                    <div class="flex justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                </div>
            </Link>

            <div v-if="Object.keys(tasks.data).length" class="space-y-4">
                <Task
                    v-for="task in tasks.data"
                    :key="task.id"
                    :task="task"
                />
                <div class="mt-4">
                    <PaginationLinks :paginator="tasks" />
                </div>
            </div>
            <div v-else class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                No tasks found
            </div>
        </Container>
    </AuthenticatedLayout>
</template>
