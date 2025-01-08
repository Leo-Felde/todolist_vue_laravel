import { createApi } from './index'

const tarefaApi = createApi('/tarefas')
const subTarefaApi = createApi('/sub-tarefas')

const methods = {
  getTarefas (params) {
    return tarefaApi.get('', {
      params: {
        ...params
      }
    })
  },

  postTarefa (params) {
    return tarefaApi.post('', params)
  },

  postSubTarefa (params) {
    return subTarefaApi.post('', params)
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
