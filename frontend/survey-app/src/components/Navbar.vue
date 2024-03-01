<script setup>
import SurveyPapers from '@/assets/survey-papers-icon.png'
//import BellIcon from '@/components/icons/BellIcon.vue'
import PersonCircle from '@/components/icons/PersonCircle.vue'
import { navbarLinks } from '@/scripts/navbarlinks'
import { computed, onMounted, provide, ref, watch } from 'vue'
import { RouterLink, useRoute } from 'vue-router'
import { navbarDropdowns } from '../scripts/navbardropdowns'

const activeNavLink = ref('dashboard')
const activeLinkIcon = ref('')
const activeDropdownAnim = ref('')

const route = useRoute()

onMounted(() => {
  const path = route.fullPath.split('/');

  if(path.length >= 1) {
    selectNavigation(path[1])
  }
})

watch(computed(() => route.fullPath), (newValue) => {
  const path = newValue.split('/')
  if(path.length >= 1) {
    selectNavigation(path[1])
  }

})

const selectNavigation = (toName) => {

  for(const item of navbarLinks) {
    if(item.name === toName) {
      activeNavLink.value = item.name
      break;
    }
  }
}

const setActiveLinkIcon = (value) => {
  activeLinkIcon.value = value
}

watch(activeLinkIcon, (activeLink) => {

  if(activeLink) activeDropdownAnim.value = activeLink 
})

provide('navbarUtils', {
  setActiveLinkIcon
})

</script>

<template>
  <nav
    class="
      shadow-lg flex items-center sm:flex-row flex-col
      h-auto sm:h-12 bg-gradient-to-t from-red-500 to-rose-600 to-90%
    "
  >
  <img 
    :src="SurveyPapers"
    alt="papers"
    width="75"
    height="75" 
  />

  <div
    class="
      flex gap-x-5 flex-1 text-neutral-200 flex-wrap justify-center
      sm:justify-start
    "
  >
    <div
      v-for="item in navbarLinks"
      :key="Math.random()"
      :class="[
        'hover:bg-zinc-600 p-1 rounded transition-colors ease-in',
        activeNavLink === item.name ? 'bg-zinc-700' : ''
      ]"
    >
      <RouterLink 
        class="
          hover:text-amber-400 transition-colors ease-in-out
        "
        :to="{ name: item.name }"
      >
        {{ item.label }}
      </RouterLink>
    </div>
  </div>

  <div>
    <div class="flex gap-x-3 sm:pl-4 p-2 justify-center">
      <!--
        <button
          class="hover:scale-110"
          @click="
            setActiveLinkIcon(
              activeLinkIcon === 'notif' 
              ? activeLinkIcon = ''
              : activeLinkIcon = 'notif'
            )
          "
        >
          <BellIcon />
        </button>
      -->

      <button
        class="hover:scale-110"
        @click="
            setActiveLinkIcon(
              activeLinkIcon === 'userOptions' 
              ? activeLinkIcon = ''
              : activeLinkIcon = 'userOptions'
            )
          "
      >
        <PersonCircle />
      </button>
    </div>

    <Transition 
      :name="`${activeDropdownAnim}-slide-y`"
    >
      <div
        v-if="activeLinkIcon"
        class="
          bg-white sm:absolute relative sm:rounded p-2 border border-gray-200
          sm:right-2 sm:w-44 w-screen overflow-y-hidden
        "
      >
        <div v-for="item in navbarDropdowns">
            <component :is="item.component" v-if="activeLinkIcon === item.name">
            </component>
        </div>
      </div>
    </Transition>
  </div>
  </nav>
</template>