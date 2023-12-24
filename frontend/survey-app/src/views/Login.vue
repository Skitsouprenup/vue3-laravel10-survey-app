<script setup>
import SurveyPapers from '@/assets/survey-papers-icon.png'
import { RouterLink, useRouter } from 'vue-router'
import { reactive, ref } from 'vue'
import { checkBlankInputs } from '@/scripts/utilities/utils'
import FormErrorList from '@/components/FormErrorList.vue'
import useAppStore from '@/store/useAppStore'
import { loginUser } from '@/scripts/crud/post'
import { displayApiError } from '@/scripts/utilities/utils'
import SpinnerIcon from '@/components/icons/SpinnerIcon.vue'

const appStore = useAppStore()
const router = useRouter()
const loading = ref(false)
const errorList = ref([])

const credentials = reactive({
  email: '',
  password: '',
  remember: false
})

const resetErrorList = () => {
  errorList.value = []
}

const login = (event) => {
  event.preventDefault()
  resetErrorList()

  const isBlank = checkBlankInputs(
    [
        { input: credentials.email.trim() ? true : false, inputName: 'E-mail' },
        { input: credentials.password.trim() ? true : false, inputName: 'Password' },
    ], errorList)
  if(isBlank) return

  loading.value = true

  loginUser(credentials).
  then(response => {
    const data = response.data
    appStore.login(data.token, data.user)
    appStore.showToast('Login Successful!', 'accept')
    router.push({ name: 'dashboard' })
  }).
  catch((error) => {
    displayApiError(error, errorList)
  }).
  finally(() => loading.value = false)
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
          Login to your Account
        </h1>

        <span
          class="flex justify-center text-lg flex-wrap text-center"
        >
          Don&apos;t have an account?&nbsp;
          <RouterLink
            :to="{ name: 'register' }"
          >
            <span
              class="underline hover:decoration-2 decoration-1 text-sky-800"
            >
              Register here!
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
          class="rounded-t-md disabled:bg-gray-200" 
          placeholder="Email"
          :disabled="loading"
          v-model="credentials.email" 
        />
        <input 
          type="password" 
          class="rounded-b-md border-t-0 disabled:bg-gray-200" 
          placeholder="password"
          :disabled="loading"
          v-model="credentials.password" 
        />
        
        <div
          class="flex"
        >
          <div
            class="flex gap-1 items-center py-2 disabled:bg-gray-200"
          >
            <span>Remember Me</span>
            <input
              class="cursor-pointer" 
              type="checkbox"
              :disabled="loading"
              v-model="credentials.remember"
            />
          </div>
        </div>
        <button
          :class="
            [
              `bg-gray-600 text-lg p-2 text-zinc-200 hover:bg-green-800
               rounded flex justify-center items-center`,
               !loading ? 'cursor-pointer' : 'cursor-not-allowed'
            ]
          "
          :disabled="loading"
          @click="login"
        >
          <SpinnerIcon v-if="loading"/>
          Login
        </button>
      </form>
    </div>
  </div>
</template>