<script setup>
import { ref, computed, watch } from 'vue';
import {
    Combobox,
    ComboboxInput,
    ComboboxButton,
    ComboboxOptions,
    ComboboxOption,
    TransitionRoot,
} from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    task: {
        type: Object,
        required: true,
    },
    assignees: {
        type: Array,
        required: true,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update']);

const assigneesWithUnassigned = computed(() => [
    { id: null, name: 'Unassigned' },
    ...props.assignees,
]);

const selectedId = computed({
    get: () => props.task.assignee_id,
    set: (newValue) => {
        emit('update', { assignee_id: newValue });
    },
});

watch(() => props.task.assignee_id, (newValue) => {
    if (newValue !== selectedId.value) {
        selectedId.value = newValue;
    }
});

const query = ref('');
const filteredAssignees = computed(() =>
    query.value === ''
        ? assigneesWithUnassigned.value
        : assigneesWithUnassigned.value.filter((user) =>
            user.name.toLowerCase().includes(query.value.toLowerCase())
        )
);

const selectedUser = computed(() =>
    assigneesWithUnassigned.value.find(user => user.id === selectedId.value) || { id: null, name: 'Unassigned' }
);
</script>

<template>
    <Combobox v-model="selectedId">
        <div class="relative mt-1">
            <div
                class="relative w-full cursor-default overflow-hidden rounded-lg bg-white text-left shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-300 sm:text-sm"
            >
                <ComboboxInput
                    class="w-full border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 focus:ring-0"
                    :displayValue="() => selectedUser.name"
                    @change="query = $event.target.value"
                    :disabled="disabled"
                />
                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2" :disabled="disabled">
                    <ChevronUpDownIcon v-if="!disabled" class="h-5 w-5 text-gray-400" aria-hidden="true" />
                </ComboboxButton>
            </div>
            <TransitionRoot leave="transition ease-in duration-100" leaveFrom="opacity-100" leaveTo="opacity-0"
                @after-leave="query = ''">
                <ComboboxOptions
                    class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                    <div v-if="filteredAssignees.length === 0 && query !== ''"
                        class="relative cursor-default select-none px-4 py-2 text-gray-700">
                        Nothing found.
                    </div>

                    <ComboboxOption
                        v-for="user in filteredAssignees"
                        as="template"
                        :key="user.id"
                        :value="user.id"
                        v-slot="{ active, selected }"
                    >
                        <li class="relative cursor-default select-none py-2 pl-10 pr-4" :class="{
                            'bg-teal-600 text-white': active,
                            'text-gray-900': !active,
                        }">
                            <span class="block truncate" :class="{ 'font-medium': selected, 'font-normal': !selected }">
                                {{ user.name }}
                            </span>
                            <span
                                v-if="selected"
                                class="absolute inset-y-0 left-0 flex items-center pl-3"
                                :class="{ 'text-white': active, 'text-teal-600': !active }"
                            >
                                <CheckIcon class="h-5 w-5" aria-hidden="true" />
                            </span>
                        </li>
                    </ComboboxOption>
                </ComboboxOptions>
            </TransitionRoot>
        </div>
    </Combobox>
</template>
