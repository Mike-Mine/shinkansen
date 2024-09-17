<script setup>
import { ref, watch, computed } from 'vue';
import {
    Listbox,
    ListboxLabel,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { formatStatus, getStatusClass } from '@/src/utils/taskStatus';

const props = defineProps({
    task: {
        type: Object,
        required: true,
    },
    statuses: {
        type: Object,
        required: true
    },
    disabled: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update']);

const selectedStatus = computed({
    get: () => props.task.status,
    set: (newValue) => {
        emit('update', { status: newValue });
    },
});

watch(() => props.task.status, (newValue) => {
    if (newValue !== selectedStatus.value) {
        selectedStatus.value = newValue;
    }
});
</script>

<template>
    <Listbox v-model="selectedStatus">
        <div class="relative mt-1">
            <ListboxButton
                :class="[
                    getStatusClass(selectedStatus),
                    'relative w-half cursor-default rounded-lg py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm'
                ]"
                :disabled="disabled"
            >
                <span class="block truncate">{{ formatStatus(selectedStatus) }}</span>
                <span
                    v-if="!disabled"
                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                >
                    <ChevronUpDownIcon
                        class="h-5 w-5 text-gray-400"
                        aria-hidden="true"
                    />
                </span>
            </ListboxButton>

            <transition
                leave-active-class="transition duration-100 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <ListboxOptions
                    class="absolute z-50 mt-1 max-h-60 w-half overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm"
                >
                    <ListboxOption
                        v-slot="{ active, selected }"
                        v-for="status in statuses"
                        :key="status"
                        :value="status"
                        as="template"
                    >
                        <li
                            class="relative text-gray-900 cursor-default select-none py-2 px-4 hover:bg-gray-100"
                            :hidden="selected"
                        >
                            <span>
                                {{ formatStatus(status) }}
                            </span>
                        </li>
                    </ListboxOption>
                </ListboxOptions>
            </transition>
        </div>
    </Listbox>
</template>
