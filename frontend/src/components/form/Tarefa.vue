<template>
  <div>
    <div class="row q-col-gutter-md">
      <div class="col-6">
        <q-input
          v-model="form.titulo"
          label="Título *"
          standout
          maxlength="80"
          :rules="[rules.obrigatorio, rules.limite80]"
        />
      </div>

      <div class="col-6">
        <q-select
          v-model="form.status"
          label="Status *"
          standout
          emit-value
          :options="statusOptions"
          :rules="[rules.obrigatorio]"
        />
      </div>
    </div>

    <div class="row q-col-gutter-md">
      <div class="col-12">
        <AutocompleteCategoria v-model="form.categoria" />
      </div>
    </div>

    <div class="row q-col-gutter-md">
      <div class="col">
        <q-input
          v-model="form.descricao"
          label="Descrição *"
          type="textarea"
          standout
          counter
          maxlength="200"
          :rules="[rules.obrigatorio, rules.limite200]"
        />
      </div>
    </div>

    <div class="row q-col-gutter-md">
      <div class="q-col-6">
        <InputDatePicker
          v-model="form.data_prazo"
          label="Prazo"
          :min="minDate"
        />
      </div>
      
      <div class="q-col-6">
        <InputDatePicker
          v-model="form.data_conclusao"
          label="Data de Conclusão"
          :readonly="form.status !== 'concluida'"
          :min="minDate"
          standout
        />
      </div>
    </div>
  </div>
</template>

<script>
import { computed, onMounted, ref, watch } from 'vue'
import { rules } from 'src/utils/validationRules'

import AutocompleteCategoria from '../AutocompleteCategoria.vue'
import InputDatePicker from '../InputDatePicker.vue'

import { cloneDeep, isEqual } from 'lodash-es'

export default {
  name: 'TaskForm',

  components: {
    AutocompleteCategoria,
    InputDatePicker
  },

  props: {
    modelValue: {
      type: Object,
      default: () => ({
        titulo: '',
        descricao: '',
        status: 'pendente',
        data_prazo: '',
        created_at: '',
        data_conclusao: ''
      })
    }
  },
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    const form = ref({})

    const statusOptions = [
      { label: 'Pendente', value: 'pendente' },
      { label: 'Cancelada', value: 'cancelada' },
      { label: 'Concluída', value: 'concluida' }
    ]

    onMounted(() => {
      form.value = props.modelValue
    })

    const minDate = computed(() => {
      return new Date().toISOString().split('T')[0] // Limitar a data para não aceitar datas passadas
    })

    const creationDate = computed(() => {
      const date = new Date()
      const day = String(date.getDate()).padStart(2, '0')
      const month = String(date.getMonth() + 1).padStart(2, '0')
      const year = date.getFullYear()
      const hours = String(date.getHours()).padStart(2, '0')
      const minutes = String(date.getMinutes()).padStart(2, '0')
      return `${day}/${month}/${year} ${hours}:${minutes}`
    })

    watch(form.value, () => {
      emit('update:modelValue', { ...form.value })
    })

    watch(() => props.modelValue, () => {
      if (!isEqual(props.modelValue, form.value)) {
        form.value = cloneDeep(props.modelValue)
      }
    })

    return {
      rules,
      form,
      statusOptions,
      minDate,
      creationDate
    }
  }
}
</script>

<style lang="sass" scoped>
.row
  margin-top: 0px !important
</style>
