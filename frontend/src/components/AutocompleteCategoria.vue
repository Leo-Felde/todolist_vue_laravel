<template>
  <q-select
    v-model="selectedCategoria"
    :options="categorias"
    :loading="isLoading"
    option-value="id"
    option-label="titulo"
    map-options
    use-input
    standout
    filled
    eager
    label="Selecione uma categoria"
    clearable
    :readonly="readonly"
    @input-value="onFilter"
  >
    <template #option="scope">
      <q-item v-bind="scope.itemProps">
        <q-item-section>
          <q-item-label>
            <slot
              name="option-label"
              valor="scope"
              :scope="scope"
            />
            <span
              :style="`color: ${scope.opt.cor}`"
              class="text-subtitle2"
            >
              {{ scope.opt.titulo }}
            </span>
          </q-item-label>
        </q-item-section>
      </q-item>
    </template>

    <template
      #selected-item="scope"
    >
      <ChipCategoria :categoria="scope['opt']" />
      <!-- {{ scope['opt']['titulo'] }} -->
    </template>
  </q-select>
</template>

<script>
import { ref } from 'vue'
import CategoriasApi from 'src/api/categorias'
import ChipCategoria from './ChipCategoria.vue'

export default {
  name: 'AutocompleteCategorias',

  components: {
    // eslint-disable-next-line vue/no-unused-components
    ChipCategoria
  },

  props: {
    readonly: Boolean
  },

  setup() {
    const categorias = ref([])

    const selectedCategoria = ref(null)
    const isLoading = ref(false)

    const onFilter = async (filter) => {
      if (!filter) {
        return
      }
      isLoading.value = true
      try {
        const response = await CategoriasApi.getCategorias(`?titulo=${filter}`)
        categorias.value = response.data
      } catch (error) {
        console.error('Erro ao buscar categorias:', error)
      } finally {
        isLoading.value = false
      }
    }

    return {
      categorias,
      selectedCategoria,
      isLoading,
      onFilter,
    }
  },
}
</script>
