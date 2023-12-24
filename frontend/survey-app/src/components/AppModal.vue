<script setup>
import useAppStore from '@/store/useAppStore'

const appStore = useAppStore()

const fireModalEvt = (event) => {
  appStore.fireModalEvent(event)
}

</script>

<template>
  <div
    class="
      w-screen h-screen bg-gray-600/[.5] fixed
      flex items-center justify-center
    "
    v-if="appStore.getModalProps.show"
  >
    <div
      class="
        sm:w-2/4 w-full p-5 bg-gray-100 rounded-md
        flex flex-col items-center justify-center gap-2
        border border-1 border-gray-400
      "
    >
      <div
        class="flex flex-col items-center"
      >
        <h1
          class="text-2xl text-semibold text-center"
        >
          {{ appStore.getModalProps.content?.heading }}
        </h1>
        <p
          class="text-lg text-center text-center"
        >
          {{ appStore.getModalProps.content?.message }}
        </p>
      </div>

      <div>
        <!-- Confirm -->
        <div
          class="flex gap-3 justify-center flex-wrap"
          v-if="appStore.getModalConfirmBtn && appStore.getModalCancelBtn"
        >
          <button
            :class="`
              ${appStore.getModalCancelBtn?.twClass} 
            `"
            @click="fireModalEvt('cancel')"
          >
              {{ appStore.getModalCancelBtn?.label }}
          </button>

          <button
            :class="`
              ${appStore.getModalConfirmBtn?.twClass} 
            `"
            @click="fireModalEvt('confirm')"
          >
            {{ appStore.getModalConfirmBtn?.label }}
          </button>
        </div>

        <!-- Accept Only -->
        <div
          v-else
          class="flex justify-center"
        >
        <button
          :class="`
            ${appStore.getModalAcceptBtn?.twClass} 
          `"
          @click="fireModalEvt('accept')"
        >
          {{ appStore.getModalAcceptBtn?.label }}
        </button>
        </div>
      </div>

    </div>
  </div>
</template>