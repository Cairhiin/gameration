<script lang="ts" setup>
import { onMounted, ref, computed } from 'vue';
import { debounce } from '@/Utils/index.ts';
import FormInput from '@/Components/Custom/FormInput.vue';
import axios from 'axios';

const { searchType, multiSelect, value, inputStyle, type, url } = defineProps({
    searchType: String,
    multiSelect: {
        type: Boolean,
        default: false
    },
    value: {
        default: null
    },
    inputStyle: String,
    type: {
        type: String,
        default: 'game'
    },
    url: {
        type: String,
        default: null
    }
});


const displayValue = computed<string>(() => {
    if (!value) return null;

    if (searchType === 'users') return value.username;
    if (searchType === 'books.series') return value.title;

    return value.name;
})

const emit = defineEmits<{
    'update:modelValue': [value: any];
}>();

const debounceFn = ref<Function>(null);
const results = ref<any[]>([]);
const clickResult = ref<string>(null);
const selected = ref<any[]>([]);

onMounted(() => {
    debounceFn.value = debounce((event: any): void => getResults(event), 800)
});

const getResults = (event: { target: HTMLInputElement }): void => {
    axios.post(route(`${searchType}.search`), { search: event.target.value, type: type }).then(response => {
        results.value = response.data;
    });
}

const setResult = (result: any): void => {
    clickResult.value = result.name;

    if (multiSelect) {
        selected.value.push(result);
        clickResult.value = null;
    }

    emit('update:modelValue', result)
    results.value = []
}

const removeFromResults = (index: number): void => {
    selected.value.splice(index, 1);
    clickResult.value = null
}

const clearResults = (): void => {
    results.value = [];
    clickResult.value = null;
}

const formatedResults = computed(() => {
    if (searchType === 'books.series') {
        return results.value.map((result: any) => {
            return {
                id: result.id,
                name: result.title
            }
        });
    }

    return results.value.map((result: any) => {
        return {
            id: result.id,
            name: searchType === 'users' ? result.username : result.name
        }
    });
});

// Format the name of the placeholder
// to be the last part of the searchType
// e.g. 'users.books' => 'books', 'books.author' => 'author'
const placeholderName = computed<string>(() => {
    return searchType.split('.').slice(-1)[0];
});
</script>

<template>
    <div class="relative bg-dark/60 rounded-3xl">
        <div v-if="selected.length && multiSelect">
            <ul class="flex gap-2 rounded mb-1">
                <li class=" bg-highlight text-light text-sm flex gap-2 items-center rounded h-8 overflow-hidden"
                    v-for="(select, index) in selected" :key="select.id">
                    <div class="px-2">{{ select.name }}</div>
                    <div
                        class="flex items-center px-2 border-l-2 border-dark/60 h-full hover:cursor-pointer hover:bg-dark/60 transition-all duration-500 ease-in-out">
                        <i @click="removeFromResults(index)" class="fa-solid fa-xmark text-lightVariant"></i>
                    </div>
                </li>
            </ul>
        </div>
        <form-input type="text" @input="debounceFn($event)" :id="searchType" :name="searchType" v-model="clickResult"
            :placeholder="value && !multiSelect ? displayValue : `Search ${placeholderName}...`"
            :class="{ 'rounded-3xl': inputStyle }" />
        <ul class="bg-dark-variant shadow-md rounded-md absolute left-0 right-0 mt-1 z-50">
            <li class="hover:bg-lightVariant hover:text-darkVariant hover:cursor-pointer p-2"
                v-for="result in formatedResults" :key="result.id" @click="setResult(result)">{{
                    result.name }}</li>
        </ul>
    </div>
</template>
