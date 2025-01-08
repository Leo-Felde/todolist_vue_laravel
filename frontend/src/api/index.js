import axios from 'axios'
import { Router } from '../router' // Certifique-se de exportar o roteador do arquivo de configuração.

import { getCookie } from '../utils/cookies'

const AppURL = `${process.env.baseUrl}:${process.env.basePort}${process.env.baseApp}`

export const createApi = (routeURL = '', responseType = 'json', usarToken = true, intecept401 = true) => {
  routeURL = routeURL.startsWith('/') ? routeURL.slice(1) : routeURL
  const baseURL = `${AppURL}${routeURL ? '/' : ''}${routeURL}`

  const api = axios.create({
    baseURL,
    responseType,
  })

  const token = getCookie('token')
  
  api.interceptors.request.use((config) => {
    config.params = {
      ...config.params,
    }
    
    if (usarToken) {
      config.headers['Authorization'] = `Bearer ${token}`
    }

    return config
  })

  api.interceptors.response.use(
    undefined,
    (error) => {
      if (error?.response?.status === 401 && intecept401) {
        Router.push('/login')
      }

      return Promise.reject(error)
    }
  )

  return api
}

export default createApi
