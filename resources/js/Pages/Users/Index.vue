<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';

dayjs.extend(relativeTime);

defineProps({
    users: {
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
                    <table class="w-full text-left table-auto sm:rounded-lg">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-3 font-bold">Avatar</th>
                            <th class="px-4 py-3 font-bold">Name</th>
                            <th class="px-4 py-3 font-bold">Email</th>
                            <th class="px-4 py-3 font-bold">Registered at</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id" class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="px-4 py-3">
                                <img
                                    :src="user.avatar
                                        ? `/storage/${user.avatar}`
                                        : '/storage/avatars/default_profile_image.jpg'
                                    "
                                    alt="avatar"
                                    class="w-8 h-8 rounded-full overflow-hidden object-center object-cover"
                                >
                            </td>
                            <td class="px-4 py-3">
                                <Link :href="route('users.show', user.id)" class="hover:underline">{{ user.name }}</Link>
                            </td>
                            <td class="px-4 py-3">{{ user.email }}</td>
                            <td class="px-4 py-3">{{ dayjs(user.created_at).format('DD/MMM/YYYY') }} ({{ dayjs(user.created_at).fromNow() }})</td>
                        </tr>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
