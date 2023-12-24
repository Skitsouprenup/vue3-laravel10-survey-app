<script setup>
import { RouterView } from 'vue-router'
import AppModal from '@/components/AppModal.vue'
import AppToast from '@/components/AppToast.vue'

</script>

<template>
  <AppModal />
  <AppToast />
  <RouterView />
</template>
