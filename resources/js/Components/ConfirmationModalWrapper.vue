<template>
    <ConfirmationModal :show="state.show">
        <template #title>
            {{ state.title }}
        </template>

        <template #content>
            {{ state.message}}
        </template>

        <template #footer>
            <SecondaryButton ref="cancelButtonRef" @click="cancel">Cancel</SecondaryButton>

            <PrimaryButton class="ml-3" @click="confirm">Confirm</PrimaryButton>
        </template>
    </ConfirmationModal>
</template>

<script setup>
import ConfirmationModal from '@/Components/ConfirmationModal.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { useConfirm } from '@/Utilities/Composable/useConfirm.js'
import { nextTick, ref, watch, watchEffect } from 'vue'

const { state, confirm, cancel } = useConfirm()

const cancelButtonRef = ref(null)

watchEffect(async () => {
    if (state.show) {
        await nextTick(() => {
            cancelButtonRef.value?.$el.focus()
        })
    } else {
        document.body.classList.remove('overflow-hidden')
    }
})
</script>
