<template>
  <div class="d-flex flex-column">
    <span class="q-mx-auto text-h6"> Criar Conta </span>
    <q-separator class="q-my-sm" />

    <q-form ref="formulario">
      <q-input
        ref="nameRef"
        v-model="userName"
        label="Nome"
        standout
        :readonly="loading"
        :rules="[rules.obrigatorio]"
        @keydown.enter="emailRef.focus()"
      />

      <q-input
        ref="emailRef"
        v-model="userEmail"
        label="E-mail"
        standout
        :readonly="loading"
        :rules="[rules.obrigatorio, rules.email]"
        @keydown.enter="passwordRef.focus()"
      />

      <q-input
        ref="passwordRef"
        v-model="password"
        label="Senha"
        standout
        :type="showPassword ? 'text' : 'password'"
        :append-inner-icon="`visibility${showPassword ? '' : '-off'}`"
        :readonly="loading"
        :rules="[rules.obrigatorio, rules.senha]"
        @keydown.enter="createAccount"
      >
        <template #append>
          <q-icon
            :name="showPassword ? 'visibility_off' : 'visibility'"
            class="cursor-pointer"
            @click="showPassword = !showPassword"
          />
        </template>
      </q-input>

      <q-input
        ref="confirmPasswordRef"
        v-model="confirmPassword"
        label="Confirme a senha"
        standout
        :type="showConfirmPassword ? 'text' : 'password'"
        :append-inner-icon="`visibility${showConfirmPassword ? '' : '-off'}`"
        :readonly="loading"
        :rules="[rules.obrigatorio, senhasIguais]"
        @keydown.enter="createAccount"
      >
        <template #append>
          <q-icon
            :name="showConfirmPassword ? 'visibility_off' : 'visibility'"
            class="cursor-pointer"
            @click="showConfirmPassword = !showConfirmPassword"
          />
        </template>
      </q-input>

      <div class="d-flex flex-column">
        <q-btn
          class="full-width q-mt-sm q-py-sm"
          color="blue"
          icon-right="person_add"
          :loading="loading"
          :disable="loading"
          @click="createAccount"
        >
          <span class="q-mr-sm">
            Criar Conta
          </span>
        </q-btn>

        <q-btn
          class="q-mx-auto q-mt-sm" 
          flat
          no-caps
          color="green"
          @click="voltar"
        >
          Voltar ao Login
        </q-btn>
      </div>
    </q-form>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import AuthAPI from '../../api/auth'
import { rules } from 'src/utils/validationRules'
import { notifyError, notifySuccess } from 'src/utils/notify'

export default {
  name: 'CriarConta',

  setup() {
    const router = useRouter()

    const formulario = ref()
    const nameRef = ref()
    const emailRef = ref()
    const passwordRef = ref()
    const confirmPasswordRef = ref()

    const userName = ref(null)
    const userEmail = ref(null)
    const password = ref(null)
    const confirmPassword = ref(null)
    const loading = ref(false)
    const showPassword = ref(false)
    const showConfirmPassword = ref(false)

    const createAccount = async () => {
      const valid = await formulario.value.validate()
      if (!valid) return

      const params = {
        nome: userName.value,
        email: userEmail.value,
        senha: password.value,
        senha_confirmation: confirmPassword.value
      }

      loading.value = true
      try {
        await AuthAPI.criarConta(params)
        notifySuccess('Conta criada com sucesso!')
        router.push('/login')
      } catch (error) {
        console.error(error)
        notifyError('Não foi possível criar a conta. Verifique os dados ou tente novamente mais tarde.')
      } finally {
        loading.value = false
      }
    }

    const senhasIguais = () => {
      if (password.value && confirmPassword.value) {
        return password.value === confirmPassword.value || 'As senhas não são iguais'
      }

      return true
    }

    const voltar = () => {
      router.push('/login')
    }

    return {
      rules,
      formulario,
      nameRef,
      emailRef,
      passwordRef,
      confirmPasswordRef,
      userName,
      userEmail,
      password,
      confirmPassword,
      loading,
      showPassword,
      showConfirmPassword,
      createAccount,
      senhasIguais,
      voltar,
    }
  }
}
</script>
