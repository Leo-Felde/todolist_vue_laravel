import { createApi } from './index'

const categoriaApi = createApi('/categorias')

const methods = {
  getCategorias (params) {
    return categoriaApi.get(params)
  },

  postCategoria (params) {
    return categoriaApi.post('', params)
  },

  getCategoria (categoriaId) {
    return categoriaApi.get(`/${categoriaId}`)
  },

  putCategoria (categoriaId, params) {
    return categoriaApi.put(`/${categoriaId}`, params)
  },

  deleteCategoria (categoriaId) {
    return categoriaApi.delete(`/${categoriaId}`)
  }
}

export default methods
