<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';

import AssigneeSelector from '@/Components/Tasks/AssigneeSelector.vue';
import Container from '@/Components/Container.vue';
import DateInput from '@/Components/DateInput.vue';

defineProps({
    assignees: {
        type: Object,
        required: true,
    },
    defaultAssigneeName: {
        type: String,
        required: true,
    }
});

const form = useForm({
    title: '',
    description: '',
    assignee_id: 0,
    start_date: '',
    due_date: '',
});
</script>

<template>
    <Head title="Tasks" />

    <AuthenticatedLayout>
        <Container>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form @submit.prevent="form.post(route('tasks.store'))">
                    <div class="mb-4">
                        <label for="title" class="block font-medium text-sm text-gray-700">
                            Title
                        </label>
                        <input
                            id="title"
                            v-model="form.title"
                            type="text"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        />
                        <InputError :message="form.errors.title" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block font-medium text-sm text-gray-700">
                            Description
                        </label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        ></textarea>
                        <InputError :message="form.errors.description" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <label for="assignee" class="block font-medium text-sm text-gray-700">
                            Assignee
                        </label>
                        <AssigneeSelector
                            :assignees="assignees"
                            :defaultAssigneeName="defaultAssigneeName"
                            v-model="form.assignee_id"
                        />
                        <InputError :message="form.errors.assignee_id" class="mt-2" />
                    </div>

                    <div class="mb-4 flex items-center gap-2">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Start date</label>
                            <DateInput v-model="form.start_date"/>
                            <InputError :message="form.errors.start_date" class="mt-2" />
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Due date</label>
                            <DateInput v-model="form.due_date"/>
                            <InputError :message="form.errors.due_date" class="mt-2" />
                        </div>
                        
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button
                            type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150"
                        >
                            Create Task
                        </button>
                    </div>
                </form>
            </div>
        </Container>
    </AuthenticatedLayout>
</template>