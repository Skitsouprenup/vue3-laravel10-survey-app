<script setup>
import useAppStore from '@/store/useAppStore'
import Xmark from '@/components/icons/Xmark.vue'
import LineTimer from '@/components/toast/LineTimer.vue'

const appStore = useAppStore()

const closeToast = (toast) => {
  for(const appToast of appStore.getToasts) {
    if(appToast.index === toast.index) {
      clearInterval(toast.index)
      appStore.removeToast(toast.index)
    }
  }
}

</script>

<template>
  <div>
    <TransitionGroup name="toast-slide-x">
      <div
        v-for="toast in appStore.getToasts"
        :key="toast.index"
        class="
          w-[210px] flex justify-center
          fixed left-[calc(100vw-210px)]
        "
      >
        <div
          class="
            flex flex-col gap-1 w-full bg-gray-100
            rounded-md mr-2 mt-3 w-[200px]
            border border-1 border-zincW-400
          "
        >
          <div
            class="flex justify-end pr-1 pt-1"
          >
            <Xmark 
              class="cursor-pointer"
              @click="closeToast(toast)"  
            />
          </div>

          <div
            class="px-2"
          >
            {{ toast.message }}
          </div>

          <LineTimer :toast="toast" />
        </div>
      </div>
    </TransitionGroup>
  </div>
</template>