<template>
    <AppLayout :title="post.title">
        <Container>
            <h1 class="text-2xl font-bold">{{ post.title }}</h1>
            <span class="block mt-1 text-sm text-gray-600">{{ formattedDate }} ago by {{ post.user.name }}</span>

            <article class="mt-6">
                <pre class="whitespace-pre-wrap font-sans">{{ post.body }}</pre>
            </article>

            <div class="mt-12">
                <h2 class="text-xl font-semibold">Comments</h2>
                <form v-if="$page.props.auth.user" @submit.prevent="addComment">
                    <div>
                        <InputLabel for="body" class="sr-only">Comment</InputLabel>
                        <TextArea id="body" rows="4" v-model="commentForm.body" placeholder="Speak your mind Spoc..."/>
                        <InputError :message="commentForm.errors.body" class="mt-2"/>
                    </div>

                    <PrimaryButton type="submit" class="mt-4" :disabled="commentForm.processing">Add Comment
                    </PrimaryButton>
                </form>

                <ul class="divide-y">
                    <li v-for="comment in comments.data" :key="post.id" class="px-2 py-4 block group">
                        <Comment @delete="deleteComment" :comment="comment"/>
                    </li>
                </ul>

                <Pagination :meta="comments.meta"/>
            </div>
        </Container>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { computed } from 'vue'
import { relativeDate } from '@/Utilities/date.js'
import Container from '@/Components/Container.vue'
import Pagination from '@/Components/Pagination.vue'
import Comment from '@/Components/Comment.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { router, useForm } from '@inertiajs/vue3'
import TextArea from '@/Components/TextArea.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps(['post', 'comments'])

const formattedDate = computed(() => relativeDate(props.post.created_at))

const commentForm = useForm({
    body: '',
})

const addComment = () => commentForm.post(route('posts.comments.store', props.post.id), {
    preserveScroll: true,
    onSuccess: () => commentForm.reset(),
})

const deleteComment = (commentId) => router.delete(route('comments.destroy', { comment: commentId, page: props.comments.meta.current_page }), {
    preserveScroll: true,
})
</script>
