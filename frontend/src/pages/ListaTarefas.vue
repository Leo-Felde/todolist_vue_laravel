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
      <div v-if="loadingCategorias">
        <q-linear-progress
          indeterminate
          class="q-mb-md"
        />
        <div class="d-flex">
          <q-skeleton
            v-for="i in 3"
            :key="`skeleton-categoria-${i}`"
            class="q-mx-sm"
            type="QChip"
          />
        </div>
      </div>
      <div
        v-else-if="categorias.length"
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
      <div class="tarefas-header d-flex q-pb-md">
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
          @keydown.enter="getTarefas"
          @clear="getTarefas"
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

      <div
        class="tarefas-list"
        @scroll="handleScroll"
      >
        <template v-if="tarefas">
          <div
            v-for="categoriaTarefas in Object.keys(tarefas)"
            :key="`categoriaTarefa-${categoriaTarefas}`"
          >
            <span
              v-if="categoriaTarefas === '-1' || !categorias.find(c => c.id === Number(categoriaTarefas))"
              class="q-my-none q-ml-md text-h6"
            >
              Sem categoria
            </span>
            <ChipCategoria
              v-else
              class="q-mt-md q-mb-xs q-ml-md"
              :categoria="categorias.find(c => c.id === Number(categoriaTarefas))"
            />
            <CardTarefa
              v-for="tarefa in tarefas[categoriaTarefas]"
              :key="`tarefa-${tarefa.id}`"
              :tarefa="tarefa"
              @editar="editarTarefa"
              @editarSubtarefa="editarSubTarefa"
              @atualizar="getTarefas(true)"
              @adicionarSubtarefa="criarSubtarefa"
            />
          </div>
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
        <div v-if="loadingTarefas">
          <q-linear-progress
            indeterminate
            class="q-mb-md"
          />
          <div class="d-flex flex-column">
            <q-skeleton
              v-for="i in 3"
              :key="`skeleton-tarefa-${i}`"
              class="q-my-sm"
              width="500px"
              height="150px"
            />
          </div>
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
      :tarefa-pai="tarefaPai"
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
    const tarefaPai = ref({})

    // paginação
    const page = ref(1)
    const hasMore = ref(true)

    const loadingTarefas = ref(false)
    const loadingCategorias = ref(false)

    const dialogoTarefa = ref(false)
    const dialogoCategoria = ref(false)
    const editandoCategorias = ref(false)

    onMounted(() => {
      getTarefas()
      getCategorias()
    })

    watch(dialogoTarefa, (newValue) => {
      if (!newValue) {
        tarefaEditada.value = {}
        tarefaPai.value = {}
      }
    })
    
    const getTarefas = async (resetar = false) => {
      if (!hasMore.value && !resetar) return
      
      try {
        const params = {
          page: resetar ? 1 : page.value,
          titulo: search.value
        }
        const resp = await TarefasApi.getTarefas(params)
        

        if (resp.data?.data.length > 0) {
          const groupedTarefas = resp.data.data.reduce((acc, tarefa) => {
            const categoria = tarefa.categoria?.id || -1
            if (!acc[categoria]) {
              acc[categoria] = []
            }
            acc[categoria].push(tarefa)
            return acc
          }, {})
          
          tarefas.value = resetar
            ? groupedTarefas
            : { ...tarefas.value, ...groupedTarefas }
          page.value += 1
        } else {
          hasMore.value = false
        }
      } catch (err) {
        console.error(err)
      } finally {
        loadingTarefas.value = false
      }
    }

    const getCategorias = async () => {
      loadingCategorias.value = true

      try {
        const resp = await CategoriasApi.getCategorias()

        categorias.value = resp.data
      } catch (err) {
        console.error(err)
      } finally {
        loadingCategorias.value = false
      }
    }

    const editarTarefa = (tarefa) => {
      tarefaEditada.value = cloneDeep(tarefa)
      dialogoTarefa.value = true
    }
    
    const editarSubTarefa = (subTarefa) => {
      tarefaEditada.value = cloneDeep(subTarefa)
      tarefaPai.value = cloneDeep(subTarefa)
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

    const criarSubtarefa = (tarefa) => {
      tarefaPai.value = cloneDeep(tarefa)
      dialogoTarefa.value = true
    }

    const handleScroll = (event) => {
      const container = event.target
      const nearBottom =
      container.scrollHeight - container.scrollTop <=
      container.clientHeight * 1.5

      if (nearBottom) {
        getTarefas()
      }
    }

    return {
      search,
      editandoCategorias,
      dialogoTarefa,
      dialogoCategoria,
      loadingTarefas,
      loadingCategorias,
      tarefas,
      tarefaPai,
      page,
      hasMore,
      categorias,
      getTarefas,
      getCategorias,
      editarTarefa,
      editarSubTarefa,
      categoriaEditada,
      tarefaEditada,
      editarCategoria,
      toggleEditarCategorias,
      criarSubtarefa,
      handleScroll
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

.tarefas-list
  height: calc(100dvh - 82px)
  overflow-y: auto
</style>
