<script setup>
import { reactive, watchEffect } from 'vue'

const emit = defineEmits(
  [
    'add-selection-choice', 
    'set-question-type',
  ]
)

const choice = reactive({
  type: 'single',
  name: ''
})

watchEffect(() => {
  emit('set-question-type', choice.type)
})

const addChoice = (event) => {
  event.preventDefault()
  emit('add-selection-choice', choice.name)
  choice.name = ''
}
</script>

<template>

  <div class="flex flex-col gap-1">
    <h2 class="text-xl font-medium">Add Choice</h2>
    
    <div
      class="flex flex-col gap-1"
    >
      <h2>Selection Type</h2>

      <div class="flex gap-1">
        <div class="flex gap-2 items-center">
          <label for="single">Single</label>
          <input 
            type="radio" 
            name="type" 
            id="single" 
            v-model="choice.type"
            value="single"
            checked
          />
        </div>

        <div class="flex gap-2 items-center">
          <label for="multiple">Multiple</label>
          <input 
            type="radio" 
            name="type" 
            id="multiple"
            value="multiple"
            v-model="choice.type" 
          />
        </div>
      </div>
    </div>

    <div class="flex gap-1">

      <div
        class="flex flex-col gap-1 flex-1"
      >
        <label for="choice">Name</label>
        <div class="flex gap-1">
          <input 
            type="text" 
            id="choice" 
            class="border border-1 border-slate-300 flex-1"
            v-model="choice.name"
          />
          <button
            class="
              bg-sky-400 p-2 rounded flex gap-1 items-center font-domine
              hover:bg-sky-500
            "
            @click="addChoice"
          >
            Add
          </button>
        </div>
      </div>
      
    </div>
  </div>
</template>