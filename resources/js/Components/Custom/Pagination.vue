<script lang="ts" setup>
import { computed, type PropType } from 'vue';
import { router } from '@inertiajs/vue3';
import type { Link } from '@/Types';

const { links, position, data } = defineProps({
    links: Object as PropType<Link[]>,
    position: String,
    data: String
});

const styles = computed<string>(() => {
    if (position === 'left') return 'justify-start';
    if (position === 'center') return 'justify-center';
    return 'justify-end';
});

const reload = (link: Link): void => {
    router.visit(link.url, { only: [data], preserveState: true, replace: true });
};
</script>

<template>
    <aside v-if="links?.length > 3">
        <ul class="flex gap-2 my-6 text-sm items-center" :class="styles">
            <li v-for="link in links" :key="link.label">
                <div v-if="link.url" :href="link.url" v-html="link.label" @click="reload(link)"
                    class="leading-6 font-bold inline-block hover:text-light text-lightVariant/75
                focus:outline-none focus:ring-2 focus:ring-dark transition ease-in-out duration-300 px-3 py-1 h-8 cursor-pointer" :class="{
                    'w-8': !link.label.toLowerCase().includes('previous') && !link.label.toLowerCase().includes('next'),
                    'w-full': link.label.toLowerCase().includes('previous') || link.label.toLowerCase().includes('next'),
                    'bg-transparent text-lightVariant/25 cursor-default pointer-events-none': link.active, 'bg-transparent': !link.active
                }" />
                <span v-else v-html="link.label"
                    class="inline-block first:w-full last:w-full text-lightVariant/25"></span>
            </li>
        </ul>
    </aside>
</template>
