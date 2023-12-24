<script setup>
const props = defineProps(['message'])
</script>

<template>
  <div
    class="flex justify-center items-center w-full h-screen" 
  >
    <h1 class="text-2xl text-bold">{{ props.message }}</h1>
  </div>
</template>