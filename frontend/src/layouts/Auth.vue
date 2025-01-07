<template>
  <div class="auth-wrapper">
    <q-card
      class="auth-card"
    >
      <router-view />
    </q-card>
  </div>
</template>

<style lang="sass" scoped>
.auth
  &-wrapper
    display: flex
    height: 100vh
  &-card
    margin: auto
    padding: 18px
    width: 100%
    max-width: 400px
    min-width: 300px

</style>