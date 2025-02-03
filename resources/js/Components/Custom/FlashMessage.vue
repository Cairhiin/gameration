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
        <transition enter-active-class="transition ease-in duration-500" enter-from-class="translate-y-16"
            enter-to-class="translate-y-0" leave-active-class="transition ease-in duration-500"
            leave-from-class="translate-y-0" leave-to-class="translate-y-16">
            <div v-show="isShowing"
                class="py-2 px-4 fixed left-[calc((100%-24rem)/2)] bottom-0 bg-emerald-600 text-zinc-100 min-w-96 h-12 rounded">
                <div class="flex justify-center items-center h-full">{{ message }}</div>
            </div>
        </transition>
    </Teleport>
</template>
