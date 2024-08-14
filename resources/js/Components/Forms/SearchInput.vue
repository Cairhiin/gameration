<script setup>
import { onMounted, ref } from 'vue';
import { debounce } from '@/Utils/index.ts';
import FormInput from '@/Components/Custom/FormInput.vue';

const { searchType, multiSelect, value } = defineProps({
    searchType: String,
    multiSelect: {
        type: Boolean,
        default: false
    },
    value: Array | Object
});

const emit = defineEmits(['update:modelValue']);

const debounceFn = ref(null);
const results = ref([]);
const clickResult = ref(null);
const selected = ref([]);

if (multiSelect && value) {
    selected.value = value
}

onMounted(() => {
    debounceFn.value = debounce((event) => getResults(event), 800)
});

const getResults = (event) => {
    axios.post(route(`${searchType}.search`), { search: event.target.value }).then(response => {
        results.value = response.data;
    });
}

const setResult = (result) => {
    clickResult.value = result.name

    if (multiSelect) {
        selected.value.push(result)
        clickResult.value = null
        emit('update:modelValue', selected.value)
    } else {
        emit('update:modelValue', result)
    }

    results.value = []
}

const removeFromResults = (index) => {
    selected.value.splice(index, 1);
    clickResult.value = null
    emit('update:modelValue', selected.value);
}
</script>

<template>
    <div v-if="selected.length && multiSelect">
        <ul class="flex gap-2 rounded">
            <li class="py-1 px-2 bg-sky-800 text-light text-sm flex gap-2 items-center rounded font-bold"
                v-for="(select, index) in selected" :key="select.id">{{
        select.name }} <i @click="removeFromResults(index)"
                    class="fa-solid fa-xmark text-lightVariant hover:cursor-pointer"></i></li>
        </ul>
    </div>
    <form-input type="text" @input="debounceFn($event)" :id="searchType" :name="searchType" v-model="clickResult"
        :placeholder="value ? value.name : `Search ${searchType}...`" />
    <ul class="bg-darkVariant shadow-md rounded-md">
        <li class="hover:bg-lightVariant hover:text-darkVariant hover:cursor-pointer p-2" v-for="result in results"
            :key="result.id" @click="setResult(result)">{{
        result.name }}</li>
    </ul>
</template>
