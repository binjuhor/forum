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
                    <InputLabel for="topic_id" value="Topic" class="sr-only" />
                    <select id="topic_id" v-model="form.topic_id" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option v-for="topic in topics" :key="topic.id" :value="topic.id">
                            {{ topic.name }}
                        </option>
                    </select>
                </div>

                <div class="mt-3">
                    <InputLabel for="body" value="Body" class="sr-only" />
                    <MarkdownEditor v-model="form.body">
                        <template #toolbar="{ editor }">
                            <li v-if="!isProduction">
                                <button
                                    @click="autofill"
                                    type="button"
                                    class="px-3 py-2 hover:bg-gray-200"
                                    :class="{
                                        'bg-gray-200': editor.isActive('heading' , { level: 4 })
                                    }"
                                >
                                    <i class="ri-article-line"></i>
                                </button>
                            </li>
                        </template>
                    </MarkdownEditor>
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
import Container from '@/Components/Container.vue'
import MarkdownEditor from '@/Components/MarkdownEditor.vue'
import { isProduction } from '@/Utilities/environment.js'

const props = defineProps({
    topics: {
        type: Array,
        required: false,
        default: () => [],
    },
})

const form = useForm({
    title: '',
    topic_id: props.topics[0]?.id ?? null,
    body: 'Hello World!',
})

const createPost = () => form.post(route('posts.store'))
const autofill = async () => {

    if (isProduction) {
        return
    }

    const response = await axios.get('/local/post-content')
    form.title = response.data.title
    form.body = response.data.body
}
</script>
