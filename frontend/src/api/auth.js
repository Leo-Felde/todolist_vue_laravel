import axios from 'axios'

const baseUrl = `${process.env.baseUrl}:${process.env.basePort}${process.env.baseApp}`

const methods = {
  login (params) {
    return axios.post(`${baseUrl}/login`, {
      ...params,
    })
  },

  criarConta (params) {
    return axios.post(`${baseUrl}/usuario`, {
      ...params,
    })
  },

  logout () {
    return axios.post(`${baseUrl}/logout`)
  }
}

export default methods
