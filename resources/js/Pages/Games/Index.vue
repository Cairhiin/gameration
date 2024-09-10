<script setup>
import { ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import image from '../../../images/missing_image_light.png';
import SubHeader from '@/Components/Custom/SubHeader.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AdminCreateSection from '@/Components/Custom/AdminCreateSection.vue';

const page = usePage();
const hasModerationRights = page.props.auth.user.role.name === 'Admin' || page.props.auth.user.role.name === 'Moderator';

const { games } = defineProps({
    games: Object
});

const sortBy = ref('released_at');
const sortOrder = ref('desc');

const setSortBy = (value) => {
    if (sortBy.value === value) {
        sortOrder.value = sortOrder.value === 'desc' ? 'asc' : 'desc';
    } else if (value === 'name') {
        sortOrder.value = 'asc'
    } else {
        sortOrder.value = 'desc';
    }

    sortBy.value = value;

    router.get(route('games.index'), { sortBy: sortBy.value, sortOrder: sortOrder.value }, { preserveState: true });
}
</script>

<template>
    <app-layout title="Games">
        <nav>
            <ul class="flex justify-end gap-4 bg-highlight p-2 text-sm uppercase border-b-2 border-dark/50">
                <li @click="setSortBy('name')" class="flex gap-2 items-center cursor-pointer">Alphabetical
                    <i class="fa-solid fa-chevron-down text-xs text-lightVariant transition-all" :class="{
                    'rotate-180': sortOrder === 'asc', 'text-lightVariant/0': sortBy !== 'name'
                }"></i>
                </li>
                <li @click="setSortBy('avg_rating')" class="flex gap-2 items-center cursor-pointer">Rating
                    <i class="fa-solid fa-chevron-down text-xs text-lightVariant transition-all" :class="{
                    'rotate-180': sortOrder === 'asc', 'text-lightVariant/0': sortBy !== 'avg_rating'
                }"></i>
                </li>
                <li @click="setSortBy('released_at')" class="flex gap-2 items-center cursor-pointer">
                    Release Date
                    <i class="fa-solid fa-chevron-down text-xs text-lightVariant transition-all" :class="{
                    'rotate-180': sortOrder === 'asc', 'text-lightVariant/0': sortBy !== 'released_at'
                }"></i>
                </li>
            </ul>
        </nav>

        <section class="backdrop-blur-sm">
            <ul>
                <li v-for="game in games.data " :key="game.id" class="my-2 rounded-md odd:bg-darkVariant/25 even:bg-darkVariant/25 hover:bg-lightVariant/15 group
                    border border-darkVariant shadow-dark-sm">
                    <Link :href="route('games.show', game)" class="flex justify-between">
                    <div class="flex gap-4">
                        <div class="overflow-hidden w-24 h-32 rounded-l-md">
                            <img :src="game.image ? `/storage/${game.image}` : image" :alt="game.name"
                                class="object-cover group-hover:scale-125 transition-all w-24 h-32">
                        </div>
                        <div class="py-2">
                            <sub-header level="h4" class="font-bold uppercase text-xl text-lightVariant">{{ game.name
                                }}</sub-header>
                            <div>{{ game.developer?.name }} ({{ new Date(game.released_at).getFullYear() }})</div>
                        </div>
                    </div>
                    <span class="my-auto px-2 text-lg">{{
                    game.avg_rating ??
                    '-' }} ({{ game.rating_count
                        }})</span>
                    </Link>
                </li>
            </ul>
        </section>

        <aside>
            <ul class="flex gap-2" v-if="games?.links?.length > 3">
                <li v-for=" link  in  games.links" :key="link.label" class="border rounded px-3 py-1">
                    <Link v-if="link.url" :href="link.url" v-html="link.label" />
                    <span v-else v-html="link.label"></span>
                </li>
            </ul>
        </aside>

        <admin-create-section :has-moderation-rights="hasModerationRights" />
    </app-layout>
</template>
