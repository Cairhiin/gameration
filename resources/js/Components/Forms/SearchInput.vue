<script lang="ts" setup>
import { onMounted, ref, computed } from 'vue';
import { debounce } from '@/Utils/index.ts';
import FormInput from '@/Components/Custom/FormInput.vue';
import axios from 'axios';

const { searchType, multiSelect, value, inputStyle } = defineProps({
    searchType: String,
    multiSelect: {
        type: Boolean,
        default: false
    },
    value: {
        default: null
    },
    inputStyle: String
});


const displayValue = computed<string>(() => {
    if (!value) return null;

    if (searchType === 'users') return value.username;

    return value.name;
})

const emit = defineEmits<{
    'update:modelValue': [value: any];
}>();

const debounceFn = ref<Function>(null);
const results = ref<any[]>([]);
const clickResult = ref<string>(null);
const selected = ref<any[]>([]);

/* if (multiSelect && value) {
    console.log(value)
    selected.value = value
} */

onMounted(() => {
    debounceFn.value = debounce((event: any): void => getResults(event), 800)
});

const getResults = (event: { target: HTMLInputElement }): void => {
    axios.post(route(`${searchType}.search`), { search: event.target.value }).then(response => {
        console.log(response.data)
        results.value = response.data;
    });
}

const setResult = (result: any): void => {
    clickResult.value = searchType === 'users' ? result.username : result.name;

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
    emit('update:modelValue', selected.value);
}

const clearResults = (): void => {
    results.value = [];
    clickResult.value = null;
}

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
            <ul class="flex gap-2 rounded">
                <li class="py-1 px-2 bg-sky-800 text-light text-sm flex gap-2 items-center rounded font-bold"
                    v-for="(select, index) in selected" :key="select.id">{{
                        select.name }} <i @click="removeFromResults(index)"
                        class="fa-solid fa-xmark text-lightVariant hover:cursor-pointer"></i></li>
            </ul>
        </div>
        <form-input type="text" @input="debounceFn($event)" :id="searchType" :name="searchType" v-model="clickResult"
            :placeholder="value && !multiSelect ? displayValue : `Search ${placeholderName}...`"
            :class="{ 'rounded-3xl': inputStyle }" />
        <ul class="bg-darkVariant shadow-md rounded-md absolute left-2 right-2 mt-1 z-50">
            <li class="hover:bg-lightVariant hover:text-darkVariant hover:cursor-pointer p-2" v-for="result in results"
                :key="result.id" @click="setResult(result)">{{
                    searchType === 'users' ? result.username : result.name }}</li>
        </ul>
    </div>
</template>
