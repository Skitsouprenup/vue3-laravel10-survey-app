<script setup>
import NextButton from './pagination/NextButton.vue';
import PageButton from './pagination/PageButton.vue';
import PreviousButton from './pagination/PreviousButton.vue';

const props = defineProps(['meta'])
</script>

<template>
  <div class="mt-[100px] flex justify-center">
    <div
      v-for="(item, index) in meta.links"
      class="flex"
    >
      <div class="flex flex-wrap pt-1 justify-center">
        <PreviousButton :link="item" v-if="index === 0" />
        <PageButton :link="item" v-if="index > 0 && index < (meta.links.length - 1)" />
        <NextButton :link="item" v-if="index === (meta.links.length - 1)"/>
      </div>
    </div>
  </div>
</template>