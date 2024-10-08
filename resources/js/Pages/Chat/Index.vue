<script setup>
import { usePage, useForm, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ChatMessage from '@/Components/ChatMessage.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head } from '@inertiajs/vue3';
import Container from '@/Components/Container.vue';

const page = usePage();

const chatMessages = computed(() => page.props.chatMessages);

defineProps({
    can: Object,
});

const form = useForm({
    message: '',
});

const submit = () => {
    form.post(route('chat.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

Echo.private('chat')
    .listen('ChatMessageCreated', () => {
        router.reload({ only: ['chatMessages'] });
    });
</script>

<template>
    <Head title="Chat" />

    <AuthenticatedLayout>
        <Container>
            <form @submit.prevent="submit">
                <textarea
                    v-model="form.message"
                    placeholder="What's on your mind?"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                ></textarea>
                <InputError :message="form.errors.message" class="mt-2" />
                <PrimaryButton class="mt-4" :disabled="form.processing">Send</PrimaryButton>
            </form>

            <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
                <ChatMessage
                    v-for="chatMessage in chatMessages"
                    :key="chatMessage.id"
                    :chatMessage="chatMessage"
                    :can="can"
                />
            </div>
        </Container>
    </AuthenticatedLayout>
</template>