import UserDropdown from '@/components/navbar/dropdown/UserDropdown.vue'
import NotifDropdown from '@/components/navbar/dropdown/NotifDropdown.vue'

export const navbarDropdowns = [
  {
    name: 'userOptions',
    component: UserDropdown
  },
  {
    name: 'notif',
    component: NotifDropdown
  },
]