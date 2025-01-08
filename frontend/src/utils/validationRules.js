import { ref } from 'vue'

export const rules = ref({
  obrigatorio: value => (value !== '' && value !== undefined && value !== null) || 'Campo obrigatório.',
  notZero: value => value > 0 || 'Não deve ser menor que 1.',
  counter: value => value.length <= 20 || 'Max 20 characters.',
  sigla: value => value.length <= 2 || 'Não deve ter mais de 2 caracteres.',
  limite200: value => value.length <= 30 || 'Não deve ter mais de 200 caracteres.',
  limite10: value => value.length <= 10 || 'Não deve ter mais de 10 caracteres.',
  limiteTextarea: value => value ? value.length < 300 || 'Não deve ter mais de 300 caracteres.': true,
  limiteTextareaMaior: value => value ? value.length < 500 || 'Não deve ter mais de 500 caracteres.': true,
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
