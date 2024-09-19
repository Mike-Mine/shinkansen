<script setup>
import { Link } from '@inertiajs/vue3'
import { formatStatus, getStatusClass } from '@/src/utils/taskStatus'

const props = defineProps({
    tasks: {
        type: Array,
        required: true
    },
    title: {
        type: String,
        required: true
    },
    emptyText: {
        type: String,
        default: 'No tasks available'
    },
    viewAllRoute: {
        type: String,
        required: true
    }
})

</script>

<template>
    <div class="bg-white rounded-lg p-4 w-1/2 border border-gray-300 shadow">
        <h3 class="text-lg font-semibold">{{ title }}</h3>
        <ul v-if="tasks.length" class="divide-y divide-gray-200">
            <li v-for="task in tasks" :key="task.id" class="py-2">
                <div class="flex justify-between items-center">
                    <Link :href="route('tasks.show', task.id)" class="hover:underline text-gray-900">
                        {{ task.title }}
                    </Link>
                    <span :class="[getStatusClass(task.status), 'px-2 py-1 text-xs font-medium rounded-full']">
                        {{ formatStatus(task.status) }}
                    </span>
                </div>
            </li>
        </ul>
        <p v-else class="text-gray-500">{{ emptyText }}</p>

        <!-- <div class="mt-4">
            <Link :href="route(viewAllRoute)" class="text-sm text-blue-600 hover:underline">
                View all tasks
            </Link>
        </div> -->
    </div>
</template>
