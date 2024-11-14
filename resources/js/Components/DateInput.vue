<script setup>
import dayjs from 'dayjs';

const model = defineModel({
    type: String,
    required: false,
});

defineProps({
    viewOnly: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <input
        v-if="!viewOnly"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        v-model="model"
        type="date"
    />
    <span v-else>{{ dayjs(model).format('DD MMM YYYY') }}</span>
</template>
