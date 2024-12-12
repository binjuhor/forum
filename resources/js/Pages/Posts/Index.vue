<template>
  <AppLayout title="Post list">
    <Container>
      <div class="px-4">
        <PageHeading v-text="selectedTopic ? selectedTopic.name : 'All Posts'"/>
        <p v-if="selectedTopic" class="mt-1 text-gray-600 text-sm">{{ selectedTopic.description }}</p>

        <menu class="flex space-x-1 mt-3 overflow-x-auto pb-2 pt-1">
          <li>
            <Pill
              :href="route('posts.index')"
              :filled="!selectedTopic"
            >All Posts
            </Pill>
          </li>
          <li v-for="topic in topics" :key="topic.id">
            <Pill
              :href="route('posts.index', { topic: topic.slug })"
              :filled="topic.id === selectedTopic?.id"
            >
              {{ topic.name }}
            </Pill>
          </li>
        </menu>
      </div>
      <ul class="divide-y mt-4">
        <li v-for="post in posts.data" :key="post.id"
            class="flex justify-between flex-col md:flex-row items-baseline px-2">
          <Link :href="post.routes.show" class="px-2 py-4 block group">
            <span class="text-bold text-lg group-hover:text-indigo-500">{{ post.title }} </span>
            <span class="block mt-1 text-sm text-gray-600">{{ formattedDate(post) }} ago by {{ post.user.name }}</span>
          </Link>
          <Pill :href="route('posts.index', { topic: post.topic.slug })">
            {{ post.topic.name }}
          </Pill>
        </li>
      </ul>
      <Pagination :meta="posts.meta"/>
    </Container>
  </AppLayout>
</template>

<script setup>

import AppLayout from '@/Layouts/AppLayout.vue'
import Container from '@/Components/Container.vue'
import Pagination from '@/Components/Pagination.vue'
import { Link } from '@inertiajs/vue3'
import { relativeDate } from '@/Utilities/date.js'
import PageHeading from '@/Components/PageHeading.vue'
import Pill from '@/Components/Pill.vue'

defineProps({
  posts: {
    type: Array,
    required: true,
  },
  topics: {
    type: Array,
    required: false,
    default: () => [],
  },
  selectedTopic: {
    id: Number,
    slug: String,
    name: String,
    description: String,
  },
})

const formattedDate = (post) => relativeDate(post.created_at)
</script>
