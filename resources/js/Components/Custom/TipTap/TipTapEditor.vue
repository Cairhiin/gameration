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
            class: 'rounded-lg focus:border-0 focus:outline-none focus:ring-highlight focus:ring-2 text-light h-96 p-2 overflow-y-scroll [&::-webkit-scrollbar]:w-4 [&::-webkit-scrollbar-track]:rounded-full [&::-webkit-scrollbar-track]:bg-dark [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-neutral-500',
        },
    },
    onUpdate: ({ editor }) => {
        emit('update:modelValue', editor.getHTML())
    }
});
</script>

<template>
    <div>
        <div class="flex gap-2 items-center">
            <tip-tap-icon-button icon="fa fa-bold" buttonText="Bold"
                @click="editor.chain().focus().toggleBold().run()"></tip-tap-icon-button>
            <tip-tap-icon-button icon="fa fa-italic" buttonText="Italic"
                @click="editor.chain().focus().toggleItalic().run()"></tip-tap-icon-button>
            <tip-tap-icon-button icon="fa fa-underline" buttonText="Underline"
                @click="editor.chain().focus().toggleUnderline().run()"></tip-tap-icon-button>
        </div>
        <div class="tiptap-editor rounded-lg bg-dark-box">
            <editor-content :editor="editor" />
        </div>
    </div>
</template>
