<script setup>
import { toastDefaults } from '@/scripts/utilities/constants'
const props = defineProps(['toast'])

const selectTimerColor = (type) => {
  let color = 'bg-gray-600'

  switch(type) {
    case 'accept':
      color = 'bg-green-500'
    break;

    case 'delete':
    case 'error':
      color = 'bg-red-500'
    break;
  }

  return color
}

const theme = {
  anim: `line-timer-reduce-x ${(toastDefaults.timeLimit/1000) + 0.2}s`
}

</script>

<template>
  <Transition name="line-timer">
    <div
      v-if="toast.time"
      :class="`
        max-w-full min-h-[5px] basis-[10px] w-0
        ${selectTimerColor(toast.type)}
      `"
    >
      &nbsp;
    </div>
  </Transition>

</template>

<style>
.line-timer-enter-active {
  animation: v-bind('theme.anim');
}

.line-timer-enter-from {
  width: 200px;
}

@keyframes line-timer-reduce-x {
  0% {
    width: 200px;
  }
  100% {
    width: 0px;
  }
}
</style>