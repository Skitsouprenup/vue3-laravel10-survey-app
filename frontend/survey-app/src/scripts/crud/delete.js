import axiosClient from "../axios"

export const deleteSurvey = (id) => {
  return axiosClient.delete(`/survey/${id}`)
}