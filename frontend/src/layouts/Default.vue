<template>
  <q-layout view="hHh Lpr fff">
    <Drawer
      :key="mobileNavigation"
      v-model="drawer"
    />

    <q-page-container>
      <router-view />
    </q-page-container>
    
    <q-footer
      v-if="mobileNavigation"
      reveal
      bordered
      style="background: transparent !important; height: 58px;"
    >
      <q-card class="d-flex justify-center q-py-sm">
        <q-btn
          round
          :color="$q.dark.isActive ? 'white' : 'black'"
          flat
          icon="sym_o_menu"
          class="q-mx-sm"
          @click="drawer = true"
        />
        <q-btn
          round
          :color="$q.dark.isActive ? 'white' : 'black'"
          flat
          icon="sym_o_home"
          class="q-mx-sm"
          to="/"
        />
        <q-btn
          round
          :color="$q.dark.isActive ? 'white' : 'black'"
          flat
          icon="sym_o_account_circle"
          class="q-mx-sm"
          @click="rightDrawer = true"
        />
      </q-card>
    </q-footer>
  </q-layout>
</template>


<script>
import { onMounted, onUnmounted, ref } from 'vue'

import { useQuasar } from 'quasar'
import Drawer from '../components/Layout/Drawer.vue'

export default {
  components: {
    Drawer
  },

  setup () {
    const drawer = ref(true)
    const mobileNavigation = ref(false)
    const $q = useQuasar()

    onMounted(() => {
      checkMobileNavigation()

      window.addEventListener('resize', () => {
        checkMobileNavigation()
      })
    })

    onUnmounted(() => {
      window.removeEventListener('resize' , () => {
        checkMobileNavigation()
      })
    })

    const checkMobileNavigation = () => {
      mobileNavigation.value = $q.screen.lt.sm || $q.screen.lt.xs 
    }
    
    return {
      drawer,
      mobileNavigation
    }
  }
}
</script>