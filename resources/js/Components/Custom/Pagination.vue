<script setup>
import { Link } from '@inertiajs/vue3';

const { links } = defineProps({
    links: Array
});
const emit = defineEmits(['changePage']);
</script>

<template>
    <aside>
        <ul class="flex justify-end gap-2 my-6 text-sm" v-if="links?.length > 3">
            <li v-for=" link  in  links" :key="link.label" class="bg-highlight/50 rounded">
                <Link v-if="link.url" :href="link.url" v-html="link.label" @click.prevent="emit('changePage', link.url)"
                    class="inline-block w-full h-full hover:bg-highlight px-3 py-1 rounded" />
                <span v-else v-html="link.label" class="inline-block w-full h-full px-3 py-1"></span>
            </li>
        </ul>
    </aside>
</template>
