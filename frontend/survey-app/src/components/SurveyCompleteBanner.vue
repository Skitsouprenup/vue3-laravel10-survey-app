<script setup>
import { RouterLink } from 'vue-router'

const props = defineProps(['surveyTitle', 'message'])

</script>

<template>
  <div
    class="
      flex flex-col gap-2 items-center justify-center 
      sm:h-[calc(100vh-3rem)] h-screen
    "
  >

    <h1
      class="text-2xl font-bold"
    >
      {{ 
        props.surveyTitle 
        ? `Thank you for completing the ${props.surveyTitle} survey.` 
        : `Thank you for completing the survey.`
      }}
    </h1>

    <div
      class="flex flex-col items-center"
    >
      <p
        class="text-lg text-gray-600"
      >
        {{ props.message }}
      </p>
      <RouterLink
        to="/surveys"
        class="underline hover:scale-105"
      >
        Go to Survey List
      </RouterLink>
    </div>

  </div>
</template>