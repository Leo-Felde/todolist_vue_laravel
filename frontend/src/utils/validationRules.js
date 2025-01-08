import { ref } from 'vue'

export const rules = ref({
  obrigatorio: value => (value !== '' && value !== undefined && value !== null) || 'Campo obrigatório.',
  notZero: value => value > 0 || 'Não deve ser menor que 1.',
  counter: value => value.length <= 20 || 'Max 20 characters.',
  sigla: value => value.length <= 2 || 'Não deve ter mais de 2 caracteres.',
  limite200: value => value.length <= 200 || 'Não deve ter mais de 200 caracteres.',
  limite80: value => value.length <= 80 || 'Não deve ter mais de 50 caracteres.',
  limite50: value => value.length <= 50 || 'Não deve ter mais de 50 caracteres.',
  email: value => {
    const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    return value ? pattern.test(value) || 'E-mail inválido.': true
  },

  cpf: value => {
    const pattern = /^\d{3}\.\d{3}\.\d{3}-\d{2}$/
    return value ? pattern.test(value) || 'Cpf inválido.': true
  },

  senha: value => {
    return value.length >= 8 || 'Deve ter no minimo 8 characteres.'
  },

  data: value => {
    const pattern = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/
    return value ? pattern.test(value) || 'Data inválida.' : true
  }
})
