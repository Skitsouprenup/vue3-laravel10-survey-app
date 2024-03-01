<script setup>
import PageLayout from '@/components/PageLayout.vue'
import PlusIcon from '@/components/icons/PlusIcon.vue'
import { onMounted, ref } from 'vue'

import { getPublishedSurveys } from '../scripts/crud/get'
import LoadingScreen from '../components/LoadingScreen.vue'
import SurveyItem from './SurveyItem.vue'
import Pagination from '../components/Pagination.vue'

const loading = ref(true)
const loadingMessage = ref('Loading...')
const surveys = ref(null)

/*
  Pagination
  links object is good for creating
  simple pagination
  
  meta object is good for more
  sophisticated pagination
*/
const links = ref(null)
const meta = ref(null)

onMounted(() => {
  getPublishedSurveys().
  then((response) => {
    //console.log(response.data)
    if(!response.data.data.length) {
      loadingMessage.value = "No Surveys Found."
    }
    else {
      surveys.value = response.data.data
      links.value = response.data.links
      meta.value = response.data.meta
      loading.value = false

      //console.log(links.value)
      //console.log(meta.value)
    }
  }).
  catch((error) => {
    console.error(error)
    loadingMessage.value = 'An Error Occured'
  })
})

</script>

<template>
  <PageLayout>
    <template v-slot:header>
      <h1
        class="font-medium text-4xl p-4"
      >
        Surveys
      </h1>
    </template>

    <template v-slot:optionButton>
      <div
        class="p-4 w-full flex sm:justify-end justify-center"
      >
        <RouterLink
          :to="{ name: 'surveyCreate' }"
          class="
            bg-green-400 p-2 rounded flex gap-1 items-center font-domine 
            w-fit hover:scale-105
          "
        >
          <PlusIcon /> Create Survey
        </RouterLink>
      </div>
    </template>

    <template v-slot:content>
      <div
        class="flex flex-col justify-between"
      >
        <div>
          <LoadingScreen v-if="loading" :message="loadingMessage"/>
          <div
            class="flex gap-3 flex-wrap" 
            v-else
          >
            <SurveyItem 
              v-for="survey in surveys"
              :survey="survey"
            />
          </div>
        </div>

        <Pagination :meta="meta" v-if="meta" />
      </div>
    </template>
  </PageLayout>
</template>