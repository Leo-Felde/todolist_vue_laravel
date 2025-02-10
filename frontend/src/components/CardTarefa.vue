<template>
  <div class="d-flex">
    <q-card
      class="q-mx-md q-mb-sm card-tarefa"
      bordered
      flat
      :style="estilosCategoria()"
    >
      <q-card-section class="q-pb-none">
        <div
          class="q-mt-sm task-status"
        >
          <label class="tarefa-data-criacao"> Criada {{ formatarData(tarefa.created_at) }}</label>
          <q-chip 
            :color="statusCor(tarefa.status)"
            :text-color="statusTextoCor(tarefa.status)"
            class="q-ml-sm"
          >
            {{ tarefa.status }}
          </q-chip>
        </div>

        <q-checkbox
          v-model="concluida"
          :color="concluida === true || concluida === false ? 'primary' : 'red'"
          :disable="concluida"
          indeterminate-icon="block"
          class="text-h6"
          :label="tarefa.titulo"
          @update:model-value="concluirTarefa"
        />

        <div class="text-subtitle2 descricao-tarefa q-mt-sm q-ml-sm">
          {{ tarefa.descricao }}
        </div>
      </q-card-section>

      <q-card-section class="q-pb-none">
        <div>
          <span v-if="tarefa.data_conclusao">
            <q-icon
              name="event_available"
              size="sm"
            />
            {{ formatarData(tarefa.data_conclusao) }}
          </span>
          <span v-else-if="tarefa.data_prazo">
            <q-icon
              name="alarm"
              size="sm"
            />
            {{ formatarData(tarefa.data_prazo) }}
            <q-tooltip>
              Prazo
            </q-tooltip>
          </span>
        </div>
      </q-card-section>
      <q-card-section class="d-flex">
        <q-btn
          v-show="concluida === false"
          round
          flat
          icon="delete"
          color="red"
          class="q-ml-auto"
          @click="excluirTarefa"
        />

        <q-btn
          round
          flat
          icon="edit"
          color="green"
          :class="{'q-ml-auto' : concluida !== false}"
          @click="$emit('editar', tarefa)"
        />
      </q-card-section>
    </q-card>
    <div class="d-flex flex-column sub-tarefas">
      <q-card
        v-for="subTarefa in tarefa.subtarefas"
        :key="`tarefa-${tarefa.id}-subtarefa-${subTarefa.id}`"
        bordered
        flat
        class="card-subtarefa"
      >
        <q-card-section class="q-pa-sm">
          <span class="q-my-none q-ml-none q-mr-sm">
            {{ subTarefa.titulo }}
          </span>
          <q-chip 
            :color="statusCor(subTarefa.status)"
            :text-color="statusTextoCor(subTarefa.status)"
            class="q-ml-sm"
            size="sm"
          >
            {{ subTarefa.status }}
          </q-chip>

          <div class="text-subtitle2 descricao-tarefa q-mt-sm q-ml-md">
            {{ subTarefa.descricao }}
          </div>
        </q-card-section>
        <div>
          <q-btn
            v-show="concluida === false && subTarefa.status === 'pendente'"
            round
            flat
            icon="delete"
            color="red"
            class="q-ml-auto"
            @click="cancelarSubTarefa(subTarefa)"
          />

          <q-btn
            round
            flat
            icon="edit"
            color="green"
            :class="{'q-ml-auto' : concluida !== false}"
            @click="$emit('editarSubtarefa', subTarefa)"
          />
        </div>
      </q-card>
      <q-btn
        v-show="concluida === false && tarefa.subtarefas.length < 3"
        flat
        color="primary"
        class="q-mx-auto q-mt-lg"
        @click="$emit('adicionar-subtarefa', tarefa)"	
      >
        Adicionar subtarefa
      </q-btn>
    </div>
  </div>
</template>

<script>
import { showCustomConfirmDialog } from 'src/utils/promptDialog'
import { computed, defineComponent } from 'vue'

import TarefasApi from 'src/api/tarefas'
import { notifyError, notifySuccess } from 'src/utils/notify'

