import { modalEventDefaults } from '@/scripts/utilities/constants'

export const checkBlankInputs = (inputs, errors) => {
    
  const errorList = []
    for(const inputObj of inputs) {
        if(!inputObj.input)
            errorList.push(
              inputObj?.customMsg 
              ? inputObj.customMsg
              : `${inputObj.inputName} field is empty!`
            )
    }
    
    if(errorList.length > 0) {
      errors.value = [...errorList]
      return true
    }
    else {
      errors.value = []
      return false
    } 
}

export const displayApiError = (error, errorList) => {
  console.log(error.response.status)
  const errorMessage = error.response?.data?.message

  if(error.response.status < 500)
    errorList.value = [errorMessage]
  else errorList.value = ['Something went wrong!']
}

export const convertImageBlobToBase64 = (blob) => {
  return new Promise((resolve, _) => {
    const reader = new FileReader()
    reader.onloadend = () => resolve(reader.result)
    reader.readAsDataURL(blob)
  });
}

const setImageExtensionForCanvas = (link) => {
  return link.substring(
    link.lastIndexOf('/')
  ).split('.')[1]
}

export const convertImageElemToBase64 = 
(
  imgRef, 
  imageAsset,
  initImgFetchOnUpdate
) => {
  const imgExt = setImageExtensionForCanvas(imgRef.src)

  const canvas = document.createElement("canvas")
  canvas.width = imgRef.width
  canvas.height = imgRef.height
  const ctx = canvas.getContext("2d")
  ctx.drawImage(imgRef, 0, 0)

  try {
    const dataImage = canvas.toDataURL(`image/${imgExt}`)
    imageAsset.value = dataImage
    initImgFetchOnUpdate.value = 'fetched'
    //console.log(dataImage)
  }
  catch(error) {
    const message = 'Tainted canvases'

    /*
      This is a solution when a tainted canvas error occured.
      This is not the best solution. The best solution I think
      is to configure Access-Control-Allow-Origin of your server
      response whenever a public image is needed to be accessed by
      the client.

      This error occurs when we're in different page and then jump
      into the survey form page. This can be fixed when page is
      refreshed
    */
    if(error.message.includes(message)) location.reload()
  }
  finally {
    canvas.remove()
  }
}


/*
  There are two ways in this function that fetch image and
  convert the image into base64. In chrome, I think, these methods
  don't work. The only way (I think) we can convert fetched image 
  into base64 is to get the image during initial fetching of images.
  We can see the initially fetched image in 
  inspector > network > Img tab

  Image fetching (via fetch API) in inspector > network > Fetch/XHR tab 
  won't work. To make this work, you need to add Access-Control-Allow-Origin
  response header in your server's CORS configuration.
*/

//export const test = (imgUrl) => {
  /*
  const imgExt = imgUrl.substring(imgUrl.lastIndexOf('/')).split('.')[1]
  
  const img = new Image()
  img.src = imgUrl + '?test=0'
  const canvas = document.createElement("canvas")
  canvas.width = img.width
  canvas.height = img.height
  const ctx = canvas.getContext("2d")
  ctx.drawImage(img, 0, 0)
  const dataImage = canvas.toDataURL(`image/${imgExt}`)
  console.log(dataImage)
  return dataImage
  */
  

  /*
  const response = await fetch(imgUrl, {
    method: 'GET',
    mode: 'no-cors',
  })

  const blob = await response.blob()
  const file = new File([blob], "surveyImage." + imgExt, {
    type: blob.type,
  });
  return file
  */
//}

export const openDeleteModal = (appStore, event, identifier, heading, message) => {
  if(event) event.preventDefault()

  appStore.setModalProps({
    ...identifier,
    heading,
    message,
    confirmButton: {
      label: 'Delete',
      twClass: `
        text-white bg-rose-500 text-xl p-1 hover:bg-rose-600 rounded
        w-[100px]
      `
    },
    cancelButton: {
      label: 'Cancel',
      twClass: `
        text-white bg-slate-500 text-xl p-1 hover:bg-slate-600 rounded
        w-[100px]
      `
    }
  }, { ...modalEventDefaults, name: 'confirm'}, true)
}

export const openAcceptModal = (
  appStore, 
  event, 
  identifier, 
  heading, 
  message, 
  error = false
) => {
  if(event) event.preventDefault()

  const nonErrorStyles = `
    bg-emerald-400 text-xl p-1 hover:bg-emerald-300 
    rounded w-[100px]
  `
  const errorStyles = `
    bg-slate-500 text-xl p-1 hover:bg-slate-600 
    rounded w-[100px] text-white
  `

  appStore.setModalProps({
    ...identifier,
    heading,
    message,
    acceptButton: {
      label: 'Accept',
      twClass: `
        ${error ? errorStyles : nonErrorStyles}
      `
    }
  }, {...modalEventDefaults, name: 'accept'}, true)
}