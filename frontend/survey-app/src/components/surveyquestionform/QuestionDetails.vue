<script setup>
const props = defineProps(['question'])
</script>

<template>
  <h1 class="text-xl text-medium">{{ props.question?.title }}</h1>
    
  <div v-if="question?.type === 'text'" class="flex flex-col gap-2 p-2">
    <label for="textquestion">Answer</label>
    <textarea id="textquestion"></textarea>
  </div>
  <div
    class="p-2" 
    v-else
  >
    <h2 class="text-lg mb-1">Select Answer</h2>
    <div v-if="question.selectionChoices.type === 'single'">
      <div 
        class="flex gap-2"
      >
        <div class="flex flex-col gap-1 justify-center">
          <label 
            v-for="(item, index) in question.selectionChoices.choices"
            :key="`${item}-${index}`"
            :for="`single-${index}`"
          >
              {{ item }}
          </label>
        </div>
        <div class="flex flex-col gap-3 justify-center">
          <input
            v-for="(item, index) in question.selectionChoices.choices"
            type="radio" 
            name="survey-question-choice-radio"
            :key="`${item}-${index}`"
            :id="`single-${index}`" 
          />
        </div>
      </div>
    </div>
    <div v-else>
      <div class="flex gap-2">
        <div class="flex flex-col gap-1 justify-center">
          <label 
            v-for="(item, index) in question.selectionChoices.choices" 
            :key="`${item}-${index}`"
            :for="`${item}-${index}`"
          >
              {{ item }}
          </label>
        </div>

        <div class="flex flex-col gap-3 justify-center">
          <input 
            type="checkbox"
            v-for="(item, index) in question.selectionChoices.choices"
            :key="`${item}-${index}`"
            :name="`choice${index}`" 
            :id="`${item}-${index}`" 
          />
        </div>
      </div>
    </div>
  </div>

  <hr />
</template>