import { Notify } from 'quasar'

export const notifySuccess = (message, options = {}) => {
  notify(message, 'positive', options)
}

export const notifyError = (message, options = {}) => {
  notify(message, 'negative', options)
}

export const notifyWarning = (message, options = {}) => {
  notify(message, 'warning', options)
}

export const notify = (message, type, options = {}) => {
  Notify.create({
    message: message,
    type: type,
    position: 'top-right',
    timeout: 3000,
    ...options
  })
}

export const notifyPersist = (message, type, options = {}) => {
  Notify.create({
    message: message,
    type: type,
    position: 'top-right',
    ...options,
    actions: [
      { icon: 'sym_o_close',
        color: 'white',
        round: true,
        handler: () => { return true }
      }
    ]
  })
}

export default notify
