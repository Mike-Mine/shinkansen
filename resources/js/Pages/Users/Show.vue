<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';
import Container from '@/Components/Container.vue';

dayjs.extend(relativeTime);

const props = defineProps({
    user: Object,
    allRoles: Array,
    can: Object,
});

const form = useForm({
    roles: props.user.roles.map(role => role.name),
});

const hasChanges = computed(() => {
    return JSON.stringify(form.roles) !== JSON.stringify(props.user.roles.map(role => role.name));
});

const resetChanges = () => {
    form.reset();
};
</script>

<template>
    <Head title="Edit User" />

    <AuthenticatedLayout>
        <Container>
            <div class="sm:p-4 bg-white shadow sm:rounded-lg">
                <TabGroup>
                    <TabList class="flex space-x-1 rounded-xl bg-blue-900/30 p-1">
                        <Tab as="template" key="profile" v-slot="{ selected }">
                            <button
                                :class="[
                                    'w-full rounded-lg py-2.5 text-sm font-medium leading-5',
                                    'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none',
                                    selected ? 'bg-white text-gray-900 shadow' : 'text-white hover:bg-white/[0.14] hover:text-white',
                                ]"
                            >
                                Profile
                            </button>
                        </Tab>
                        <Tab as="template" key="roles" v-slot="{ selected }">
                            <button
                                :class="[
                                    'w-full rounded-lg py-2.5 text-sm font-medium leading-5',
                                    'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none',
                                    selected ? 'bg-white text-gray-900 shadow' : 'text-white hover:bg-white/[0.14] hover:text-white',
                                ]"
                            >
                                Roles
                            </button>
                        </Tab>
                    </TabList>

                    <TabPanels class="mt-2">
                        <TabPanel
                            :class="[
                                'rounded-xl bg-white p-3',
                                'h-96 overflow-y-auto',
                                'focus:outline-none',
                            ]"
                        >
                            <div class="flex items-center space-x-4">
                                <div class="space-y-1">
                                    <div class="relative w-28 h-28 rounded-full overflow-hidden border border-slate-300">
                                        <img
                                            :src="user.avatar ? `/storage/${user.avatar}` : '/storage/avatars/default_profile_image.jpg'"
                                            class="object-cover w-28 h-28"
                                        />
                                    </div>
                                    <h2 class="text-lg font-semibold">{{ user.name }}</h2>
                                    <p class="text-sm text-gray-500">{{ user.email }}</p>
                                    <p class="text-sm text-gray-500">{{ dayjs(user.created_at).format('DD/MMM/YYYY') }} ({{ dayjs(user.created_at).fromNow() }})</p>
                                </div>
                            </div>
                        </TabPanel>

                        <TabPanel
                            :class="[
                                'rounded-xl bg-white p-3',
                                'h-96 overflow-y-auto',
                                'focus:outline-none',
                            ]"
                        >
                            <div v-for="role in allRoles" :key="role.name" class="mb-2">
                                <label class="inline-flex items-center">
                                    <input
                                        type="checkbox"
                                        :value="role.name"
                                        v-model="form.roles"
                                        class="rounded-full h-5 w-5 border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        :disabled="!can.update"
                                    />
                                    <span class="ml-2">{{ role.name }}</span>
                                </label>
                            </div>

                            <div v-if="hasChanges && can.update" class="mt-4">
                                <button @click="form.put(route('users.update', user.id))" class="text-blue-600 hover:text-blue-900">Save</button>
                                <button @click="resetChanges" class="ml-2 text-gray-500 hover:text-gray-700">Cancel</button>
                            </div>
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </Container>
    </AuthenticatedLayout>
</template>