export default defineComponent({
  name: 'CardTarefa',

  props: {
    tarefa: {
      type: Object,
      required: true,
      validator: (value) => 
        ['titulo', 'descricao', 'data_prazo', 'data_conclusao', 'status'].every(
          (key) => key in value
        )
    }
  },

  emits: ['atualizar', 'editar', 'adicionar-subtarefa'],
  setup (props, { emit }) {

    const concluida = computed(() => {
      switch (props.tarefa.status) {
      case 'pendente':
        return false
      case 'concluida':
        return true
      case 'cancelada':
        return null
      default:
        return null
      }
    })

    const statusTextoCor = (status) => {
      return status === 'pendente' || status === 'cancelada'
        ? 'black'
        : 'white'
    }

    const statusCor = (status) => {
      switch (status) {
      case 'pendente':
        return 'yellow'
      case 'concluida':
        return 'green'
      case 'cancelada':
        return 'red'
      default:
        return 'grey'
      }
    }

    const formatarData = (data) => {
      const opcoes = { year: 'numeric', month: 'long', day: 'numeric' }
      return new Date(data).toLocaleDateString('pt-BR', opcoes)
    }

    const concluirTarefa = async () => {
      const options = {
        ok: { label: 'Concluir tarefa', color: 'primary' },
      }

      const confirm = await showCustomConfirmDialog('Concluir tarefa', 'Concluir a tarefa selecionada?', options.ok)
      if (!confirm) {
        return
      }      

      try {
        await TarefasApi.putTarefa(props.tarefa.id, { 'status': 'concluida'})

        notifySuccess('Tarefa concluída')
        emit('atualizar')
      } catch (error) {
        console.error(error)
        notifyError('Não foi possível concluir a tarefa')
      }
    }

    const excluirTarefa = async () => {
      const options = {
        ok: { label: 'Cancelar tarefa', color: 'primary' },
      }

      const confirm = await showCustomConfirmDialog('Cancelar Tarefa', 'Cancelar a tarefa selecionada?', options.ok)
      if (!confirm) {
        return
      } 

      try {
        await TarefasApi.putTarefa(props.tarefa.id, { 'status': 'cancelada' })

        notifySuccess('Tarefa cancelada')
        emit('atualizar')
      } catch (error) {
        console.error(error)
        notifyError('Não foi possível cancelar a tarefa')
      }
    }

    const cancelarSubTarefa = async (subTarefa) => {
      const options = {
        ok: { label: 'Cancelar subTarefa', color: 'primary' },
      }

      const confirm = await showCustomConfirmDialog('Cancelar subTarefa', 'Cancelar a subTarefa selecionada?', options.ok)
      if (!confirm) {
        return
      } 

      try {
        await TarefasApi.putTarefa(subTarefa.id, { 'status': 'cancelada' })

        emit('atualizar')
        notifySuccess('Tarefa cancelada')
      } catch (error) {
        console.error(error)
        notifyError('Não foi possível cancelar a tarefa')
      }
    }

    const estilosCategoria = () => {
      if (props.tarefa.categoria) {
        return `border-left: 5px solid ${props.tarefa.categoria.cor}`
      } else {
        return 'border-left: 5px solid #aeaeae2b'	
      }
    }

    return {
      concluida,
      statusTextoCor,
      statusCor,
      formatarData,
      concluirTarefa,
      excluirTarefa,
      cancelarSubTarefa,
      estilosCategoria
    }
  }
})
</script>

<style lang="sass" scoped>
.text-h6
  font-weight: bold

.text-subtitle2 
  font-size: 14px
  color: #757575

.task-status
  display: flex
  flex-direction: column
  position: absolute
  top: 0px
  right: 15px
  margin-top: 0px

.tarefa-data-criacao
  font-size: 0.7rem
  margin-top: 8px

.q-chip
  max-width: fit-content
  margin-left: auto

.card-tarefa
  max-width: 500px
  width: 100%
  border-right: none !important
  border-top-right-radius: 0px
  border-bottom-right-radius: 0px

.card-subtarefa
  margin-top: 5px
  width: 300px
  border-right: none !important
  border-left: none !important
  border-top-right-radius: 0px
  border-bottom-right-radius: 0px


.descricao-tarefa
  overflow: hidden
  text-overflow: ellipsis

:deep(.q-checkbox__label)
  max-width: 326px !important
  overflow: hidden
  text-overflow: ellipsis
  max-height: 30px 

.sub-tarefas
  max-height: 200px
  overflow-y: auto
</style>
