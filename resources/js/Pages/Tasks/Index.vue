<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Task from '@/Components/Task.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

defineProps(['tasks']);
</script>

<template>
    <Head title="Tasks" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
                <Link :href="route('tasks.create')" class="text-gray-600">
                    <div class="sm:p-4 bg-white shadow sm:rounded-lg hover:bg-gray-200">
                        <div class="flex justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                    </div>
                </Link>

                <Task
                    v-for="task in tasks.data"
                    :key="task.id"
                    :task="task"
                />
                <div class="mt-4">
                    <Link
                        v-for="link in tasks.links"
                        :key="link.url"
                        :href="link.url"
                        v-html="link.label"
                        class="px-2"
                        :class="{ 'text-zinc-400': !link.url, 'text-indigo-500': link.active }"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>