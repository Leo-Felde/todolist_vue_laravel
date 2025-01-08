<template>
  <div class="page-container">
    <q-card
      class="card-categorias q-px-md"
      flat
    >
      <div class="categorias-header d-flex q-my-md"> 
        <h6 class="q-pa-none q-ma-none">
          Categorias
        </h6>

        <q-btn
          color="primary"
          class="q-ml-md q-my-auto"
          rounded
          style="max-height: 40px;"
          @click="dialogoCategoria = true"
        >
          Nova categoria
        </q-btn>

        <q-btn
          class="q-ml-auto q-my-auto"
          round
          flat
          icon="more_vert"
          style="max-height: 40px;"
        >
          <q-menu self="top middle">
            <q-list style="min-width: 100px">
              <q-item
                v-close-popup
                clickable
                :disable="!categorias.length"
                @click="toggleEditarCategorias"
              >
                <q-item-section>Editar categoria</q-item-section>
              </q-item>
            </q-list>
          </q-menu>

          <q-tooltip>
            Ver mais opções
          </q-tooltip>
        </q-btn>
      </div>
      <div
        v-if="categorias"
        class="d-flex flex-column"
      >
        <label
          v-show="editandoCategorias"
          class="text-caption q-mx-auto"
        >
          Clique em uma categoria para editar
        </label>
        <div v-if="categorias.length">
          <ChipCategoria
            v-for="categoria in categorias"
            :key="`categoria-${categoria.id}`"
            :categoria="categoria"
            @click="editarCategoria"
          />
        </div>
        <div
          v-else
          style="height: 100%;"
        >
          <span class="text-caption">
            Nenhuma categoria encontrada...
          </span>
        </div>
      </div>
    </q-card>
    <q-card
      class="card-tarefas q-px-md q-pt-lg"
      flat
    >
      <div class="tarefas-header d-flex">
        <q-input
          v-model="search"
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
          Nova tarefa
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
        <div
          v-else
          class="q-mt-md q-ml-md"
          style="height: 100%;"
        >
          <span class="text-caption">
            Nenhuma tarefa encontrada...
          </span>
        </div>
      </div>
    </q-card>
  
    <DialogCriarCategoria
      v-model="dialogoCategoria"
      :categoria="categoriaEditada"
      @atualizar="getCategorias(true)"
    />

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

import ChipCategoria from 'src/components/ChipCategoria.vue'
import CardTarefa from 'src/components/CardTarefa.vue'
import DialogCriarCategoria from 'src/components/DialogCriarCategoria.vue'
import DialogCriarTarefa from 'src/components/DialogCriarTarefa.vue'

import { cloneDeep } from 'lodash-es'

export default defineComponent({
  name: 'ListaTarefas',

  components: {
    ChipCategoria,
    CardTarefa,
    DialogCriarCategoria,
    DialogCriarTarefa 
  },

  setup () {
    const search = ref(null)
    const tarefas = ref([])
    const categorias = ref([])
    const tarefaEditada = ref({})
    const categoriaEditada = ref({})
    const dialogoTarefa = ref(false)
    const dialogoCategoria = ref(false)
    const editandoCategorias = ref(false)

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

    const editarCategoria = (categoria) => {
      if (!editandoCategorias.value) return

      categoriaEditada.value = cloneDeep(categoria)
      dialogoCategoria.value = true
    }

    const toggleEditarCategorias = () => {
      editandoCategorias.value = true
      setTimeout(() => {
        editandoCategorias.value = false
      }, 1000 * 5)
    }

    return {
      search,
      editandoCategorias,
      dialogoTarefa,
      dialogoCategoria,
      tarefas,
      categorias,
      getTarefas,
      getCategorias,
      editarTarefa,
      categoriaEditada,
      tarefaEditada,
      editarCategoria,
      toggleEditarCategorias
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

.card-categorias
  background-color: #68686814
  width: 35%
  height: 100%

.card-tarefas
  width: 65%
  height: 100%

</style>
