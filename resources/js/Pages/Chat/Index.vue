<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ChatMessage from '@/Components/ChatMessage.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head } from '@inertiajs/vue3';

defineProps(['chatMessages']);

const form = useForm({
    message: '',
});
</script>

<template>
    <Head title="Chat" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chat</h2>
        </template>

        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <form @submit.prevent="form.post(route('chat.store'), { onSuccess: () => form.reset() })">
                <textarea
                    v-model="form.message"
                    placeholder="What's on your mind?"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                ></textarea>
                <InputError :message="form.errors.message" class="mt-2" />
                <PrimaryButton class="mt-4">Send</PrimaryButton>
            </form>

            <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
                <ChatMessage
                    v-for="chatMessage in chatMessages"
                    :key="chatMessage.id"
                    :chatMessage="chatMessage"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>