<script setup>
import { getPage } from '@/scripts/crud/get'

const props = defineProps(['link'])
</script>

<template>
  <button
    class="p-2 text-xl border border-1 border-gray-300"
    :disabled="!props.link?.active"
    @click="getPage(props.link?.url)"
  >
    {{ props.link?.label }}
  </button>
</template>