<script setup>

const model = defineModel({
    type: String,
    required: false,
});
</script>

<template>
    <input
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        v-model="model"
        type="date"
    />
</template>
