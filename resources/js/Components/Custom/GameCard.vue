<script setup>
import { Link } from '@inertiajs/vue3';
import SubHeader from '@/Components/Custom/SubHeader.vue';
import image from '../../../images/missing_image_light.png';

const { game } = defineProps({
    game: Object
});
</script>

<template>
    <div class="bg-dots-darker drop-shadow-md rounded-md">
        <div class="group my-2 pr-2 rounded-md  group transition-all duration-300 ease-in
    ">
            <Link :href="route('games.show', game)" class="flex justify-between group-hover:from-dark-highlight-variant group-hover:text-dark/80 group-hover:font-bold
                bg-gradient-to-r from-dark-box-40 via-dark-box-40 to-transparent" v-if="game">
            <div class="flex items-center gap-4">
                <div class="overflow-hidden w-16 h-16 rounded-l-md shrink-0">
                    <img :src="game.image ? `/storage/${game.image}` : image" :alt="game.name"
                        class="object-cover group-hover:scale-125 transition-all w-24 h-32">
                </div>
                <div class="shrink min-h-16 flex flex-col justify-center">
                    <sub-header level="h4"
                        class="font-bold uppercase text-xl text-slate-400 group-hover:text-dark line-clamp-1">{{
                    game.name
                }}</sub-header>
                    <div class="leading-7 line-clamp-1">{{ game.developer?.name }} ({{ new
                    Date(game.released_at).getFullYear() }})</div>
                </div>
            </div>
            <div class="shrink-0 my-auto px-2 text-xl">{{
                    game.avg_rating?.toFixed(1) ??
                    '-' }} <span class="hidden sm:inline">({{ game.rating_count
                    }})</span></div>
            </Link>
        </div>
    </div>
</template>

<style scoped>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,<svg id='patternId' opacity='0.03' width='100%' height='100%' xmlns='http://www.w3.org/2000/svg'><defs><pattern id='a' patternUnits='userSpaceOnUse' width='80' height='46.185' patternTransform='scale(1) rotate(0)'><rect x='0' y='0' width='100%' height='100%' fill='hsla(240,6.7%,17.6%,1)'/><path d='M0 .002V13.63l9.87-5.69L21.944.975l7.373 4.239C19.536 10.843 9.782 16.503 0 22.135v24.05h.842V23.557l.145-.087C10.71 17.868 20.436 12.266 30.13 6.664v8.476c-4.006 2.35-8.041 4.673-12.076 6.995-3.28 1.887-6.56 3.803-9.869 5.69v18.359H9.87v-6.022a1928.14 1928.14 0 0 1 10.396 6.023h3.338l-1.659-.972c-4.005-2.323-8.04-4.645-12.075-6.967V29.74l28.45 16.445h3.389c9.454-5.482 18.938-10.963 28.421-16.444v8.505a4590.557 4590.557 0 0 1-12.076 6.967l-1.63.972h3.285v-.016c3.483-2.004 6.966-4.006 10.421-6.01v6.024h1.684v-18.36l-6.01-3.483-3.86-2.206a2547.983 2547.983 0 0 1-12.076-6.996V6.662c9.754 5.631 19.536 11.263 29.29 16.894v22.627H80v-24.05L50.682 5.211 58.055.972 70.131 7.94 80 13.63V0h-.842v11.22l-7.344-4.24V0h-1.683v6.023L59.735 0h-3.36l-8.189 4.717v25.37l-7.344 4.238V.48l.146-.087L41.63 0h-3.26l.788.48v33.845l-7.344-4.238V4.717l-6.008-3.454L23.653 0h-3.387L9.87 6.023V0H8.186v6.982L.842 11.22V0zm30.13 17.083v12.017l-10.42-6.009c3.483-2.003 6.966-4.006 10.42-6.008zm19.74 0c3.483 2.002 6.937 4.005 10.42 6.008l-10.42 6.009zm-31.814 6.967 12.075 6.995 9.87 5.69 9.868-5.69 12.076-6.995 7.373 4.237c-9.782 5.66-29.317 16.924-29.317 16.924L10.683 28.289z'  stroke-width='1' stroke='none' fill='hsla(47,80.9%,61%,1)'/></pattern></defs><rect width='800%' height='800%' transform='translate(0,0)' fill='url(%23a)'/></svg>")
}
</style>
