<script setup>
import { inject, onMounted, reactive } from 'vue'

import SelectionOption from '@/components/surveyquestionform/SelectionOption.vue'
import TrashIcon from './icons/TrashIcon.vue';

const { addQuestion } = inject('addQuestion')
const { 
  toggleQuestionForm, 
  createErrorList,
  editQuestionData,
  editQuestion
} = inject('surveyFormUtils')

const questionData = reactive({
  id: -1,
  title: '',
  type: 'text',
  selectionChoices: {
    type: '',
    choices: []
  }
})

/* 
  If in update mode, get the server data
  and fill-up questionData  
*/
onMounted(() => {
  if(editQuestionData.value) {
    questionData.id = editQuestionData.value.id,
    questionData.title = editQuestionData.value.title,
    questionData.type = editQuestionData.value.type,
    questionData.selectionChoices = {
      ...editQuestionData.value.selectionChoices
    }
  }
})

const closeForm = (event) => {
  toggleQuestionForm(event)
}

const setQuestionType = (type) => {
  questionData.selectionChoices.type = type
}

const removeSelectionChoice = (choice) => {
  for(const item of questionData.selectionChoices.choices) {
    if(item === choice) {
      questionData.selectionChoices.choices = [
        ...questionData.selectionChoices.choices.filter((cItem) =>
          cItem !== item
        )
      ]
      break;
    }
  }
}

const addSelectionChoice = (choice) => {

  const isBlank = createErrorList(
    [
      { input: choice?.trim() ? true : false, inputName: 'Choice' }
    ]
  )
  if(isBlank) return

  questionData.selectionChoices.choices = [
    ...questionData.selectionChoices.choices,
    choice
  ]
}

const createOrEditQuestion = (event) => {
  event.preventDefault()

  const isBlank = createErrorList(
    [
      { input: questionData.title.trim() ? true : false, inputName: 'Question Title' },
      { 
        input: 
          questionData.type === 'selection' 
          ? !questionData.selectionChoices.choices.length 
            ? false
            : true
          : true, 
        inputName: 'Choice List' 
      }
    ]
  )
  if(isBlank) return

  if(!editQuestionData.value) {
    addQuestion(questionData)
    return
  }

  editQuestion(questionData)
}
</script>

<template>
  <div
    class="flex flex-col gap-2 w-full"
  >

    <h1
      class="font-medium text-xl font-domine"
    >
      {{ editQuestionData ? 'Edit Question' : 'Create Question' }}
    </h1>

    <div class="flex sm:flex-row flex-col gap-2">
      <div class="flex flex-col gap-1 flex-1">
        <label for="questiontitle">Question Title</label>
        <input 
          type="text" 
          id="questiontitle" 
          v-model="questionData.title" 
          class="border border-1 border-slate-300"
        />
      </div>

      <div class="flex flex-col gap-1 flex-1">
        <label for="questiontype">Question Type</label>
        <select 
          id="questiontype" 
          v-model="questionData.type"
          class="border border-1 border-slate-300"
        >
          <option value="text">Text</option>
          <option value="selection">Selection</option>
        </select>
      </div>
    </div>

    <SelectionOption 
      v-if="questionData.type !== 'text'"
      @add-selection-choice="addSelectionChoice"
      @set-question-type="setQuestionType"
    />

    <div 
      v-if="questionData.type === 'selection'"
      class="border border-1 border-zinc-300"
    >
      <div v-if="questionData.selectionChoices.choices.length">
        <div 
          v-for="choice in questionData.selectionChoices.choices"
          class="p-2 flex gap-2 justify-between"
        >
          <span>{{ choice }}</span>
          <div
            class="cursor-pointer hover:scale-110 hover:text-rose-600"
            @click="removeSelectionChoice(choice)"
          >
            <TrashIcon />
          </div>
        </div>
      </div>
      <div
        class="flex justify-center"
        v-else
      >
        <h2 class="text-2xl text-gray-400">No Items. Add One.</h2>
      </div>
    </div>

    <div class="flex gap-2 mt-1">
      <button
        class="
          bg-red-400 p-2 rounded flex gap-1 items-center font-domine
          hover:bg-red-500
        "
        @click="closeForm"
      >
        Cancel
      </button>
      <button 
        type="submit"
        class="
          bg-sky-400 p-2 rounded flex gap-1 items-center font-domine
          hover:bg-sky-500
        "
        @click="createOrEditQuestion"
      >
        {{ !editQuestionData ? 'Create Question' : 'Edit Question' }}
      </button>
    </div>

  </div>
</template>