<template>
    <Head>
        <meta name="canonical" :content="post.slug"/>
    </Head>
    <AppLayout :title="post.title">
        <Container>
            <Pill :href="route('posts.index', {topic: post.topic.slug})" class="mb-4">{{ post.topic.name }}</Pill>
            <PageHeading>{{ post.title }}</PageHeading>
            <span class="block mt-1 text-sm text-gray-600">{{ formattedDate }} by {{ post.user.name }}</span>

            <div class="mt-4">
                <span class="text-pink-500 font-bold">{{ post.likes_count }} likes</span>
            </div>

            <article class="mt-6 prose prose-sm max-w-none" v-html="post.html"></article>

            <div class="mt-12">
                <h2 class="text-xl font-semibold">Comments</h2>
                <form v-if="$page.props.auth.user" @submit.prevent="() => commentIdBeingEdited ? updateCommnent() : addComment()">
                    <div>
                        <InputLabel for="body" class="sr-only">Comment</InputLabel>
                        <MarkdownEditor v-model="commentForm.body" class="min-h-[100px]" ref="commentEditorRef" placeholder="Speak your mind Spoc..."/>
                        <InputError :message="commentForm.errors.body" class="mt-2"/>
                    </div>

                    <PrimaryButton
                        type="submit"
                        class="mt-4"
                        :class="{ 'opacity-25': commentForm.processing }"
                        :disabled="commentForm.processing"
                        v-text="commentIdBeingEdited ? 'Update Comment' : 'Add Comment'"
                    ></PrimaryButton>
                    <SecondaryButton v-if="commentIdBeingEdited" @click="cancelEditComment" class="ml-2">Cancel</SecondaryButton>
                </form>

                <ul class="divide-y">
                    <li v-for="comment in comments.data" :key="post.id" class="px-2 py-4 block group">
                        <Comment  @edit="editComment" @delete="deleteComment" :comment="comment"/>
                    </li>
                </ul>

                <Pagination :meta="comments.meta" :only="['comments']"/>
            </div>
        </Container>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { computed, ref } from 'vue'
import { relativeDate } from '@/Utilities/date.js'
import Container from '@/Components/Container.vue'
import Pagination from '@/Components/Pagination.vue'
import Comment from '@/Components/Comment.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { router, useForm, Head } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import { useConfirm } from '@/Utilities/Composable/useConfirm.js'
import MarkdownEditor from '@/Components/MarkdownEditor.vue'
import PageHeading from '@/Components/PageHeading.vue'
import Pill from '@/Components/Pill.vue'

const props = defineProps(['post', 'comments'])

const formattedDate = computed(() => relativeDate(props.post.created_at))

const commentForm = useForm({
    body: '',
})

const commentEditorRef = ref(null)
const commentIdBeingEdited = ref(null)
const commentBeingEdit = computed(() => props.comments.data.find(comment => comment.id === commentIdBeingEdited.value))

const editComment = (commentId) => {
    commentIdBeingEdited.value = commentId
    commentForm.body = commentBeingEdit.value?.body
    commentEditorRef.value?.focus()
}

const cancelEditComment = () => {
    commentIdBeingEdited.value = null
    commentForm.reset()
}

const addComment = () => commentForm.post(route('posts.comments.store', props.post.id), {
    preserveScroll: true,
    onSuccess: () => commentForm.reset(),
})

const { confirmation } = useConfirm()

const updateCommnent = async () => {
    if(! await confirmation('Are you sure you want to update this comment?')) {
        commentTextAreaRef.value?.focus()
        return
    }

    commentForm.put(route('comments.update', {
        comment: commentIdBeingEdited.value,
        page: props.comments.meta.current_page,
    }), {
        preserveScroll: true,
        onSuccess: cancelEditComment,
    })
}

const deleteComment = async (commentId) => {
    if (! await confirmation('Are you sure you want to delete this comment?')) {
        return
    }

    router.delete(route('comments.destroy', {
        comment: commentId,
        page: props.comments.data.length > 1
            ? props.comments.meta.current_page
            : Math.max(props.comments.meta.current_page - 1, 1),
    }), {
    preserveScroll: true,
})}
</script>
