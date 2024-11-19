<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';

const { links, position, data } = defineProps({
    links: Array,
    position: String,
    data: String
});

const styles = computed(() => {
    if (position === 'left') return 'justify-start';
    if (position === 'center') return 'justify-center';
    return 'justify-end';
});

const reload = (link) => {
    router.visit(link.url, { only: [data], preserveState: true, replace: true });
};
</script>

<template>
    <aside v-if="links?.length > 3">
        <ul class="flex gap-2 my-6 text-sm items-center" :class="styles">
            <li v-for=" link  in  links" :key="link.label">
                <div v-if="link.url" :href="link.url" v-html="link.label" @click="reload(link)"
                    class="leading-6 font-bold inline-block hover:text-dark text-dark-highlight-variant hover:bg-dark-highlight-variant rounded-full
                focus:outline-none focus:ring-2 focus:ring-dark transition ease-in-out duration-300 px-3 py-1 h-8 cursor-pointer" :class="{
        'w-8': !link.label.toLowerCase().includes('previous') && !link.label.toLowerCase().includes('next'),
        'w-full': link.label.toLowerCase().includes('previous') || link.label.toLowerCase().includes('next'),
        'bg-transparent text-light cursor-default pointer-events-none': link.active, 'bg-transparent': !link.active
    }" />
                <span v-else v-html="link.label" class="inline-block first:w-full last:w-full"></span>
            </li>
        </ul>
    </aside>
</template>
