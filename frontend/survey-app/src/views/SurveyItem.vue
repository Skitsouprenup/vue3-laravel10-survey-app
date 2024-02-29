<script setup>
import EyeIcon from '@/components/icons/EyeIcon.vue'
import PencilSquareIcon from '@/components/icons/PencilSquareIcon.vue'
import TrashIcon from '@/components/icons/TrashIcon.vue'
import useAppStore from '@/store/useAppStore'
import { openDeleteModal, openAcceptModal } from '../scripts/utilities/utils'
import { modalEventDefaults } from '@/scripts/utilities/constants'
import { deleteSurvey } from '@/scripts/crud/delete'

import { ref, watchEffect } from 'vue'
import { useRouter } from 'vue-router'

const appStore = useAppStore()
const fetchLoading = ref(false)
const props = defineProps(['survey'])
const router = useRouter()

/* If you return keyword here, watchers will stop working */
watchEffect(() => {

  if(appStore.getModalFireConfirmEvt) {
    if(props.survey.id === appStore.getModalProps.content.id) {
      appStore.setModalProps({}, modalEventDefaults, false)
      deleteSelectedSurvey(props.survey.id)
    }
  }
  
  if(
      appStore.getModalFireCancelEvt ||
      appStore.getModalFireAcceptEvt
    ) {
    appStore.setModalProps({}, modalEventDefaults, false)
  }

})

const displayDesc = (description) => {
  return `${description.substring(0, 50)}${description.length > 50 ? '...' : ''}`
}

const viewSurvey = () => {
  router.push(`surveys/${props.survey?.slug}/${props.survey?.id}`)
}

const editSurvey = () => {
  router.push(`surveys/edit/${props.survey?.id}`)
}

const deleteSelectedSurvey = (surveyId) => {
  fetchLoading.value = true

  openAcceptModal(
      appStore, 
      null, 
      {},
      `'${props.survey.title}' survey has been Deleted!`,
      'The selected survey is now successfully deleted!',
      true
    )

    deleteSurvey(surveyId).
    then(() => {
      openAcceptModal(
        appStore, 
        null, 
        {},
        `'${props.survey.title}' survey has been Deleted!`,
        'The selected survey is now successfully deleted!'
      )
    }).
    catch((error) => {
      console.error(error)
      openAcceptModal(
        appStore, 
        null, 
        {},
        `Failed to Delete Survey`,
        'Operation aborted due to an error',
        true
      )
    })
}

</script>

<template>
  <div
    class="flex flex-col gap-2 shadow-md w-fit p-2"
  >
    <div>
      <img
        width="300"
        height="300"
        :src="props.survey?.imagePrev" 
        alt="image" 
      />
    </div>

    <div
      class="flex flex-col gap-1"
    >
      <h2
        class="text-2xl text-semibold"
      >
        {{ props.survey?.title }}
      </h2>
      <p>
        {{ displayDesc(props.survey?.description) }}
      </p>
    </div>

    <div
      v-if="!fetchLoading"
      class="flex justify-around"
    >
      <div
        class="cursor-pointer hover:scale-110 tooltip"
        v-on:click="viewSurvey"
        v-if="props.survey?.status"
      >
        <EyeIcon />
        <span class="tooltiptext">View</span>
      </div>
      <div
        class="cursor-pointer hover:scale-110 tooltip"
        v-if="!props.survey?.status"
        v-on:click="editSurvey"
        
      >
        <PencilSquareIcon />
        <span class="tooltiptext">Edit</span>
      </div>
      <div
        class="cursor-pointer hover:scale-110 tooltip"
        @click="$event => 
        openDeleteModal(
          appStore,
          $event,
          { id: props.survey.id },
          `Delete '${props.survey.title}'`,
          'Are you sure you wanna delete this survey?'
        )"
      >
        <TrashIcon />
        <span class="tooltiptext">Delete</span>
      </div>
    </div>
    <div 
      v-else
      class="flex justify-center items-center"
    >
      <h2 
        class="text-xl font-semibold"
      >
        Loading...
      </h2>
    </div>

  </div>
</template>