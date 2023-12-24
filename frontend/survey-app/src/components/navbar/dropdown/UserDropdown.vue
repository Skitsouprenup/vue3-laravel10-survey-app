<script setup>
import useAppStore from '@/store/useAppStore'
import { useRouter } from 'vue-router'
import { logoutUser } from '@/scripts/crud/post'
import { inject } from 'vue'

const { setActiveLinkIcon } = inject('navbarUtils')

const appStore = useAppStore()
const router = useRouter()

const logout = () => {
  logoutUser().
  then(() => {
    appStore.logout()
    router.push({ name: 'login' })
  }).
  catch((error) => {
    /*
      401 could mean that the user token is
      already expired. In that case, logout
      the user.
    */
    if(error.response.status === 401) {
      appStore.logout()
      router.push({ name: 'login' })
      return
    }
    console.error(error)
  }).
  finally(() => {
    setActiveLinkIcon('') 
    appStore.showToast('Logout Successful!', 'accept') 
  })
}

</script>

<template>
  <div
    class="flex flex-col gap-y-2"
  >
    
    <div
      class="cursor-pointer"
      @click="logout"
    >
      <span>Logout</span>
      <hr />
    </div>
  </div>
</template>