<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    paginator: Object,
})

</script>

<template>
    <div class="flex justify-between items-start">
        <div class="flex items-center rounded-md overflow-hidden shadow-l">
            <Link
                v-for="link in paginator.links"
                :key="link.url"
                :href="link.url ?? paginator.path + '?page=' + paginator.current_page"
                v-html="link.label"
                class="px-2 transition duration-150 ease-in-out"
                :class="{
                    'text-zinc-400': !link.url,
                    'text-indigo-500': link.active,
                    'bg-indigo-200 rounded-full': link.active,
                }"
            />
        </div>
    </div>
</template>