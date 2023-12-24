<script setup>
import SurveyPapers from '@/assets/survey-papers-icon.png'
import { reactive, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { registerUser } from '@/scripts/crud/post'
import useAppStore from '@/store/useAppStore'
import { checkBlankInputs } from '@/scripts/utilities/utils'
import FormErrorList from '@/components/FormErrorList.vue'
import { useRouter } from 'vue-router'
import { displayApiError } from '@/scripts/utilities/utils'
import SpinnerIcon from '@/components/icons/SpinnerIcon.vue'

const appStore = useAppStore()
const router = useRouter()

const loading = ref(false)
const errorList = ref([])
const credentials = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const resetErrorList = () => {
  errorList.value = []
}

const register = (event) => {
  event.preventDefault()
  resetErrorList()

  const isBlank = checkBlankInputs(
    [
        { input: credentials.name.trim() ? true : false, inputName: 'Full Name' },
        { input: credentials.email.trim() ? true : false, inputName: 'E-mail' },
        { input: credentials.password.trim() ? true : false, inputName: 'Password' },
        { input: credentials.password_confirmation.trim() ? true : false, 
          inputName: 'Confirm Password' 
        },
    ], errorList)
  if(isBlank) return

  if(credentials.password !== credentials.password_confirmation) {
    errorList.value = ['Password Confirmation failed.']
    return
  }

  loading.value = true

  registerUser(credentials).
  then(response => {
    const data = response.data
    appStore.register(data.token, data.user)
    router.push({ name: 'dashboard' })
  }).
  catch((error) => {
    displayApiError(error, errorList)
  }).
  finally(() => {
    loading.value = false
  })
}

</script>

<template>
  <div
    class="flex justify-center items-center h-screen"
  >
    <div 
      class="
        sm:shadow-md shadow-sm sm:w-8/12 w-full p-2
        flex flex-col mx-auto items-center
      "
    >
      <img 
        :src="SurveyPapers"
        alt="papers"
        width="75"
        height="75" 
      />

      <div
        class="flex flex-col gap-2"
      >
        <h1
          class="text-5xl font-semibold font-domine text-center"
        >
          Create an Account
        </h1>

        <span
          class="flex justify-center text-lg flex-wrap text-center"
        >
          Already have an account?&nbsp;
          <RouterLink
            :to="{name: 'login'}"
          >
            <span
              class="underline hover:decoration-2 decoration-1 text-sky-800"
            >
              Login here!
            </span>
          </RouterLink>
        </span>
      </div>

      <form
        class="flex flex-col w-full p-2"
      >
        <FormErrorList 
          :errorList="errorList"
          @resetErrorList="resetErrorList" 
        />

        <input 
          type="text" 
          placeholder="Full Name" 
          class="rounded-t-md disabled:bg-gray-200"
          v-model="credentials.name" 
        />

        <input 
          type="text" 
          placeholder="Email" 
          class="border-t-0 disabled:bg-gray-200"
          v-model="credentials.email"
          :disabled="loading"
        />

        <input 
          type="password" 
          placeholder="Password" 
          class="border-t-0 disabled:bg-gray-200"
          v-model="credentials.password"
          :disabled="loading"
        />

        <input 
          type="password"
          class="rounded-b-md border-t-0 disabled:bg-gray-200"
          placeholder="Confirm Password"
          v-model="credentials.password_confirmation"
          :disabled="loading"
        />
        
        <button
          :class="
            [
              `bg-gray-600 text-lg p-2 text-zinc-200 hover:bg-green-800
               rounded mt-2 flex justify-center items-center`,
               !loading ? 'cursor-pointer' : 'cursor-not-allowed'
            ]
          "
          @click="register"
          :disabled="loading"
        >
          <SpinnerIcon v-if="loading"/>
          Create Account
        </button>
      </form>
    </div>
  </div>
</template>