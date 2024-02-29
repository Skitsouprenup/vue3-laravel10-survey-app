<script setup>

import { onBeforeMount, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

import LoadingScreen from '@/components/LoadingScreen.vue'
import { getAnswersBySurveyId } from '@/scripts/crud/get' 

const data = ref(null)
const loading = ref(true)
const loadingMessage = ref('Loading...')

const router = useRouter()
const route = useRoute()

const answerToString = (ans) => {
  let result = ''

  if(ans[0] === '[' && ans[ans.length-1] === ']') {
    const list = JSON.parse(ans)
    for(let i = 0; i < list.length; i++) {
      result += list[i]
      if(i < list.length-1) result += ', '
    }
  }
  else {
    result = ans
  }

  return result
}

onBeforeMount(()=> {
  getAnswersBySurveyId(route.params.id).
  then((response) => {
    data.value = response.data
    console.log(response.data)
    loading.value = false
  }).
  catch((error) => {
    console.error(error)
    loadingMessage.value = 'An Error Occured.'
  })
});

</script>

<template>
<div>
  <LoadingScreen v-if="loading" :message="loadingMessage" />
  <div v-else>
    <div
    class="
      sm:shadow-md shadow-sm sm:w-8/12 w-full p-2
      flex flex-col mx-auto gap-2"
    >
      <h1
        class="font-medium text-4xl p-3 font-domine text-center"
      >
        Answers
      </h1>

      <div>
        <h2 
          class="font-medium text-2xl px-2 font-domine"
        >
          Survey: {{ data.surveyTitle }}
        </h2>

        <h2 
          class="text-xl px-2 pb-4"
        >
          <span class="font-medium">Answered By:</span> {{ data.name }}
        </h2>
      </div>

      <div
        v-for="(title, index) in data.titles"
        class="px-2 py-1"
      >
        <div
          class="flex items-center gap-2"
        >
          <h3
            class="font-medium text-xl font-domine"
          >
            {{'Question: '}}
          </h3>
          <p
            class="text-xl"
          >
            {{ title }}
          </p>
        </div>

        <div
          class="flex items-center gap-2"
        >
          <h3
            class="font-medium text-xl font-domine"
          >
            {{'Answer: '}}
          </h3>
          <p
            class="text-xl"
          >
            {{ answerToString(data.answers[index]) }}
          </p>
        </div>
      </div>

      <div
        class="p-4 w-full flex justify-center"
      >
        <RouterLink
          :to="{ name: 'dashboard' }"
          class="
            bg-blue-300 p-2 rounded flex gap-1 items-center font-domine 
            w-fit hover:scale-105
          "
        >
          Back to Dashboard 
        </RouterLink>
      </div>
    </div>

  </div>
</div>
</template>