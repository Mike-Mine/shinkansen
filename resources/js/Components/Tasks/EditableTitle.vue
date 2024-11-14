<script setup>
import { ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

defineProps({
    viewOnly: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update']);

const editMode = ref(false);

const startEditing = () => {
    editMode.value = true;
};

const cancelEditing = () => {
    editMode.value = false;
};

const saveTitle = () => {
    emit('update', { title: model });
    editMode.value = false;
};
</script>

<template>
    <div class="flex items-center mb-4">
        <div v-if="!editMode" class="flex items-start space-x-2">
            <h1 class="text-2xl font-bold break-all">{{ model }}</h1>
            <button v-if="!viewOnly" @click="startEditing" class="ml-2 text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17.27L16.18 12.09L19.78 15.69L13 22.5L6.5 22.5L6.5 16L13 9.5L16.5 13M16.5 13L19 16.5M19 16.5L11 17.27" />
                </svg>
            </button>
        </div>
        <div v-else class="flex items-center w-full">
            <input
                v-model="model"
                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            />
            <button @click="saveTitle" class="ml-2 text-blue-600 hover:text-blue-900">Save</button>
            <button @click="cancelEditing" class="ml-2 text-gray-500 hover:text-gray-700">Cancel</button>
        </div>
    </div>
</template>