<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { ref } from 'vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'

dayjs.extend(relativeTime);

defineProps({
    user: {
        type: Object
    },
});
</script>
<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
                <div class="sm:p-4 bg-white shadow sm:rounded-lg">
                    <!-- <p>{{ user.name }}</p>
                    <p>{{ user.email }}</p>
                    <p>{{ dayjs(user.created_at).format('DD/MMM/YYYY') }} ({{ dayjs(user.created_at).fromNow() }})</p> -->
                    <TabGroup>
                        <TabList class="flex space-x-1 rounded-xl bg-blue-900/30 p-1">
                            <Tab
                                as="template"
                                key="profile"
                                v-slot="{ selected }"
                            >
                                <button
                                    :class="[
                                        'w-full rounded-lg py-2.5 text-sm font-medium leading-5',
                                        'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none',
                                        selected
                                            ? 'bg-white text-gray-900 shadow'
                                            : 'text-white hover:bg-white/[0.14] hover:text-white',
                                    ]"
                                >
                                    Profile
                                </button>
                            </Tab>
                            <!-- <Tab
                                as="template"
                                key="roles"
                                v-slot="{ selected }"
                            >
                                <button
                                :class="[
                                        'w-full rounded-lg py-2.5 text-sm font-medium leading-5',
                                        'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none',
                                        selected
                                            ? 'bg-white text-gray-900 shadow'
                                            : 'text-white hover:bg-white/[0.14] hover:text-white',
                                    ]"
                                >
                                    Roles
                                </button>
                            </Tab>
                            <Tab
                            as="template"
                                key="tasks"
                                v-slot="{ selected }"
                            >
                                <button
                                    :class="[
                                        'w-full rounded-lg py-2.5 text-sm font-medium leading-5',
                                        'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
                                        selected
                                            ? 'bg-white text-blue-700 shadow'
                                            : 'text-blue-100 hover:bg-white/[0.14] hover:text-white',
                                    ]"
                                >
                                    Tasks
                                </button>
                            </Tab> -->
                        </TabList>

                        <TabPanels class="mt-2">
                            <TabPanel
                                :class="[
                                    'rounded-xl bg-white p-3',
                                    ' h-96 overflow-y-auto',
                                    'focus:outline-none',
                                ]"
                            >
                                <div class="flex items-center space-x-4">
                                    <div class="space-y-1">
                                        <h2 class="text-lg font-semibold">{{ user.name }}</h2>
                                        <p class="text-sm text-gray-500">{{ user.email }}</p>
                                        <p class="text-sm text-gray-500">{{ dayjs(user.created_at).format('DD/MMM/YYYY') }} ({{ dayjs(user.created_at).fromNow() }})</p>
                                    </div>
                                </div>
                            </TabPanel>
                            <!-- <TabPanel
                                :class="[
                                    'rounded-xl bg-white p-3',
                                    'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
                                ]"
                            >
                                <p>test 2</p>
                            </TabPanel>
                            <TabPanel
                                :class="[
                                    'rounded-xl bg-white p-3',
                                    'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
                                ]"
                            >
                                <p>test 3</p>
                            </TabPanel> -->
                        </TabPanels>
                    </TabGroup>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
