<template>
    <AppLayout title="Post list">
        <Container>
            <ul class="divide-y">
                <li v-for="post in posts.data" :key="post.id">
                    <Link :href="post.routes.show" class="px-2 py-4 block group">
                        <span class="text-bold text-lg group-hover:text-indigo-500">{{ post.title }} </span>
                        <span class="block mt-1 text-sm text-gray-600">{{ formattedDate(post) }} ago by {{ post.user.name }}</span>
                    </Link>
                </li>
            </ul>
            <Pagination :meta="posts.meta" />
        </Container>
    </AppLayout>
</template>

<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Pagination from "@/Components/Pagination.vue";
import { Link } from '@inertiajs/vue3'
import {relativeDate} from "@/Utilities/date.js";

defineProps({
    posts: {
        type: Array,
        required: true,
    },
})

const formattedDate = (post) => relativeDate(post.created_at);
</script>
