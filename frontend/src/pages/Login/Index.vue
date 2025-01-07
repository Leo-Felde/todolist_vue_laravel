<template>
  <div class="d-flex flex-column">
    <span class="q-mx-auto text-h6"> Login </span>
    <q-separator class="q-my-sm" />

    <q-card
      v-if="invalidCredentials"
      flat
      color="red"
      class="q-pa-sm q-mb-sm d-flex"
      bordered
      style="background-color: #ff000014; border-radius: 0;"
    >
      <div
        class="q-mx-auto d-flex flex-column text-center"
        style="color: black"
      >
        <span> Credenciais inválidas </span>
        <span class="text-caption"> Usuário ou senha incorretos </span>
      </div>
    </q-card>
    <q-form ref="formulario">
      <q-input
        ref="email"
        v-model="userEmail"
        label="E-mail"
        standout
        :readonly="loading"
        :rules="[rules.obrigatorio, rules.email]"
        @keydown.enter="pswd.focus()"
      />

      <q-input
        ref="pswd"
        v-model="password"
        label="Senha"
        standout
        :rules="[rules.obrigatorio]"
        :type="showPassword ? 'text' : 'password'"
        :append-inner-icon="`visibility${showPassword ? '' : '-off'}`"
        :readonly="loading"
        @keydown.enter="login"
      >
        <template #append>
          <q-icon
            :name="showPassword ? 'visibility_off' : 'visibility'"
            class="cursor-pointer"
            @click="showPassword = !showPassword"
          />
        </template>
      </q-input>

      <div class="d-flex flex-column">
        <q-btn
          class="full-width q-mt-sm q-py-sm"
          color="green"
          icon-right="login"
          :loading="loading"
          :disable="loading"
          @click="login"
        >
          <span class="q-mr-sm">
            Entrar
          </span>
        </q-btn>

        <q-btn
          class="q-mx-auto q-mt-sm" 
          color="primary"
          flat
          no-caps
          @click="esqueceuSenha"
        >
          Esqueceu a senha?
        </q-btn>
      </div>
    </q-form>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

import AuthAPI from '../../api/auth'

import { setCookie } from '../../utils/cookies'
import { rules } from 'src/utils/validationRules'
import { notifyError } from 'src/utils/notify'

export default {
  name: 'Index',

  setup () {
    const router = useRouter()
    
    const formulario = ref()
    const email = ref()
    const pswd = ref()

    const userEmail = ref(null)
    const password = ref(null)
    const loading = ref(false)
    const invalidCredentials = ref(false)
    const showPassword = ref(false)

    const login = async () => {
      const valid = await formulario.value.validate()
      if (!valid) return

      const params = {
        email: userEmail.value,
        senha: password.value
      }

      loading.value = true
      try {
        const resp = await AuthAPI.login(params)

        const usuarioFormatado = {
          token: resp.data.token,
          id: resp.data.user.id,
          nome: resp.data.user.nome,
          email: resp.data.user.email,
        }

        localStorage.setItem('usuario', JSON.stringify(usuarioFormatado))
        setCookie('token', resp.data.token)

        invalidCredentials.value = false
        router.push('/')
      } catch (error) {
        console.error(error)
        if (error.response?.status === 401 || error.response?.status === 403) {
          invalidCredentials.value = true
          return
        }
  
        notifyError('Não foi possível se conectar com o servidor de login. Tente novamente mais tarde')
      } finally {
        loading.value = false
      }
    }

    const cancelar = async () => {
      await formulario.value.resetValidation()

      showPassword.value = false
      userEmail.value = null
      password.value = null
    }

    const esqueceuSenha = async () => {
      // A FAZER  
    }

    return {
      rules,
      formulario,
      email,
      pswd,
      userEmail,
      password,
      loading,
      invalidCredentials,
      showPassword,
      login,
      cancelar,
      esqueceuSenha
    }
  }
}
</script>