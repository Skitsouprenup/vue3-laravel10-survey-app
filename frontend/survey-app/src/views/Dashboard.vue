<script setup>
import LoadingScreen from '@/components/LoadingScreen.vue'
import PageLayout from '@/components/PageLayout.vue'
import { onBeforeMount, ref } from 'vue'

import { RouterLink } from 'vue-router'
import { getDashboardInfo } from '@/scripts/crud/get'
import EyeIcon from '@/components/icons/EyeIcon.vue'
import PencilSquareIcon from '@/components/icons/PencilSquareIcon.vue'

const stats = ref(null)
const loading = ref(true)
const loadingMessage = ref('Loading...')

const formatDateTime = (dateString) => {
  const date = new Date(dateString);
  return `${date.toDateString()} ` +
  `${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`
}

onBeforeMount(() => {
  getDashboardInfo().
  then((response) => {
    //console.log(response.data)
    stats.value = response.data
    loading.value = false
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
        Dashboard
      </h1>
    </template>

    <template v-slot:content>
      <LoadingScreen v-if="loading" :message="loadingMessage"/>
      <div
        v-else
        class="
          grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 
          h-full p-4
        "
      >
        <div
          class="
            shadow-md border border-1 border-zinc-200 order-1 
            p-2 flex flex-col gap-1 items-center justify-center
          "
        >
          <div>
            <h1
              class="text-4xl font-semibold text-gray-800"
            >
              Total Surveys
            </h1>
          </div>

          <div>
            <h2
              class="text-[4rem] font-semibold text-gray-700"
            >
              {{ stats.totalSurveys }}
            </h2>
          </div>
        </div>

        <div
          class="
            shadow-md lg:row-span-2 border border-1 border-zinc-200
            p-2 flex flex-col gap-1 items-center md:items-start
            order-3 lg:order-2 md:order-3
          "
        >
          <div>
            <h1
              class="font-medium text-2xl"
            >
              Latest Survey
            </h1>
          </div>

          <div
            class="flex flex-col gap-1"
          >
            <img
              :src="stats.latestSurvey.imagePrev"
              class="w-[300px] md:w-[100%]"
            />

            <div
              class="flex flex-col gap-1"
            >
              <h2
                class="text-xl font-medium"
              >
                {{ stats.latestSurvey.title }}
              </h2>

              <div
                class="flex justify-between gap-1"
              >
                <span>Created At:</span>
                <span>{{ formatDateTime(stats.latestSurvey.created_at) }}</span>
              </div>

              <div
                class="flex justify-between gap-1"
              >
                <span>Expiration:</span>
                <span>{{ formatDateTime(stats.latestSurvey.expire_date) }}</span>
              </div>

              <div
                class="flex justify-between gap-1"
              >
                <span>Status:</span>
                <span>{{ stats.latestSurvey.status ? 'Draft' : 'Published' }}</span>
              </div>

              <div
                class="flex justify-between gap-1"
              >
                <span>Questions:</span>
                <span>{{ stats.latestSurvey.questions }}</span>
              </div>

              <div
                class="flex justify-between gap-1"
              >
                <span>Answers:</span>
                <span>{{ stats.latestSurvey.answers }}</span>
              </div>

              <div class="flex justify-around gap-1 flex-wrap pt-2">
                <div class="w-fit h-fit">
                  <RouterLink
                    :to="`/surveys/${stats.latestSurvey.slug}/${stats.latestSurvey.id}`"
                    
                  >
                    <button
                      class="
                        rounded-md bg-sky-300 p-2 hover:bg-sky-400 flex gap-1
                        w-[150px] justify-center
                      "
                    >
                      <EyeIcon v-if="!stats.latestSurvey.status" />
                      <PencilSquareIcon v-else/>
                      {{ stats.latestSurvey.status ? 'Edit Survey' : 'View Survey' }}
                    </button>
                  </RouterLink>
                </div>

                <div class="w-fit h-fit">
                  <!-- TODO -->
                  <RouterLink
                    to="/surveys"
                  >
                    <button
                      class="
                        rounded-md bg-purple-400 p-2 hover:bg-purple-500 flex gap-1
                        w-[150px] justify-center
                      "
                    >
                      <EyeIcon />
                      View Answers
                    </button>
                  </RouterLink>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div
          class="
            shadow-md lg:row-span-2 border border-1 border-zinc-200
            p-2 flex flex-col gap-1 items-center md:items-start
            order-4 md:order-4 lg:order-3
          "
        >
          <div
            class="flex justify-between px-1 w-full items-center"
          >
            <h1
              class="font-medium text-2xl"
            >
              Latest Answers
            </h1>
            <!-- TODO -->
            <RouterLink
              to="/surveys"
              class="hover:underline h-fit text-sky-600"
            >
              View All
            </RouterLink>
          </div>

          <div
            v-for="answer in stats.latestAnswers"
            class="
              cursor-pointer flex flex-col hover:bg-zinc-300
              w-full p-1
            "
          >
            <h2
              class="text-lg font-medium text-gray-800"
            >
              {{ answer.survey.title }}
            </h2>
            <p
              class="text-gray-700 italic"
            >
              Answered: {{ answer.end_date }}
            </p>
          </div>
        </div>

        <div
          class="
            shadow-md border border-1 border-zinc-200 lg:order-4 md:order-2
            order-2 p-2 flex flex-col gap-1 items-center justify-center
          "
        >
          <div>
            <h1
              class="text-4xl font-semibold text-gray-800"
            >
              Total Answers
            </h1>
          </div>

          <div>
            <h2
              class="text-[4rem] font-semibold text-gray-700"
            >
              {{ stats.totalAnswers }}
            </h2>
          </div>
        </div>
      </div>
    </template>
  </PageLayout>
</template>