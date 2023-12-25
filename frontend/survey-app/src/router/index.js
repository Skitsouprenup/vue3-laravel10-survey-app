import { createRouter, createWebHistory } from 'vue-router'
import MainPage from '@/views/MainPage.vue'
import Dashboard from '@/views/Dashboard.vue'
import useAppStore from '@/store/useAppStore'

const routes = [
  {
    path: '/',
    redirect: '/dashboard',
    meta: { requiresAuth: true },
    component: MainPage,
    children: [
      {
        path: '/dashboard', 
        name: 'dashboard',
        component: Dashboard
      },
      {
        path: '/surveys', 
        name: 'surveys',
        component: () => import('@/views/Surveys.vue')
      },
      {
        path: '/surveys/create', 
        name: 'surveyCreate',
        component: () => import('@/views/SurveyForm.vue')
      },
      {
        path: '/surveys/edit/:id',
        name: 'surveyEdit',
        component: () => import('@/views/SurveyForm.vue')
      },
      {
        path: '/surveys/:slug/:id', 
        name: 'surveyView',
        component: () => import('@/views/SurveyAnswerForm.vue')
      },
    ]
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/Login.vue')
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('@/views/Register.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const appStore = useAppStore()
  let redirect = false

  if(to.meta.requiresAuth && !appStore.getToken) {
    next({ name: 'login' })
    redirect = true
  }

  if(appStore.getToken && !to.meta.requiresAuth)  {
    next({ name: 'dashboard' })
  }

  if(!redirect) next()
})

export default router