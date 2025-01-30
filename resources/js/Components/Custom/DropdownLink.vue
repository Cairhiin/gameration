<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';

const { href, as, icon } = defineProps({
    href: String,
    as: String,
    icon: String
});
</script>

<template>
    <div>
        <button v-if="as == 'button'" type="submit" class="block group bg-dark-highlight px-4 py-2 leading-5 text-light focus:outline-none focus:bg-dark-highlight-variant transition duration-150 ease-in-out
            hover:bg-dark-highlight-variant  hover:text-dark">
            <div class="flex items-center gap-4">
                <i v-if="icon" :class="icon" class="text-xl basis-6 text-center"></i>
                <div>
                    <div class="uppercase font-bold">
                        <slot name="header" />
                    </div>
                    <div class="text-light/50 text-sm font-normal group-hover:text-dark/70">
                        <slot name="subheader" />
                    </div>
                </div>
            </div>
        </button>

        <a v-else-if="as == 'a'" :href="href" class="block group bg-dark-highlight px-4 py-2 leading-5 text-light focus:outline-none focus:bg-dark-highlight-variant transition duration-150 ease-in-out
            hover:bg-dark-highlight-variant  hover:text-dark">
            <div class="flex items-center gap-4">
                <i v-if="icon" :class="icon" class="text-xl basis-6 text-center"></i>
                <div>
                    <div class="uppercase font-bold">
                        <slot name="header" />
                    </div>
                    <div class="text-light/50 text-sm font-normal group-hover:text-dark/70">
                        <slot name="subheader" />
                    </div>
                </div>
            </div>
        </a>

        <Link v-else :href="href" class="block group bg-dark-highlight px-4 py-2 leading-5 text-light focus:outline-none focus:bg-dark-highlight-variant transition duration-150 ease-in-out
            hover:bg-dark-highlight-variant hover:text-dark">
        <div class="flex items-center gap-4">
            <i v-if="icon" :class="icon" class="text-xl basis-6 text-center"></i>
            <div>
                <div class="uppercase font-bold">
                    <slot name="header" />
                </div>
                <div class="text-light/70 text-sm font-normal group-hover:text-dark/70">
                    <slot name="subheader" />
                </div>
            </div>
        </div>
        </Link>
    </div>
</template>
