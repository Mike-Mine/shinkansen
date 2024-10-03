<script setup>
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { Link } from '@inertiajs/vue3';
import { useTaskStatus } from '@/src/utils/taskStatus';
import { useFiltersStore } from '@/stores/filtersStore';

dayjs.extend(relativeTime);

const props = defineProps(['task']);

const filtersStore = useFiltersStore();

const selectUser = (id) => {
  filtersStore.setReporterId(id);
};

const { formattedStatus, statusClass } = useTaskStatus(props.task.status);

</script>

<template>
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="flex items-start space-x-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>

            <div class="flex-1">
                <div class="flex justify-between items-center">
                    <Link :href="route('tasks.show', task.id)" class="block hover:underline">
                        <h3 class="text-lg font-semibold text-gray-900">{{ task.title }}</h3>
                    </Link>
                    <span :class="[statusClass, 'px-2 py-1 text-xs font-medium rounded-full']">
                        {{ formattedStatus }}
                    </span>
                </div>

                <p class="mt-1 text-sm text-gray-600">{{ task.description }}</p>

                <div class="mt-4 flex items-center text-sm text-gray-500 space-x-4">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <button @click="selectUser(task.reporter.id)" class="hover:underline">{{ task.reporter.name }}</button>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Created: {{ dayjs(task.created_at).format('DD/MMM/YYYY HH:mm') }}
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Updated: {{ dayjs(task.updated_at).fromNow() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>