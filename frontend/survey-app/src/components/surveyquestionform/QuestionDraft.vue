<script setup>
import { inject, watchEffect } from 'vue'
import useAppStore from '@/store/useAppStore'
import { openDeleteModal } from '@/scripts/utilities/utils'
import { modalEventDefaults } from '@/scripts/utilities/constants'

const appStore = useAppStore()
const props = defineProps(['question'])

const { editMode, deleteQuestion } = inject('surveyFormUtils')

watchEffect(() => {
  if(appStore.getModalFireConfirmEvt) {
    if(props.question.title === appStore.getModalProps.content.title) {
      deleteQuestion(props.question?.title)
      appStore.setModalProps({}, modalEventDefaults, false)
    }
    return
  }

  if(appStore.getModalFireCancelEvt) {
    appStore.setModalProps({}, modalEventDefaults, false)
  }
})



const setEditMode = (event) => {
  event.preventDefault()

  editMode(props.question)
}
</script>

<template>
  <div class="flex flex-col gap-2">
    <div class="flex flex-col gap-1">
      <h1 class="text-2xl text-medium">Question Title</h1>
      <h3 class="text-lg pl-2">{{ props.question?.title }}</h3>
    </div>

    <div class="flex flex-col gap-1">
      <h1 class="text-2xl text-medium">Question Type</h1>
      <h3 class="text-lg pl-2">{{ props.question?.type }}</h3>
    </div>

    <div class="flex flex-col gap-2" v-if="props.question?.type === 'selection'">
      <div class="flex flex-col gap-1">
        <h1 class="text-2xl text-medium">Selection Type</h1>
        <h3 class="text-lg pl-2">{{ props.question?.selectionChoices?.type }}</h3>
      </div>

      <div class="flex flex-col gap-1 pl-2">
        <h1 class="text-xl text-medium">Choices</h1>

        <div class="flex flex-col gap-1 pl-2">
          <h3 
            class="text-lg" 
            v-for="item in props.question?.selectionChoices?.choices"
          >
            {{ item }}
          </h3>
        </div>
      </div>

    </div>

    <button 
      class="text-white bg-slate-500 text-xl p-1 hover:bg-slate-600 rounded"
      @click="setEditMode"
    >
      Edit
    </button>
    <button 
      class="text-white bg-rose-500 text-xl p-1 hover:bg-rose-600 rounded"
      @click="$event => 
        openDeleteModal(
          appStore,
          $event,
          { title: props.question.title },
          `Delete '${props.question.title}'`,
          'Are you sure you wanna delete this question?'
        )"
    >
      Delete
    </button>
  </div>
  <hr />
</template>