<template>
  <q-card
    class="q-ma-md"
    bordered
  >
    <q-card-section>
      <div class="text-h6">
        {{ tarefa.titulo }}
      </div>
      <div class="text-subtitle2 q-mt-sm">
        {{ tarefa.descricao }}
      </div>
    </q-card-section>

    <q-separator />

    <q-card-section>
      <div>
        <strong>Data:</strong> 
        <span v-if="tarefa.data_conclusao">
          {{ formatarData(tarefa.data_conclusao) }} (Concluída)
        </span>
        <span v-else-if="tarefa.data_prazo">
          {{ formatarData(tarefa.data_prazo) }} (Prazo)
        </span>
        <span v-else>
          Não informado
        </span>
      </div>

      <div class="q-mt-sm">
        <strong>Status:</strong>
        <q-chip 
          :color="statusCor"
          :text-color="statusTextoCor"
          outline
          class="q-ml-sm"
        >
          {{ tarefa.status }}
        </q-chip>
      </div>
    </q-card-section>
  </q-card>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'TaskCard',

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

  computed: {
    statusCor() {
      switch (this.tarefa.status) {
      case 'pendente':
        return 'yellow'
      case 'iniciada':
        return 'blue'
      case 'concluida':
        return 'green'
      case 'cancelada':
        return 'red'
      default:
        return 'grey'
      }
    },
    statusTextoCor() {
      return this.tarefa.status === 'pendente' || this.tarefa.status === 'cancelada'
        ? 'black'
        : 'white'
    }
  },

  methods: {
    formatarData(data) {
      const opcoes = { year: 'numeric', month: 'long', day: 'numeric' }
      return new Date(data).toLocaleDateString('pt-BR', opcoes)
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

</style>
