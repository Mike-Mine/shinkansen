<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Task from '@/Components/Task.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { onMounted, ref, watch } from 'vue';
import { useFiltersStore } from '@/stores/filtersStore';

const props = defineProps({
    tasks: Object,
    can: Object,
    filters: Object,
});

const filtersStore = useFiltersStore();

onMounted(() => {
    filtersStore.initializeFromProps(props);
})

const search = ref(props.filters.search);

watch(search, debounce(
    (query) => {
        filtersStore.setSearch(query);
    }, 500
));
</script>

<template>
    <Head title="Tasks" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
                <div class="flex justify-between mb-4">
                    <div>filters</div>
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

                <div v-if="Object.keys(tasks.data).length">
                    <Task
                        v-for="task in tasks.data"
                        :key="task.id"
                        :task="task"
                    />
                    <div class="mt-4">
                        <Link
                            v-for="link in tasks.links"
                            :key="link.label"
                            :href="link.url ?? 'null'"
                            v-html="link.label"
                            class="px-2"
                            :class="{ 'text-zinc-400': !link.url, 'text-indigo-500': link.active }"
                        />
                    </div>
                </div>
                <div v-else>
                    No tasks found
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
