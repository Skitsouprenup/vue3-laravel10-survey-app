import axiosClient from "../axios"

export const loginUser = (credentials) => {
  return axiosClient.post('/login', credentials, {
    headers: {
      'Content-Type':'application/json'
    }
  })
}

export const registerUser = (credentials) => {
  return axiosClient.post('/register', credentials, {
    headers: {
      'Content-Type': 'application/json'
    }
  })
}

export const logoutUser = () => {
  return axiosClient.post('logout')
}

export const saveSurvey = (survey) => {
  return axiosClient.post('/survey', survey, {
    headers: {
      'Content-Type': 'application/json'
    }
  })
}

export const updateSurvey = (survey, id) => {
  return axiosClient.put(`/survey/${id}`, survey, {
    headers: {
      'Content-Type': 'application/json'
    }
  })
}

export const saveSurveyAnswer = (surveyId, answers) => {
  return axiosClient.post(`/survey/${surveyId}/answer`, answers, {
    headers: {
      'Content-Type': 'application/json'
    }
  })
}