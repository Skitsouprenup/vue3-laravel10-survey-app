<script setup>
import { onBeforeMount, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'


import LoadingScreen from '@/components/LoadingScreen.vue'
import FormErrorList from '@/components/FormErrorList.vue'

import { checkAnsweredSurvey, getSurveyBySlug } from '@/scripts/crud/get' 
import { saveSurveyAnswer } from '@/scripts/crud/post'

import { 
  checkBlankInputs,
  displayApiError
} from '@/scripts/utilities/utils'
import SurveyCompleteBanner from '../components/SurveyCompleteBanner.vue'

const survey = ref(null)
const surveyComplete = ref(false)
const surveyCompleteMessage = ref('')

const loading = ref(true)
const fetchLoading = ref(false)
const loadingMessage = ref('Loading...')
const errorList = ref([])

const router = useRouter()
const route = useRoute()

onBeforeMount(() => {
  if(route.params.slug && route.params.id) {
    checkAnsweredSurvey(route.params.id).
    then((response) => {
      const answered = response.data?.answered

      if(!answered) {
        getSurveyBySlug(route.params.slug).
        then((response) => {

          const questionAnswer = []
          for(const item of response.data.data.questions) {

            questionAnswer.push({
              ...item,
              answer: 
                item.selectionChoices.type === 'multiple'
                ? []
                : ''
            })
          }

          survey.value = {
            ...response.data.data,
            questions: [...questionAnswer]
          }

          loading.value = false
        }).
        catch((error) => {
          console.error(error)
          loadingMessage.value = 'An Error Occured.'
        })
      } else {
        surveyComplete.value = true
        surveyCompleteMessage.value = 
          'You already answered this survey. ' +
          'Thank you for your participation!'
      }
    }).
    catch((error) => {
      console.error(error)
      loadingMessage.value = 'An Error Occured.'
    })

    
  } else loadingMessage.value = 'No Selected Survey.'
})

const createErrorList = (inputs) => {
  return checkBlankInputs(inputs, errorList)
}

const resetErrorList = () => {
  errorList.value = []
}

const setSingleChoice = (event, questionIndex) => {
  /* 
    re-assigning values to a reactive object will make 
    them reactive. To prevent this, use shallowRef
    and adjust your code. 
  */
  survey.value.questions[questionIndex] = {
    ...survey.value.questions[questionIndex],
    answer: event.target.value
  }
}

const setMultipleChoices = (event, questionIndex) => {
  const answer = []

  if(!survey.value.questions[questionIndex].answer.length) {
    answer.push(event.target.value)
  }
  else {

    let exists = false
    for(const item of survey.value.questions[questionIndex].answer) {
      if(item === event.target.value) {
        exists = true
        continue
      }
      answer.push(item)
    }
    if(!exists) answer.push(event.target.value)

  }

  survey.value.questions[questionIndex] = {
    ...survey.value.questions[questionIndex],
    answer: [...answer]
  }
}

const cancelSurvey = (event) => {
  event.preventDefault()
  router.push({ name: 'surveys' })
}

const completeSurvey = (event) => {
  event.preventDefault()

  const answers = {}
  const answerForErrorList = []

  let index = 0
  for(const question of survey.value.questions) {
    answers[question.id] = question.answer

    answerForErrorList.push({
      input: 
        question.selectionChoices.type === 'multiple'
        ? question.answer?.length ? true : false
        : question.answer ? true : false,
      inputName: `Question #${index + 1}`
    })
    index++
  }

  const isBlank = createErrorList([...answerForErrorList])
  if(isBlank) return

  fetchLoading.value = true

  saveSurveyAnswer(survey.value.id, {answers}).
  then((response) => {
    console.log(response.data)
    surveyComplete.value = true
    surveyCompleteMessage.value = 
      'Thank you for your participation on this survey!'
  }).
  catch((error) => {
    displayApiError(error, errorList)
  }).
  finally(() => { fetchLoading.value = false })
}

</script>

<template>
  <div
    v-if="surveyComplete"
  >
    <SurveyCompleteBanner 
      :survey-title="survey?.title"
      :message="surveyCompleteMessage" 
    />
  </div>
  <div v-else>
    <LoadingScreen v-if="loading" :message="loadingMessage" />
    <div
      v-else
      class="flex flex-col gap-2"
    >
      <div
        class="sm:shadow-md shadow-sm sm:w-8/12 w-full p-2
        flex flex-col mx-auto items-center gap-2"
      >
        <h1 class="font-medium sm:text-4xl text-2xl p-4 font-domine">
          {{ survey?.title }}
        </h1>

        <div
          class="w-fit flex flex-col gap-1 items-center"
        >
          <img 
            class="sm:w-[90%] w-full"
            :src="survey?.imagePrev"
          />

          <div
            class="flex flex-col gap-1 sm:w-[90%] w-full"
          >
            <p>
              {{ survey?.description }}
            </p>

            <div
              class="flex flex-col gap-1"
            >
              <div class="flex justify-center">
                <h1 class="font-medium sm:text-2xl text-xl font-domine">
                  Questions
                </h1>
              </div>

              <form class="flex flex-col gap-1">
                <div 
                  class="flex flex-col gap-1"
                  v-for="(question, questionIndex) in survey.questions"
                >
                  <p class="text-lg">
                    {{ question.title }}
                  </p>

                  <div v-if="question.type === 'text'">
                    <textarea 
                      placeholder="Answer..."
                      class="w-full disabled:bg-gray-200"
                      v-model="question.answer"
                      :disabled="fetchLoading"
                    >
                    </textarea>
                  </div>

                  <div
                    v-if="question.type === 'selection'"
                  >
                    <div
                      v-for="(choice, index) in question.selectionChoices.choices"
                    >
                      <div
                        class="flex gap-2 items-center"
                      >
                        <input 
                          type="radio" 
                          name="choice"
                          class="disabled:bg-gray-200"
                          :disabled="fetchLoading"
                          :id="`choice-${index}-${question.selectionChoices.type}`"
                          :value="choice" 
                          v-if="question.selectionChoices.type === 'single'"
                          @change="$event => setSingleChoice($event, questionIndex)"
                        />

                        <input 
                          type="checkbox"
                          :name="`choice-${index}`"
                          class="disabled:bg-gray-200"
                          :disabled="fetchLoading"
                          :id="`choice-${index}-${question.selectionChoices.type}`"
                          :value="choice"
                          v-if="question.selectionChoices.type === 'multiple'"
                          @change="$event => setMultipleChoices($event, questionIndex)"
                        />
                        <label :for="`choice-${index}-${question.selectionChoices.type}`">
                          {{ choice }}
                        </label>
                      </div>
                    </div>

                  </div>
                </div>

                <FormErrorList 
                  :errorList="errorList"
                  @resetErrorList="resetErrorList"
                />

                <div
                  v-if="!fetchLoading" 
                  class="flex justify-around my-3"
                >
                  <button
                    class="
                      bg-rose-400 p-2 rounded flex gap-1 items-center font-domine
                      w-[150px] hover:scale-[1.02] flex justify-center
                    "
                    @click="cancelSurvey"
                  >
                    Cancel
                  </button>

                  <button 
                    type="submit"
                    class="
                    bg-green-400 p-2 rounded flex gap-1 items-center font-domine 
                    w-[150px] hover:scale-[1.02]"
                    @click="completeSurvey"
                  >
                    Complete Survey
                  </button>
                </div>
                <div
                  class="flex justify-center" 
                  v-else
                >
                  <h2 
                    class="text-xl font-semibold"
                  >
                    Loading...
                  </h2>
                </div>

              </form>

            </div>
          </div>
          </div>
      </div>
    </div>
  </div>
</template>