import { defineStore } from 'pinia'
import { modalEventDefaults, toastDefaults } from '../scripts/utilities/constants'

export default defineStore('globalStore', {

  state: () => ({
    token: localStorage.getItem('survey_app_token'),
    userDetails: localStorage.getItem('survey_app_user_credentials') ?
    JSON.parse(localStorage.getItem('survey_app_user_credentials')) : {},
    modalProps: {
      show: false,
      event: { ...modalEventDefaults },
      content: {}
    },
    toasts: [],
  }),
  /*Getter are computed by default */
  getters: {
    getToken: (state) => state.token,
    getUserDetails: (state) => state.userDetails,
    getToasts: (state) => state.toasts,
    getModalProps: (state) => state.modalProps,
    getModalConfirmBtn: (state) => state.modalProps.content?.confirmButton,
    getModalCancelBtn: (state) => state.modalProps.content?.cancelButton,
    getModalAcceptBtn: (state) => state.modalProps.content?.acceptButton,
    getModalFireConfirmEvt: (state) => state.modalProps.event.fireConfirm,
    getModalFireCancelEvt: (state) => state.modalProps.event.fireCancel,
    getModalFireAcceptEvt: (state) => state.modalProps.event.fireAccept
  },
  actions: {
    login(token, userDetails) {
      this.token = token
      this.userDetails = userDetails

      localStorage.setItem('survey_app_token', token)
      localStorage.setItem('survey_app_user_credentials', JSON.stringify(userDetails))
    },
    logout() {
      localStorage.removeItem('survey_app_token')
      localStorage.removeItem('survey_app_user_credentials')
      this.token = null
    },
    register(token, userDetails) {
      this.login(token, userDetails)
    },
    setModalProps(content, event, show) {
      this.modalProps.content = { ...content }
      this.modalProps.event = { ...event }
      this.modalProps.show = show
    },
    fireModalEvent(event) {
      switch(event) {
        case 'cancel':
          this.modalProps.event.fireCancel = true
        break;

        case 'confirm':
          this.modalProps.event.fireConfirm = true
        break;

        case 'accept':
          this.modalProps.event.fireAccept = true
        break;
      }
    },
    removeToast(index) {
      this.toasts = [
        ...this.toasts.filter(
          (item) => item.index !== index
        )
      ]
    },
    showToast(message, type) {
      const index = Math.random()

      //Check active max toasts and remove the first one
      //if the array length exceeds max toasts
      if(this.toasts.length >= toastDefaults.maxToasts) {
        clearInterval(this.toasts[0].intervalVar)
        this.toasts.shift()
      }

      //set interval
      const intervalVar = setInterval(() => {
        if(this.toasts.length) {

          for(const toast of this.toasts) {
            if(toast.index === index) {
              toast.time = toast.time + 100

              if(toast.time > toastDefaults.timeLimit) {
                clearInterval(toast.intervalVar)
                this.removeToast(toast.index)
                break
              }
            }
          }

        }
      }, 100)

      //Add new toast
      this.toasts = [
        ...this.toasts,
        { 
          message, 
          type, 
          time: 0,
          intervalVar,
          index
        }
      ]

    }
  }
  
})