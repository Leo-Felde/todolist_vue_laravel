<template>
  <q-input
    v-model="inputValue"
    :rounded="rounded"
    :standout="standout"
    :dense="dense"
    :hide-bottom-space="hideBottomSpace"
    :clearable="clearable"
    :mask="mask"
    :label="label"
    :readonly="readonly"
    :rules="usedRules"
  >
    <template #append>
      <q-btn
        round
        flat
        icon="event"
        class="cursor-pointer"
        :disable="readonly"
      >
        <q-popup-proxy
          v-model="menuDatepicker"
          cover
          transition-show="scale"
          transition-hide="scale"
        >
          <q-date
            v-if="!periodo"
            v-model="inputValue"
            no-unset
            color="primary"
            minimal
            :mask="qDateMask"
            :disable="readonly"
            @update:modelValue="menuDatepicker = false"
          />
        </q-popup-proxy>
      </q-btn>
    </template>
  </q-input>
</template>

<script>
import { computed, onMounted, ref, watch } from 'vue'

export default {
  props: {
    modelValue: { 
      type: String,
      default: undefined
    },
    toValue: {
      type: String,
      default: 'to'
    },
    fromValue: {
      type: String,
      default: 'from'
    },
    standout: {
      type: Boolean,
      default: true
    },
    rounded: Boolean,
    readonly: Boolean,
    dataHora: Boolean,
    hideBottomSpace: Boolean,
    dense: Boolean,
    clearable: Boolean,
    periodo: Boolean,
    label: {
      type: String,
      default: 'Data'
    },
    rules: {
      type: [Boolean, Array],
      default: undefined
    }
  },

  emits: ['update:modelValue'],
  setup (props, { emit }) {
    const inputValue = ref(null)
    const menuDatepicker = ref(false)

    const mask = computed (() => {
      return `##/##/####${props.dataHora ? ' ##:##' : ''}`
    })

    const qDateMask = computed (() => {
      return `DD/MM/YYYY${props.dataHora ? ' HH:mm' : ''}`
    }) 

    watch(() => props.modelValue, (newValue) => {
      inputValue.value = newValue
    })

    watch(() => inputValue.value, () => {
      emit('update:modelValue', inputValue.value === '' ? null : inputValue.value)
    })

    const usedRules = computed(() => {
      const rules = [].concat(props.rules)

      rules.push((value) => {
        if (!value) return true
        const dataHora = value.replace(' - ', '')
        if (dataHora.length > 10) {
          const pattern = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4} (0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/
          return pattern.test(value) || 'Data inválida'
        } else {
          const pattern = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/
          return pattern.test(dataHora) || 'Data inválida'
        }
      })
      
      
      return rules
    })

    onMounted (() => {
      if (props.modelValue) {
        inputValue.value = props.modelValue
      }
    })

    return {
      inputValue,
      menuDatepicker,
      mask,
      qDateMask,
      usedRules
    }
  }
}
</script>
