<template>
    <Link
        :href="href"
        class="whitespace-nowrap rounded-full py-0.5 px-2 border border-pink-500 text-pink-500 text-xs hover:bg-indigo-500 hover:text-white mb-5"
        :class="{ 'bg-indigo-500 !text-indigo-50': filled }"
    >
        <slot/>
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({
    href: {
        type: String,
        required: true,
    },
    filled: {
        type: Boolean,
        default: false,
    },
})
</script>
