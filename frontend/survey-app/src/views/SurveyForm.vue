<script setup>
import { 
  onBeforeUnmount, 
  ref, 
  provide, 
  computed, 
  onBeforeMount,
  shallowReactive, 
  /*watchEffect */ 
} from 'vue'

import { 
  checkBlankInputs, 
  convertImageBlobToBase64,
  displayApiError, 
  /*convertImageElemToBase64 */
} from '@/scripts/utilities/utils'

import useAppStore from '@/store/useAppStore'

import { getSurveyDetails } from '@/scripts/crud/get'

import PlusIcon from '@/components/icons/PlusIcon.vue'
import ImageIcon from '@/components/icons/ImageIcon.vue'

import SurveyQuestionForm from '@/components/SurveyQuestionForm.vue'
import FormErrorList from '@/components/FormErrorList.vue'
import QuestionList from '@/components/surveyquestionform/QuestionList.vue'

import { useRoute, useRouter } from 'vue-router'
import { saveSurvey, updateSurvey } from '@/scripts/crud/post.js'
import LoadingScreen from '../components/LoadingScreen.vue'

const appStore = useAppStore()

const loading = ref(true)
const fetchLoading = ref(false)
const loadingMessage = ref('Loading...')

const addQuestionFormToggle = ref(false)
const editQuestionData = ref(null)
const errorList = ref([])

//const initImgFetchOnUpdate = ref('none')

const imageBtnState = ref('upload')
const imagePrev = ref(null)
const imageAsset = ref(null)
const imgRef = ref(null)

const router = useRouter()
const route = useRoute()

const initialDataState = {
  title: '',
  status: false,
  description: '',
  expire_date: null,
  /*
    During update, initial questions from this array 
    will have database id coming from the database 
  */
  questions: []
}

const data = shallowReactive({...initialDataState})

onBeforeMount(() => {
  if(route.params?.id) {
    getSurveyDetails(route.params.id).
    then(async (response) => {
      const payload = response.data.data

      /*
        Note: This logic expect the date from the server to
        be in 'YYYY-mm-dd' format 
      */
      const splitDate = 
        payload.expire_date.split(' ')[0].split('-')
      const newDate = `${splitDate[0]}-${splitDate[1]}-${splitDate[2]}`

      data.title = payload.title
      data.status = payload.status
      data.description = payload.description
      data.expire_date = newDate
      data.questions = payload.questions

      imagePrev.value = payload.imagePrev
      imageAsset.value = payload.imageAsset
      imageBtnState.value = 'remove'

      loading.value = false
    }).catch((error) => {
      console.error(error)
      loadingMessage.value = 'An Error Occured.'
    })
  }
  else loading.value = false
})

onBeforeUnmount(() => {
  removeImgPrev()
})

/* 
  This is only usable during initial page load and component state
  must be in update mode. Note: this solution doesn't successfully 
  convert the image in <img> tag into base64.
*/
/*
  watchEffect(() => {
    if(
        imgRef.value && 
        initImgFetchOnUpdate.value === 'none' && 
        route.params?.id
      ) 
      convertImageElemToBase64(
        imgRef.value, 
        imageAsset, 
        initImgFetchOnUpdate
      )
  }, { flush: 'post' })
*/

const toggleQuestionForm = (event) => {
  event.preventDefault()
  addQuestionFormToggle.value = !addQuestionFormToggle.value

  /* If edit mode, remove question data */
  if(editQuestionData)
    editQuestionData.value = null
}

const addQuestion = (question) => {
  let questionExists = false

  for(const item of data.questions) {
    if(item.title === question.title) {
      questionExists = true
      break;
    }
  }

  if(!questionExists) {
    data.questions = [...data.questions, question]
    addQuestionFormToggle.value = !addQuestionFormToggle.value
    resetErrorList()
  }
  else {
    createErrorList([
      { input: false, 
        inputName: '', 
        customMsg: 'Question Title Already Exists!' 
      }
    ])
  }
}

