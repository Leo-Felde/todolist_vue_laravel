import { createApi } from './index'

const tarefaApi = createApi('/tarefas')

const methods = {
  getTarefas () {
    return tarefaApi.get()
  },

  postTarefa (params) {
    return tarefaApi.post('', params)
  },

  getTarefa (id) {
    return tarefaApi.get(`/${id}`)
  },

  putTarefa (id, params) {
    return tarefaApi.put(`/${id}`, params)
  },

  deleteTarefa (id) {
    return tarefaApi.delete(`/${id}`)
  },

  getSubtarefas (id) {
    return tarefaApi.get(`/${id}/subtarefas`)
  },

  getColaboradores (id) {
    return tarefaApi.get(`/${id}/colaboradores`)
  }
}

export default methods
