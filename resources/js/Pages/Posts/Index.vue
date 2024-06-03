<template>
    <AppLayout title="Post list">
        <Container>
            <div class="px-4">
                <Link v-if="selectedTopic" :href="route('posts.index')" class="text-indigo-500 hover:text-indigo-700 mb-2 text-sm">Back to all Posts</Link>
                <PageHeading v-text="selectedTopic ? selectedTopic.name : 'All Posts'" />
                <p v-if="selectedTopic" class="mt-1 text-gray-600 text-sm">{{ selectedTopic.description }}</p>
            </div>
            <ul class="divide-y mt-4">
                <li v-for="post in posts.data" :key="post.id" class="flex justify-between flex-col md:flex-row items-baseline px-2">
                    <Link :href="post.routes.show" class="px-2 py-4 block group">
                        <span class="text-bold text-lg group-hover:text-indigo-500">{{ post.title }} </span>
                        <span class="block mt-1 text-sm text-gray-600">{{ formattedDate(post) }} ago by {{ post.user.name }}</span>
                    </Link>
                    <Link :href="route('posts.index', { topic: post.topic.slug })" class="rounded-full py-0.5 px-2 border border-pink-500 text-pink-500 text-xs hover:bg-indigo-500 hover:text-white mb-5">
                        {{ post.topic.name }}
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
import PageHeading from '@/Components/PageHeading.vue';

defineProps({
    posts: {
        type: Array,
        required: true,
    },
    selectedTopic: {
        id: Number,
        slug: String,
        name: String,
        description: String,
    }
})

const formattedDate = (post) => relativeDate(post.created_at);
</script>
