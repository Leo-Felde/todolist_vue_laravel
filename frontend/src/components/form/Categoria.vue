<template>
  <div>
    <div class="row q-col-gutter-md">
      <div class="col-6">
        <q-input
          v-model="form.titulo"
          label="Título *"
          standout
          maxlength="50"
          :rules="[rules.obrigatorio, rules.limite50]"
        />
      </div>

      <div class="col-6">
        <q-input
          v-model="form.cor"
          label="Cor *"
          standout
          type="text"
          maxlength="7"
          :rules="[rules.obrigatorio, rules.hexadecimal]"
        >
          <template #prepend>
            <q-avatar :style="`background: ${form.cor || '#fefefe'}`" />
          </template>
          <template #append>
            <q-btn
              flat
              dense
              color="primary"
              icon="palette"
              @click="openColorPicker = true"
            />
          </template>
        </q-input>
        <q-popup-proxy
          v-model="openColorPicker"
          transition-show="scale"
          transition-hide="scale"
        >
          <q-color
            v-model="form.cor"
            format="hex"
            :default-view="'palette'"
          />
        </q-popup-proxy>
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
          maxlength="80"
          :rules="[rules.obrigatorio, rules.limite80]"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { ref, watch } from 'vue'
import { rules } from 'src/utils/validationRules'
import { cloneDeep, isEqual } from 'lodash-es'

export default {
  name: 'CategoriaForm',
  props: {
    modelValue: {
      type: Object,
      default: () => ({
        titulo: '',
        descricao: '',
        cor: '#ffffff',
      })
    }
  },
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    const form = ref({ ...props.modelValue })
    const openColorPicker = ref(false)

    watch(form, () => {
      emit('update:modelValue', { ...form.value })
    }, { deep: true })

    watch(() => props.modelValue, (newVal) => {
      if (!isEqual(newVal, form.value)) {
        form.value = cloneDeep(newVal)
      }
    })

    return {
      form,
      rules,
      openColorPicker,
    }
  }
}
</script>

<style scoped lang="sass">
.row
  margin-top: 0px !important

:deep(.q-color-picker__header-content)
  display: none
</style>
