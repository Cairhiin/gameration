<script lang="ts" setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    message: {
        type: String,
        default: ''
    }
});
const isShowing = ref<boolean>(false);

onMounted(() => {
    isShowing.value = props.show;

    const hideFlash = () => isShowing.value = false;
    setTimeout(hideFlash, 3000);
});
</script>

<template>
    <Teleport to="body">
        <transition enter-active-class="tw-transition tw-ease-in tw-duration-500" enter-from-class="tw-translate-y-16"
            enter-to-class="tw-translate-y-0" leave-active-class="tw-transition tw-ease-in tw-duration-500"
            leave-from-class="tw-translate-y-0" leave-to-class="tw-translate-y-16">
            <div v-show="isShowing"
                class="tw-py-2 tw-px-4 tw-fixed tw-left-[calc((100%-24rem)/2)] tw-bottom-0 tw-bg-emerald-600 tw-text-zinc-100 tw-min-w-96 tw-h-12 tw-rounded">
                <div class="tw-flex tw-justify-center tw-items-center tw-h-full">{{ message }}</div>
            </div>
        </transition>
    </Teleport>
</template>