const deleteQuestion = (title) => {
  data.questions = [
    ...data.questions.filter((item) => 
      item.title !== title)
  ]

}

const editMode = (question) => {
  editQuestionData.value = question

  /* If surveyform toggle is off */
  if(!addQuestionFormToggle.value)
    addQuestionFormToggle.value = true
}

const editQuestion = (question) => {
  let exists = false

  const newQuestions = []
  for(const item of data.questions) {
    if(item.title === question.title) {
      newQuestions.push(question)
      exists = true
      continue
    }
    newQuestions.push(item)
  }

  if(exists) {
    addQuestionFormToggle.value = !addQuestionFormToggle.value
    editQuestionData.value = null
    data.questions = [...newQuestions]
    resetErrorList()
  }

}

const resetErrorList = () => {
  errorList.value = []
}

const createErrorList = (inputs) => {
  return checkBlankInputs(inputs, errorList)
}

const removeImgPrev = () => {
  if(imagePrev.value) {
    URL.revokeObjectURL(imagePrev.value)
    imagePrev.value = null
    imageAsset.value = null
    //initImgFetchOnUpdate.value = 'replaced'
  }
}

const setImageForUpload = (event) => {
  event.preventDefault()

  if(imageBtnState.value === 'upload') {
    const selectedFile = event.target.files[0]

    imagePrev.value = URL.createObjectURL(selectedFile)
    imageAsset.value = selectedFile

    imageBtnState.value = 'remove'
    return
  }

  if(imageBtnState.value === 'remove') {
    removeImgPrev()
    imageBtnState.value = 'upload'
    return
  }
}

const createOrUpdateSurvey = async (event) => {
  event.preventDefault()

  const isBlank = createErrorList(
    [
      { input: data.title.trim() ? true : false, inputName: 'Survey Title' },
      { input: data.description.trim() ? true : false, inputName: 'Description' },
      { input: imageAsset.value ? true : false, inputName: 'Image' },
      { input: data.expire_date ? true : false, inputName: 'Expire Date' },
      { input: data.questions.length ? true : false, inputName: 'Questions' }
    ]
  )
  if(isBlank) return

  fetchLoading.value = true
  const assetType = typeof imageAsset.value
  const imageBase64 = 
    assetType === 'string' 
    ? imageAsset.value
    : await convertImageBlobToBase64(imageAsset.value)

  if(route.params?.id) {
    updateSurvey({ ...data, image: imageBase64}, route.params.id).
    then((data) => {
      //console.log(data)
      appStore.showToast('Survey updated successfully!', 'accept')
      router.push(`surveys/${route.params.id}`)
    }).
    catch((error) => displayApiError(error, errorList)).
    finally(() => { fetchLoading.value = false })
  }
  else {
    saveSurvey({ ...data, image: imageBase64}).
    then((data) => {
      //console.log(data)
      appStore.showToast('Survey has been created!', 'accept')
      router.push({ name: 'surveys' })
    }).
    catch((error) => displayApiError(error, errorList)).
    finally(() => { fetchLoading.value = false })
  }
 
}

/* Providers */

provide('addQuestion', {
  addQuestion
})

provide('surveyFormUtils', {
  toggleQuestionForm,
  createErrorList,
  editQuestion,
  editMode,
  editQuestionData: computed(() => editQuestionData.value),
  deleteQuestion
})
</script>

