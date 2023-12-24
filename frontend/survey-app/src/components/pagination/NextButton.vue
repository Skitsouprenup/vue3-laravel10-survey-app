<script setup>
import { getPage } from '@/scripts/crud/get'

const props = defineProps(['link'])
</script>

<template>
  <button
    class="
      rounded-tr-md rounded-br-md text-xl p-2
      border border-1 border-gray-300 w-[125px]
    "
    :disabled="!props.link?.active"
    @click="getPage(props.link?.url)"
  >
    Next &raquo;
  </button>
</template>