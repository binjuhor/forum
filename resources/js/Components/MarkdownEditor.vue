<template>
    <div v-if="editor" class="bg-white rounded-md border-0 pb-0.5 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
        <menu class="flex divide-x border-b">
            <li>
                <button
                    @click="editor.chain().focus().toggleBold().run()"
                    type="button"
                    class="px-3 py-2 rounded-tl-md hover:bg-gray-200"
                    :class="{
                        'bg-gray-200': editor.isActive('bold')
                    }"
                >
                    <i class="ri-bold"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleItalic().run()"
                    type="button"
                    class="px-3 py-2 hover:bg-gray-200"
                    :class="{
                        'bg-gray-200': editor.isActive('italic')
                    }"
                >
                    <i class="ri-italic"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleStrike().run()"
                    type="button"
                    class="px-3 py-2 hover:bg-gray-200"
                    :class="{
                        'bg-gray-200': editor.isActive('strike')
                    }"
                >
                    <i class="ri-strikethrough"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleBlockquote().run()"
                    type="button"
                    class="px-3 py-2 hover:bg-gray-200"
                    :class="{
                        'bg-gray-200': editor.isActive('blockquote')
                    }"
                >
                    <i class="ri-double-quotes-l"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleBulletList().run()"
                    type="button"
                    class="px-3 py-2 hover:bg-gray-200"
                    :class="{
                        'bg-gray-200': editor.isActive('bulletList')
                    }"
                >
                    <i class="ri-list-unordered"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleOrderedList().run()"
                    type="button"
                    class="px-3 py-2 hover:bg-gray-200"
                    :class="{
                        'bg-gray-200': editor.isActive('orderedList')
                    }"
                >
                    <i class="ri-list-ordered"></i>
                </button>
            </li>
            <li>
                <button
                    @click="promptUserForHref"
                    type="button"
                    class="px-3 py-2 hover:bg-gray-200"
                    :class="{
                        'bg-gray-200': editor.isActive('link')
                    }"
                >
                    <i class="ri-link"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
                    type="button"
                    class="px-3 py-2 hover:bg-gray-200"
                    :class="{
                        'bg-gray-200': editor.isActive('heading' , { level: 2 })
                    }"
                >
                    <i class="ri-h-1"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleHeading({ level: 3}).run()"
                    type="button"
                    class="px-3 py-2 hover:bg-gray-200"
                    :class="{
                        'bg-gray-200': editor.isActive('heading' , { level: 3 })
                    }"
                >
                    <i class="ri-h-2"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleHeading({ level: 4}).run()"
                    type="button"
                    class="px-3 py-2 hover:bg-gray-200"
                    :class="{
                        'bg-gray-200': editor.isActive('heading' , { level: 4 })
                    }"
                >
                    <i class="ri-h-3"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleCodeBlock().run()"
                    type="button"
                    class="px-3 py-2 hover:bg-gray-200"
                    :class="{
                        'bg-gray-200': editor.isActive('codeBlock' , { level: 4 })
                    }"
                >
                    <i class="ri-code-block"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleCode().run()"
                    type="button"
                    class="px-3 py-2 hover:bg-gray-200"
                    :class="{
                        'bg-gray-200': editor.isActive('code' , { level: 4 })
                    }"
                >
                    <i class="ri-code-line"></i>
                </button>
            </li>
        </menu>
        <EditorContent :editor="editor" />
    </div>
</template>

<script setup>
import { EditorContent, useEditor } from '@tiptap/vue-3'
import { StarterKit } from '@tiptap/starter-kit'
import { watch } from 'vue'
import { Markdown } from 'tiptap-markdown'
import 'remixicon/fonts/remixicon.css'
import { Link } from '@tiptap/extension-link'

const props = defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    class: {
        type: String,
        default: 'min-h-[512px]',
    },
})

const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
    extensions: [
        StarterKit.configure({
            heading: {
                levels: [2, 3, 4],
            },
        }),
        Link.configure({
            protocols: ['http', 'https', 'mailto'],
        }),
        Markdown,
    ],
    editorProps: {
        attributes: {
            class: `${props.class} prose prose-sm max-w-none py-1.5 px-3`,
        },
    },
    onUpdate() {
        emit('update:modelValue', editor.value?.storage.markdown.getMarkdown())
    },
})

watch(() => props.modelValue, (value) => {
    if(value === editor.value?.storage.markdown.getMarkdown()) {
        return;
    }
    editor.value?.commands.setContent(value)
}, { immediate: true })

const promptUserForHref = () => {
    if(editor.value?.isActive('link')) {
        return editor.value?.chain().focus().unsetLink().run()
    }

    const href = window.prompt('Enter the URL of the link:')

    if (!href) {
        return editor.value?.chain().focus().run()
    }
    return editor.value?.chain().focus().setLink({ href }).run()
}
</script>
