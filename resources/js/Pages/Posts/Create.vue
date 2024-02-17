<template>
    <AppLayout title="Create a Post">
        <Container>
            <h1 class="text-2xl font-bold">Create a Post</h1>
            <form @submit.prevent="createPost">
                <div class="mt-6">
                    <InputLabel for="title" value="Title" class="sr-only" />
                    <TextInput id="title" class="w-full" v-model="form.title"  placeholder="Give it a great title..."/>
                    <InputError :message="form.errors.title" class="mt-2" />
                </div>

                <div class="mt-3">
                    <InputLabel for="body" value="Body" class="sr-only" />
                    <MarkdownEditor v-model="form.body" />
                    <TextArea id="body" v-model="form.body" rows="25" class="mt-2" />
                    <InputError :message="form.errors.body" class="mt-2" />
                </div>

                <div class="flex justify-end mt-6">
                    <SecondaryButton href="{{ route('posts.index') }}" class="mr-2">Cancel</SecondaryButton>
                    <PrimaryButton type="submit">Create Post</PrimaryButton>
                </div>
            </form>
        </Container>
    </AppLayout>

</template>

<script setup>
import {useForm} from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import TextArea from '@/Components/TextArea.vue'
import Container from '@/Components/Container.vue'
import MarkdownEditor from '@/Components/MarkdownEditor.vue'

const form = useForm({
    title: '',
    body: '',
})

const createPost = () => form.post(route('posts.store'))
</script>
