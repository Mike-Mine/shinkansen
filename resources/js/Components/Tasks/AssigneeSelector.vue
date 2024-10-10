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

const model = defineModel({
    type: Number,
    required: true,
});

const props = defineProps({
    assignees: {
        type: Object,
        required: true,
    },
    defaultAssigneeName: {
        type: String,
        required: true,
    },
    disabled: {
        type: Boolean,
        default: false,
    }
});

const query = ref('');
const filteredAssignees = computed(() =>
    query.value === ''
        ? props.assignees
        : props.assignees.filter((assignee) =>
            assignee.name.toLowerCase().includes(query.value.toLowerCase())
        )
);

const selectedUser = computed(() =>
    props.assignees.find(assignee => assignee.id === model.value) || { id: null, name: props.defaultAssigneeName }
);
</script>

<template>
    <Combobox v-model="model">
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
                        v-for="assignee in filteredAssignees"
                        :key="assignee.id"
                        :value="assignee.id"
                        v-slot="{ active, selected }"
                    >
                        <li class="relative cursor-default select-none py-2 pl-10 pr-4" :class="{
                            'bg-teal-600 text-white': active,
                            'text-gray-900': !active,
                        }">
                            <span class="block truncate" :class="{ 'font-medium': selected, 'font-normal': !selected }">
                                {{ assignee.name }}
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
