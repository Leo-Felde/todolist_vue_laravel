<template>
  <q-dialog
    v-model="isDialogVisible"
    persistent
  >
    <q-card class="dialog-card">
      <q-linear-progress
        v-if="loading"
        indeterminate
      />

      <q-card-section class="q-pb-none">
        <div class="text-h6 q-py-none">
          {{ getTitulo() }}
        </div>
      </q-card-section>

      <q-card-section class="q-pt-none">
        <q-form ref="formulario">
          <FormTarefa
            v-model="taskData"
            :sub-tarefa="!!tarefaPai.id"
          />
        </q-form>
      </q-card-section>

      <q-card-actions class="d-flex">
        <q-btn
          label="Excluir"
          color="red"
          class="q-mr-auto"
          @click="excluirTarefa"
        />
        <q-btn
          label="Cancelar"
          flat
          class="q-ml-auto"
          @click="closeDialog"
        />
        <q-btn
          label="Salvar"
          color="primary"
          class="q-ml-md"
          :loading="loading"
          :disable="loading"
          @click="salvarTarefa"
        />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
import { ref, watch } from 'vue'

import TarefasApi from 'src/api/tarefas'

import { notifyError, notifySuccess } from 'src/utils/notify'

import FormTarefa from './form/Tarefa.vue'
import { showConfirmDelete } from 'src/utils/promptDialog'

export default {
  name: 'TarefaDialog',
  components: {
    FormTarefa
  },

  props: {
    modelValue: {
      type: Boolean,
      default: false
    },
    tarefa: {
      type: Object,
      default: () => {}
    },
    tarefaPai: {
      type: Object,
      default: () => {}
    }
  },

  emits: ['update:modelValue', 'atualizar'],
  setup(props, { emit }) {
    const formulario = ref()
    const isDialogVisible = ref(props.modelValue)
    const loading = ref(false)
    const taskData = ref({
      titulo: '',
      descricao: '',
      status: 'pendente',
      data_prazo: '',
      created_at: '',
      data_conclusao: ''
    })
    
    watch(() => props.modelValue, (newValue) => {
      isDialogVisible.value = newValue
    })

    watch(() => props.tarefaPai, (newValue) => {
      if (newValue.id) {
        taskData.value.categoria = newValue.categoria
      }
    })

    watch(() => props.tarefa, (newValue) => {
      if (newValue.id) {
        taskData.value = formatarDatasEdicao(newValue)
      } else {
        taskData.value = {
          titulo: '',
          descricao: '',
          status: 'pendente',
          data_prazo: '',
          created_at: '',
          data_conclusao: ''
        }
      }
    })

    watch(isDialogVisible, (newValue) => {
      emit('update:modelValue', newValue)
    })
    
    const getTitulo = () => {
      return taskData.value.id ? 'Editar Tarefa' : props.tarefaPai?.id ? 'Criar Subtarefa' : 'Criar Tarefa'
    }

    const openDialog = () => {
      isDialogVisible.value = true
    }

    const closeDialog = () => {
      isDialogVisible.value = false
      resetarFormulario()
      emit('update:modelValue', false)
    }

    const salvarTarefa = async () => {
      const valid = await formulario.value.validate()
      if (!valid) return

      loading.value = true
      if (taskData.value?.categoria?.id) {
        taskData.value.id_categoria = taskData.value.categoria?.id
        delete taskData.value.categoria
      }
      try {
        if (taskData.value?.id) {
          await TarefasApi.putTarefa(taskData.value.id, taskData.value)

          notifySuccess('Tarefa editada com sucesso')
          emit('atualizar')
        } else {
          const resp = await TarefasApi.postTarefa(taskData.value)          
          if (props.tarefaPai?.id) {
            const params = {
              id_tarefa: props.tarefaPai.id,
              id_subtarefas: [resp.data.id]
            }

            await TarefasApi.postSubTarefa(params)
            notifySuccess('Subtarefa criada com sucesso')
          } else {
            notifySuccess('Tarefa criada com sucesso')
          }

          emit('atualizar')
        }

        closeDialog()
      } catch (error) {
        console.error(error)
        notifyError('Não foi possível salvar a tarefa')
      } finally {
        loading.value = false
      }
    }

    const excluirTarefa = async () => {
      const confirm = await showConfirmDelete('Excluir Tarefa','Deseja excluir essa Tarefa? Isso não poderá ser revertido')
      if (confirm) {
        try {
          await TarefasApi.deleteTarefa(taskData.value.id)
          notifySuccess('Tarefa excluida com sucesso')
          emit('atualizar')
          closeDialog()
        } catch (error) {
          console.error(error)
          notifyError('Não foi possível excluir a tarefa')
        }
      }
    }

    const formatarDatasEdicao = (tarefa) => {
      const objetosData = ['data_prazo', 'data_conclusao']
      const dadosFormatados = { ...tarefa }

      objetosData.forEach(campoData => {
        if (tarefa[campoData]) {
          const data = new Date(tarefa[campoData])
          const dataFormatada = data.toLocaleDateString('pt-BR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
          })

          dadosFormatados[campoData] = dataFormatada
        }
      })

      return dadosFormatados
    }

    const resetarFormulario = () => {
      taskData.value = {}
    }

    return {
      formulario,
      getTitulo,
      isDialogVisible,
      taskData,
      loading,
      openDialog,
      closeDialog,
      salvarTarefa,
      excluirTarefa
    }
  }
}
</script>

