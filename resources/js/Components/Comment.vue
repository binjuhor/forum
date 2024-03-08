<template>
    <div class="sm:flex">
        <div class="mb-4 flex-shrink-0 sm:mb-0 sm:mr-4">
            <img :src="comment.user.profile_photo_url" alt="" class="w-10 h-10 rounded-full">
        </div>
        <div class="flex-1">
            <div class="mt-1 prose prose-sm max-w-none" v-html="comment.html"></div>
            <span class="first-letter:uppercase block pt-1 text-xs text-gray-600">By {{ comment.user.name }} {{ relativeDate(comment.created_at)}} ago</span>
            <div class="mt-2 flex justify-end space-x-3 empty:hidden">
                <form  v-if="comment.can?.update" @submit.prevent="$emit('edit', comment.id)">
                    <button type="submit" class="text-sm font-mono  hover:font-bold">Edit</button>
                </form>
                <form  v-if="comment.can?.delete" @submit.prevent="$emit('delete', comment.id)">
                    <button type="submit" class="text-sm font-mono text-red-700 hover:font-bold">Delete</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {relativeDate} from "@/Utilities/date.js";

    const props = defineProps({
        comment: {
            type: Object,
            required: true,
        },
    })

    const emit = defineEmits(['delete', 'edit'])
</script>
