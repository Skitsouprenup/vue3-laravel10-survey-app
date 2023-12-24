<script setup>
import { RouterLink } from 'vue-router'

const props = defineProps(['surveyTitle'])

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
      Thank you for completing the '{{ props.surveyTitle }}' survey.
    </h1>

    <div
      class="flex flex-col items-center"
    >
      <p
        class="text-lg text-gray-600"
      >
        Thank you for your participation on this survey!
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