<template>
  <LoadingScreen v-if="loading" :message="loadingMessage"/>
  <form v-else
    class="
      sm:shadow-md shadow-sm sm:w-8/12 w-full p-2
      flex flex-col mx-auto items-center gap-2
    "
  >
    <h1
      class="font-medium text-4xl p-4 font-domine"
    >
      {{ route.params?.id ? 'Update Survey' : 'Create Survey' }}
    </h1>

    <div class="flex flex-col gap-y-1 w-full">
      <label for="title">Title</label>
      <input 
        type="text" 
        id="title"
        :disabled="fetchLoading"
        class="border border-1 border-slate-300"
        v-model="data.title"
      />
    </div>

    <div 
      class="
        flex gap-2 w-full sm:flex-row flex-col 
        items-center
      "
    >
      <ImageIcon :size="{ width: 50, height: 50 }" v-if="!imagePrev"/>
      <div v-else>
        <img 
          :src="imagePrev" 
          width="300" 
          height="300" 
          ref="imgRef"
        />
      </div>

      <div 
        class="flex gap-x-1 items-center" 
        v-if="imageBtnState === 'upload'"
      >
        <input 
          type="file" 
          id="file" 
          style="display: none;"
          accept="image/*"
          :disabled="fetchLoading"
          @change="setImageForUpload"
        />
        <label 
          for="file"
          class="
            cursor-pointer p-2 border border-2 hover:shadow-md
            hover:border-lime-500
          "
        >
          Upload Image
        </label>
      </div>
      <div class="flex gap-x-1 items-center" v-else>
        <button
          class="cursor-pointer p-2 border border-2"
          :disabled="fetchLoading"
          @click="setImageForUpload"
        >
          Remove Image
        </button>
      </div>

    </div>

    <div class="flex flex-col gap-y-1 w-full">
      <label for="description">Description</label>
      <textarea 
        id="description"
        class="border border-1 border-slate-300"
        :disabled="fetchLoading"
        v-model="data.description"
      >
      </textarea>
    </div>

    <div class="flex flex-col gap-y-1 w-full">
      <label for="expiry">Expire Date</label>
      <input 
        type="date" 
        id="expiry"
        :disabled="fetchLoading"
        class="border border-1 border-slate-300"
        v-model="data.expire_date"
      />
    </div>

    <div
      class="flex gap-1 items-center py-2 w-full"
    >
      <label for="active">Publish</label>
      <input
        class="cursor-pointer" 
        type="checkbox"
        id="active"
        :disabled="fetchLoading"
        v-model="data.status"
      />
    </div>

    <div 
      class="flex justify-between items-center w-full" 
      v-if="!addQuestionFormToggle"
    >
      <h2
        class="font-medium text-xl"
      >
        Questions
      </h2>
      <button
        class="
          bg-sky-400 p-2 rounded flex gap-1 items-center font-domine
          hover:bg-sky-500 hover:text-gray-100
        "
        :disabled="fetchLoading"
        @click="toggleQuestionForm"
      >
        <PlusIcon /> Add Question
      </button>
    </div>

    <div
      class="border border-1 border-zinc-300 w-full"
      v-if="!addQuestionFormToggle"
    >
      <QuestionList 
        v-if="data.questions.length" 
        :questions="data.questions"
        :draft="true" 
      />
      <div
        class="flex justify-center"
        v-else
      >
        <h2 class="text-2xl text-gray-400">No Questions. Add One.</h2>
      </div>
    </div>

    <SurveyQuestionForm v-if="addQuestionFormToggle || editQuestionData" />

    <div 
      class="
        flex flex-col gap-1 items-center font-domine mt-5
        w-full
      "
    >
      <FormErrorList 
        :errorList="errorList"
        @resetErrorList="resetErrorList"
      />
      <button
        v-if="!fetchLoading" 
        type="submit" 
        :class="
          !addQuestionFormToggle 
          ? `bg-green-500 p-2 rounded font-semibold hover:bg-green-400
             hover:scale-105`
          : 'bg-gray-300 p-2 rounded font-semibold'          
        "
        @click="createOrUpdateSurvey"
        :disabled="addQuestionFormToggle"
      >
        {{ route.params?.id ? 'Update Survey' : 'Create Survey' }}
      </button>
      <h2 
        v-else
        class="text-xl font-semibold"
      >
        Loading...
      </h2>
    </div>
  </form>
</template>