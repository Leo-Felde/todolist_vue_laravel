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
      <q-input
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
    </q-card>
  </div>
</template>

<script>
import { defineComponent, onMounted, ref } from 'vue'

import TarefasApi from '../api/tarefas'

export default defineComponent({
  name: 'TwoCardPage',

  setup () {
    const tarefas = ref([])

    onMounted(() => {
      getTarefas()
    })
    
    const getTarefas = async (resetar = false) => {
      if (resetar) {
        tarefas.value = []
      }

      try {
        const resp = await TarefasApi.getTarefas()
        console.log(resp.data)

        tarefas.value = resp.data
      } catch (err) {
        console.error(err)
      }
    }

    return {
      tarefas
    }
  }
})
</script>

<style lang="sass" scoped>
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
