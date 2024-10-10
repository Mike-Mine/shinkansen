<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
import { useForm, usePage, router, Head, Link } from '@inertiajs/vue3';

import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';

import AssigneeSelector from '@/Components/Tasks/AssigneeSelector.vue';
import StatusSelector from '@/Components/Tasks/StatusSelector.vue';
import EditableTitle from '@/Components/Tasks/EditableTitle.vue';
import EditableDescription from '@/Components/Tasks/EditableDescription.vue';
import CommentsList from '@/Components/Comments/List.vue';
import Container from '@/Components/Container.vue';
import PaginationLinks from '@/Components/PaginationLinks.vue';
import DateInput from '@/Components/DateInput.vue';

dayjs.extend(relativeTime);

const props = defineProps({
    task: {
        type: Object,
        required: true,
    },
    statuses: {
        type: Object,
        required: true,
    },
    assignees: {
        type: Object,
        required: true,
    },
    defaultAssigneeName: {
        type: String,
        required: true,
    },
    can: {
        type: Object,
        required: true,
    },
});

const task = ref({ ...props.task });

const form = useForm({
    status: task.value.status,
    assignee_id: task.value.assignee_id,
    title: task.value.title,
    description: task.value.description,
    start_date: task.value.start_date,
    due_date: task.value.due_date,
});

const updateTask = (updates) => {
    Object.entries(updates).forEach(([field, value]) => {
        if (form[field] !== value) {
            form[field] = value;
            task.value[field] = value;
        }
    });

    form.patch(route('tasks.update', task.value.id), {
        preserveState: true,
        preserveScroll: true,
        only: Object.keys(updates),
    });
};

const startDate = computed({
    get: () => task.value.start_date,
    set: (newValue) => updateTask({ start_date: newValue }),
});

const dueDate = computed({
    get: () => task.value.due_date,
    set: (newValue) => updateTask({ due_date: newValue }),
});

const page = usePage();
const comments = computed(() => page.props.comments);

onMounted(() => {
    Echo.private(`task.${task.value.id}.comments`)
        .listen('TaskCommentCreated', (event) => {
            router.reload({ only: ['comments'] });
        });

    Echo.private(`tasks.${task.value.id}`)
        .listen('TaskUpdated', (e) => {
            task.value = { ...task.value, ...e.task };
        });
});

onUnmounted(() => {
    Echo.leave(`task.${task.value.id}.comments`);

    Echo.leave(`tasks.${task.value.id}`);
});
</script>

<template>
    <Head title="Task Details" />

    <AuthenticatedLayout>
        <Container>
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row">
                        <div class="w-full md:w-2/3 pr-0 md:pr-4">
                            <EditableTitle v-model="task.title" @update="updateTask" />

                            <hr class="my-4">

                            <EditableDescription v-model="task.description" @update="updateTask" />
                        </div>
                        <div class="w-full md:w-1/3 mt-4 md:mt-0 pl-0 md:pl-4 border-t md:border-t-0 md:border-l border-gray-200">
                            <div class="mb-4 flex justify-between">
                                <div class="w-full">
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                                    <StatusSelector
                                        v-model="task.status"
                                        :statuses="statuses"
                                        :disabled="!can.updateStatus"
                                        @update:modelValue="updateTask({ status: $event })"
                                        class="w-full"
                                    />
                                </div>
                                <div v-if="form.recentlySuccessful">
                                    <p class="text-sm text-green-600">Saved.</p>
                                </div>
                            </div>
                            <div class="mb-4">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Assignee</h3>
                                <AssigneeSelector
                                    v-model="task.assignee_id"
                                    :assignees="assignees"
                                    :disabled="!can.updateAssignee"
                                    :defaultAssigneeName="defaultAssigneeName"
                                    @update:modelValue="updateTask({ assignee_id: $event })"
                                />
                            </div>
                            <div class="mb-4">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Start date</h3>
                                <DateInput v-model="startDate"/>
                                <InputError :message="form.errors.start_date" class="mt-2" />
                            </div>
                            <div class="mb-4">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Due date</h3>
                                <DateInput v-model="dueDate"/>
                                <InputError :message="form.errors.due_date" class="mt-2" />
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
                        <CommentsList :task="task" :comments="comments.data" :can="can"/>
                    </div>

                    <div class="mt-4">
                        <PaginationLinks :paginator="comments" />
                    </div>
                </div>
            </div>
        </Container>
    </AuthenticatedLayout>
</template>