<script setup></script>

<template>
  <div
    class="flex justify-between items-center sm:flex-row flex-col p-4"
  >
    <slot name="header"></slot>
    <slot name="optionButton"></slot>
  </div>
  <hr class="border-gray-200 border border-1" />

  <slot name="content"></slot>
</template>