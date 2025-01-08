<template>
  <q-drawer
    v-model="drawer"
    show-if-above
    side="left"
    behavior="desktop"
    bordered
    :width="100"
  >
    <q-scroll-area class="fit">
      <q-list>
        <q-item
          class="q-mt-sm"
        >
          <ThemeButton />
        </q-item>
        <q-item class="d-flex flex-column">
          <h5 class="q-mt-none q-mb-none q-mx-auto">
            Todo
          </h5>
          <span class="text-subtitle2 q-mx-auto text-primary">
            <q-icon
              name="fact_check"
              color="primary"
              size="sm"
            />
            List
          </span>
        </q-item>
        <template
          v-for="(item, index) in menuList"
          :key="`navdrawer-item${index}`"
        >
          <q-item
            v-ripple
            clickable
            :to="item.to"
          >
            <q-icon
              :name="item.icon"
              size="md"
              class="q-mx-auto"
            />
          </q-item>
        </template>
      </q-list>
    </q-scroll-area>
  </q-drawer>
</template>

<script>
import { onMounted, ref, watch } from 'vue'
import ThemeButton from '../ThemeButton.vue'

export default {
  
  components: {
    ThemeButton,
  },

  props: {
    modelValue: {
      type: Boolean,
      default: true
    },
    mobile: Boolean,
  },
  
  emits: ['update:modelValue'],
  setup (props, { emit }) {
    const drawer = ref(props.modelValue)
    const menuList = ref([
      {
        icon: 'equalizer',
        label: 'Início',
        to: '/'
      },
      {
        icon: 'checklist',
        label: 'Checklist',
        to: '/tasks'
      }
    ])

    onMounted(() => {
      drawer.value = props.modelValue
    })

    watch(drawer, () => {
      emit('update:modelValue', drawer.value)
    })

    watch(() => props.modelValue, () => {
      drawer.value = props.modelValue
    })

    return {
      drawer,
      menuList
    }
  }
}
</script>