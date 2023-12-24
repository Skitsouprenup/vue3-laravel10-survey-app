import axios from "axios"
import useAppStore from "@/store/useAppStore"

const axiosClient = axios.create({
  baseURL: `${import.meta.env.VITE_API_BASE_URL}/api`
})

axiosClient.interceptors.request.use(config => {
  const appStore = useAppStore()

  config.headers.Authorization = `Bearer ${appStore.getToken}`
  return config
})

export default axiosClient