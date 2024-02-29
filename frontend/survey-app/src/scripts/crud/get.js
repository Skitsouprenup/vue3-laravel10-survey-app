import axiosClient from "../axios"

export const getSurveys = () => {
  return axiosClient.get('/survey', {
    headers: {
      'Accept':'application/json'
    }
  })
}

export const getSurveyDetails = (id) => {
  return axiosClient.get(`/survey/${id}`, {
    headers: {
      'Accept':'application/json'
    }
  })
}

export const getSurveyBySlug = (slug) => {
  return axiosClient.get(`/survey/slug/${slug}`, {
    headers: {
      'Accept':'application/json'
    }
  })
}

export const getPage = (link) => {
  return axiosClient.get(link, {
    headers: {
      'Accept':'application/json'
    }
  })
}

export const getDashboardInfo = () => {
  return axiosClient.get(`/dashboard`, {
    headers: {
      'Accept':'application/json'
    }
  })
}

export const getAnswersBySurveyId = (surveyId) => {
  return axiosClient.get(`/answer/${surveyId}`, {
    headers: {
      'Accept':'application/json'
    }
  })
}

export const checkAnsweredSurvey = (surveyId) => {
  return axiosClient.get(`/answer/answered?surveyid=${surveyId}`, {
    headers: {
      'Accept':'application/json'
    }
  })
}