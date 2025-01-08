<template>
  <div class="page-container">
    <q-card
      class="card-elementos q-px-md"
      flat
    >
      <!-- a fazer -->
    </q-card>
    <q-card
      class="card-tarefas q-px-md q-mt-lg"
      flat
    >
      <div class="d-flex">
        <q-input
          class="input-pesquisa"
          label="Pesquisar"
          hide-bottom-space
          dense
          clearable
          standout
          rounded
          style="max-width: 500px;"
        >
          <template #prepend>
            <q-icon name="search" />
          </template>
        </q-input>

        <q-btn
          color="primary"
          class="q-ml-md"
          rounded
          @click="dialogoTarefa = true"
        >
          Criar tarefa
        </q-btn>
      </div>

      <div>
        <template v-if="tarefas.length">
          <CardTarefa
            v-for="tarefa in tarefas"
            :key="`tarefa-${tarefa.id}`"
            :tarefa="tarefa"
            @editar="editarTarefa"
            @atualizar="getTarefas(true)"
          />
        </template>
      </div>
    </q-card>

    <DialogCriarTarefa
      v-model="dialogoTarefa"
      :tarefa="tarefaEditada"
      @atualizar="getTarefas(true)"
    />
  </div>
</template>

<script>
import { defineComponent, onMounted, ref, watch } from 'vue'

import TarefasApi from '../api/tarefas'
import CategoriasApi from '../api/categorias'

import CardTarefa from 'src/components/CardTarefa.vue'
import DialogCriarTarefa from 'src/components/DialogCriarTarefa.vue'
import { cloneDeep } from 'lodash-es'

export default defineComponent({
  name: 'ListaTarefas',

  components: {
    CardTarefa,
    DialogCriarTarefa 
  },

  setup () {
    const tarefas = ref([])
    const categorias = ref([])
    const tarefaEditada = ref({})
    const dialogoTarefa = ref(false)

    onMounted(() => {
      getTarefas()
      getCategorias()
    })

    watch(dialogoTarefa, (newValue) => {
      if (!newValue) tarefaEditada.value = {}
    })
    
    const getTarefas = async (resetar = false) => {
      if (resetar) {
        tarefas.value = []
      }

      try {
        const resp = await TarefasApi.getTarefas()

        tarefas.value = resp.data
      } catch (err) {
        console.error(err)
      }
    }

    const getCategorias = async (resetar = false) => {
      if (resetar) {
        categorias.value = []
      }

      try {
        const resp = await CategoriasApi.getCategorias()

        categorias.value = resp.data
      } catch (err) {
        console.error(err)
      }
    }

    const editarTarefa = (tarefa) => {
      tarefaEditada.value = cloneDeep(tarefa)
      dialogoTarefa.value = true
    }

    return {
      dialogoTarefa,
      tarefas,
      categorias,
      getTarefas,
      editarTarefa,
      tarefaEditada
    }
  }
})
</script>

<style lang="sass" scoped>
.input-pesquisa
  width: 40% !important

.page-container
  display: flex
  height: 100vh
  margin: 0
  padding: 0

.card
  display: flex
  flex-direction: column
  justify-content: center
  align-items: center
  padding: 1rem
  border-radius: 8px

.card-elementos
  background-color: #68686814
  width: 35%
  height: 100%

.card-tarefas
  width: 65%
  height: 100%

</style>
