<script setup>
import { watch } from 'vue';

const props = defineProps(['errorList'])
const emits = defineEmits(['resetErrorList'])

</script>

<template>
  <div
    class="
      bg-red-600 rounded w-full p-2 mb-2 text-md sm:text-lg text-zinc-200
      flex flex-col gap-y-2
    "
    v-if="errorList.length > 0"
  >
    <span
      class="flex justify-end"
    >
      <button 
        class="sm:text-lg text-md hover:underline"
        @click="emits('resetErrorList')"
      >
        Close
      </button>
    </span>
    <span v-for="error in errorList" :key="Math.random()">
      {{ error }}
    </span>
  </div>
</template>