<script setup>
import { getPage } from '@/scripts/crud/get'

const props = defineProps(['link'])
</script>

<template>
  <button
    class="
      rounded-tl-md rounded-bl-md text-xl p-2
      border border-1 border-gray-300 w-[125px]
    "
    :disabled="!props.link?.active"
    @click="getPage(props.link?.url)"
  >
    &laquo; Previous
  </button>
</template>