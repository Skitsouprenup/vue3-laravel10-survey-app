<script setup>
import QuestionDetails from './QuestionDetails.vue'
import QuestionDraft from './QuestionDraft.vue'

const props = defineProps(['draft', 'questions'])

</script>

<template>
  <div 
    v-for="question in props.questions"
    class="m-1 p-2 border border-1 border-zinc-300"
  >
    <QuestionDetails :question="question" v-if="!draft"/>
    <QuestionDraft :question="question" v-else />
  </div>
</template>