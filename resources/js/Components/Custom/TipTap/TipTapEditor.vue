<script setup lang="ts">
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import TipTapIconButton from './TipTapIconButton.vue';

const props = defineProps({
    default: {
        type: String,
        default: '',
    },
    modelValue: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue']);

const editor = useEditor({
    content: props.modelValue,
    extensions: [StarterKit],
    editorProps: {
        attributes: {
            class: 'm-2 focus:outline-none text-light h-96',
        },
    },
    onUpdate: ({ editor }) => {
        emit('update:modelValue', editor.getHTML())
    }
});
</script>

<template>
    <div class="flex gap-2 my-2">
        <tip-tap-icon-button icon="fa fa-bold" buttonText="Bold"
            @click="editor.chain().focus().toggleBold().run()"></tip-tap-icon-button>
        <tip-tap-icon-button icon="fa fa-italic" buttonText="Italic"
            @click="editor.chain().focus().toggleItalic().run()"></tip-tap-icon-button>
        <tip-tap-icon-button icon="fa fa-underline" buttonText="Underline"
            @click="editor.chain().focus().toggleUnderline().run()"></tip-tap-icon-button>
    </div>
    <div class="tiptap-editor border border-lightVariant/60 rounded-lg">
        <editor-content :editor="editor" />
    </div>
</template>
