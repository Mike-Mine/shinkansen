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

const saveDescription = () => {
    emit('update', { description: model });
    editMode.value = false;
};
</script>

<template>
    <div class="mt-4">
        <div v-if="!editMode">
            <h2 class="text-lg font-semibold mb-2">Description</h2>
            <p>{{ model }}</p>
            <button v-if="!viewOnly" @click="startEditing" class="text-sm text-gray-500 hover:text-gray-700">
                Edit Description
            </button>
        </div>
        <div v-else class="flex flex-col">
            <textarea
                v-model="model"
                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                rows="4"
            ></textarea>
            <div class="mt-2">
                <button @click="saveDescription" class="text-blue-600 hover:text-blue-900">Save</button>
                <button @click="cancelEditing" class="ml-2 text-gray-500 hover:text-gray-700">Cancel</button>
            </div>
        </div>
    </div>
</template>
