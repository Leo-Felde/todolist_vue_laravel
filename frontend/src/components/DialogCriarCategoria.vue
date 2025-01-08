<template>
  <q-dialog
    v-model="isDialogVisible"
    persistent
  >
    <q-card class="dialog-card">
      <q-linear-progress
        v-if="loading"
        indeterminate
      />

      <q-card-section>
        <div class="text-h6">
          {{ categoryData.id ? 'Editar' : 'Criar' }} Categoria
        </div>
      </q-card-section>

      <q-card-section>
        <q-form ref="formulario">
          <CategoriaForm v-model="categoryData" />
        </q-form>
      </q-card-section>

      <q-card-actions class="d-flex">
        <q-btn
          v-if="categoryData.id"
          label="Excluir"
          color="red"
          class="q-mr-auto"
          @click="excluirCategoria"
        />
        <q-btn
          label="Cancelar"
          flat
          class="q-ml-auto"
          @click="closeDialog"
        />
        <q-btn
          label="Salvar"
          color="primary"
          class="q-ml-md"
          :loading="loading"
          :disable="loading"
          @click="salvarCategoria"
        />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
import { ref, watch } from 'vue'
import CategoriaForm from './form/Categoria.vue'
import CategoriasApi from 'src/api/categorias'
import { notifyError, notifySuccess } from 'src/utils/notify'
import { showConfirmDelete } from 'src/utils/promptDialog'

export default {
  name: 'CategoriaDialog',
  components: {
    CategoriaForm
  },
  props: {
    modelValue: {
      type: Boolean,
      default: false
    },
    categoria: {
      type: Object,
      default: () => ({})
    }
  },
  emits: ['update:modelValue', 'atualizar'],
  setup(props, { emit }) {
    const formulario = ref()
    const isDialogVisible = ref(props.modelValue)
    const loading = ref(false)
    const categoryData = ref({
      titulo: '',
      descricao: '',
      cor: '#ffffff'
    })

    watch(
      () => props.modelValue,
      (newValue) => {
        isDialogVisible.value = newValue
      }
    )

    watch(
      () => props.categoria,
      (newValue) => {
        if (newValue.id) {
          categoryData.value = { ...newValue }
        } else {
          resetarFormulario()
        }
      }
    )

    watch(isDialogVisible, (newValue) => {
      emit('update:modelValue', newValue)
    })

    const closeDialog = () => {
      isDialogVisible.value = false
      resetarFormulario()
      emit('update:modelValue', false)
    }

    const salvarCategoria = async () => {
      const valid = await formulario.value.validate()
      if (!valid) return

      loading.value = true
      try {
        if (categoryData.value.id) {
          await CategoriasApi.putCategoria(categoryData.value.id, categoryData.value)
          notifySuccess('Categoria editada com sucesso')
          emit('atualizar')
        } else {
          await CategoriasApi.postCategoria(categoryData.value)
          notifySuccess('Categoria criada com sucesso')
          emit('atualizar')
        }
        closeDialog()
      } catch (error) {
        console.error(error)
        notifyError('Não foi possível salvar a categoria')
      } finally {
        loading.value = false
      }
    }

    const excluirCategoria = async () => {
      const confirm = await showConfirmDelete(
        'Excluir Categoria',
        'Deseja excluir esta categoria? Isso não poderá ser revertido.'
      )
      if (confirm) {
        try {
          await CategoriasApi.deleteCategoria(categoryData.value.id)
          notifySuccess('Categoria excluída com sucesso')
          emit('atualizar')
          closeDialog()
        } catch (error) {
          console.error(error)
          notifyError('Não foi possível excluir a categoria')
        }
      }
    }

    const resetarFormulario = () => {
      categoryData.value = {
        titulo: '',
        descricao: '',
        cor: '#ffffff'
      }
    }

    return {
      formulario,
      isDialogVisible,
      categoryData,
      loading,
      closeDialog,
      salvarCategoria,
      excluirCategoria
    }
  }
}
</script>

<style scoped>
.dialog-card {
  min-width: 400px;
  max-width: 600px;
}
</style>
