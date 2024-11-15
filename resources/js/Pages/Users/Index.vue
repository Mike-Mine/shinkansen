<script setup>
import Container from '@/Components/Container.vue';
import PaginationLinks from '@/Components/PaginationLinks.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { debounce } from 'lodash';
import { throttle } from 'lodash';
import { ref, watch } from 'vue';

dayjs.extend(relativeTime);

const props = defineProps({
    users: {
        type: Object,
        required: true
    },
    searchQuery: {
        type: String,
        required: true
    }
});

const search = ref(props.searchQuery);

watch(search, debounce(
    (query) => router.get('/users', { search: query }, { preserveState: true }),
    500
));

</script>
<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <Container>
            <div class="flex justify-end mb-4">
                <div class="w-1/4">
                    <input
                        type="search"
                        v-model="search"
                        placeholder="Search"
                        class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                    />
                </div>
            </div>
            <table class="sm:p-4 w-full text-left table-auto shadow sm:rounded-lg bg-white">
                <thead class="bg-gray-200 rounded-t-lg">
                    <tr>
                        <th class="px-4 py-3 font-bold rounded-tl-lg">Avatar</th>
                        <th class="px-4 py-3 font-bold">Name</th>
                        <th class="px-4 py-3 font-bold">Email</th>
                        <th class="px-4 py-3 font-bold rounded-tr-lg">Registered at</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users.data" :key="user.id" class="border-b border-gray-200 hover:bg-gray-100">
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
                        <td class="px-4 py-3">
                            {{ dayjs(user.created_at).format('DD/MMM/YYYY') }} ({{ dayjs(user.created_at).fromNow() }})
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="mt-4">
                <PaginationLinks :paginator="users" />
            </div>
        </Container>
    </AuthenticatedLayout>
</template>
