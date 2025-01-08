<template>
  <q-chip 
    text-color="white"
    :style="`background: ${categoria.cor || '#fefefe'}`"
    class="q-ml-sm"
    style="user-select: none;"
    clickable
    @click="$emit('click', categoria)"
  >
    {{ categoria.titulo }}
    <q-tooltip :delay="400">
      {{ categoria.descricao }}
    </q-tooltip>
  </q-chip>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'ChipCategoria',

  props: {
    categoria: {
      type: Object,
      required: true
    }
  },

  emits: ['click'],
  setup () {

  }
})

</script>