<template>
  <!-- https://github.com/Leo-Felde/theme-button -->
  <div
    id="theme-button"
    :class="themeDark ? 'night' : 'day'"
    @click="toggleTheme"
  >
    <div id="icons">
      <div
        id="moving-icon"
      />
      <div
        id="moving-gradient"
      >
        <div
          v-for="i in 3"
          :id="`gradient-${i}`"
          :key="i"
          :class="themeDark ? 'night' : 'day'"
        />
      </div>
      <div id="moon">
        <div
          v-for="i in 3"
          :id="`crater-${i}`"
          :key="i"
        />
      </div>
    </div>

    <div
      id="clouds"
    >
      <div
        v-for="i in 6"
        :id="`cloud-${i}`"
        :key="i"
      />
      
      <div
        v-for="i in 6"
        :id="`cloud-background-${i}`"
        :key="i"
      />
    </div>

    <div
      id="stars"
    >
      <div
        v-for="i in 11"
        :id="`star-${i}`"
        :key="i"
      />
    </div>
  </div>
</template>

<script>
import { useQuasar } from 'quasar'
import getCookie, { setCookie } from 'src/utils/cookies'
import { onMounted, ref } from 'vue'

export default {
  name: 'ThemeButton',
  
  emits: ['toggleTheme'],

  setup (_, { emit }) {
    const themeDark = ref(false) // isso seria legal vim do banco
    const transitioning = ref(false)
    const $q = useQuasar()

    const toggleTheme = () => {
      if (transitioning.value) return // impede de ficar clicando igual um maluco
      transitioning.value = true
      
      themeDark.value = !themeDark.value
      emit('toggleTheme')
      $q.dark.set(themeDark.value)

      setCookie('theme', themeDark.value ? 'dark' : 'light')

      handleClassList()

      setTimeout(() => {
        transitioning.value = false
      }, 1000)
    }

    const handleClassList = () => {
      const movingIconElement = document.getElementById('moving-icon')
      const gradientElement = document.getElementById('moving-gradient')
      const moonElement = document.getElementById('moon')
      const cloudsElement = document.getElementById('clouds')
      const starsElement = document.getElementById('stars')

      if (themeDark.value) {
        movingIconElement.classList.add('animate') 
        gradientElement.classList.add('animate')
        moonElement.classList.add('animate')
        cloudsElement.classList.add('animate')
        starsElement.classList.add('animate')
        movingIconElement.classList.remove('animate-reverse')
        gradientElement.classList.remove('animate-reverse')
        moonElement.classList.remove('animate-reverse')
        cloudsElement.classList.remove('animate-reverse')
        starsElement.classList.remove('animate-reverse')
      } else {
        movingIconElement.classList.add('animate-reverse') 
        gradientElement.classList.add('animate-reverse')
        moonElement.classList.add('animate-reverse')
        cloudsElement.classList.add('animate-reverse')
        starsElement.classList.add('animate-reverse')

        movingIconElement.classList.remove('animate')
        gradientElement.classList.remove('animate')
        moonElement.classList.remove('animate')
        cloudsElement.classList.remove('animate')
        starsElement.classList.remove('animate')
      }
    }

    onMounted(() => {
      const savedTheme = getCookie('theme')
      if (savedTheme) {
        $q.dark.set(savedTheme === 'dark' ? true : false)
      }
      themeDark.value = $q.dark.isActive
      handleClassList()
    })

    return {
      themeDark,
      toggleTheme
    }
  }
}
</script>

<style lang="sass">
#theme-button
  height: 30px
  width: 80px
  border-radius: 20px
  display: flex
  position: relative
  box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75), inset 0px 10px 15px -3px rgba(0,0,0,0.1)
  z-index: 1
  overflow: hidden
  cursor: pointer
  transition: background-color 700ms linear
  &.night
    background: rgb(27, 30, 42)
  &.day
    background: rgb(47, 118, 185)
  
#icons
  position: absolute
  height: 100%
  width: 100%
  display: flex

#moving-icon
  height: 20px
  width: 20px
  left: 5px
  border-radius: 50%
  background: #fbeb01
  margin-top: auto
  margin-bottom: auto
  position: relative
  z-index: 1
  box-shadow: inset 0px -20px 12px -19px rgba(0,0,0,0.75)
  &.animate
    animation: slide-x 1.3s 1 forwards
  &.animate-reverse
    animation: slide-x-reverse 1.3s 1 forwards

@keyframes slide-x
  0%
    background: #eedf00
    left: 5px
    top: 0px
  100%
    background: rgb(195, 201, 210)
    left: 55px
    top: 0px
@keyframes slide-x-reverse
  0%
    background: rgb(195, 201, 210)
    left: 55px
    top: 0px
  100%
    background: #eedf00
    left: 5px
    top: 0px

#clouds
  position: relative
  width: 100%
  &.animate
    animation: slide-y 1.3s 1 forwards
  &.animate-reverse
    animation: slide-y-reverse 1.3s 1 forwards

@keyframes slide-y
  0%
    top: 0px
  100%
    top: 40px
@keyframes slide-y-reverse
  0%
    top: 40px
  100%
    top: 0px

#cloud
  background: white
  position: absolute
  height: 28px
  width: 28px
  border-radius: 50%
  bottom: -22px
  z-index: 0
  &-1
    @extend #cloud
    left: 0px
    bottom: -22px
  &-2
    @extend #cloud
    left: 18px
    bottom: -20px
  &-3
    @extend #cloud
    right: 20px
    bottom: -24px

  &-4
    @extend #cloud
    right: 12px
    bottom: -21px

  &-5
    @extend #cloud
    right: -2px
    bottom: -16px
  &-6
    @extend #cloud
    right: -13px
    bottom: -6px

#cloud-background
  background: rgb(198, 219, 236)
  position: absolute
  height: 25px
  width: 25px
  border-radius: 47%
  bottom: -22px
  z-index: -1
  &-1
    @extend #cloud-background
    left: 5px
    bottom: -15px
  &-2
    @extend #cloud-background
    left: 21px
    bottom: -12px
  &-3
    @extend #cloud-background
    right: 20px
    bottom: -16px

  &-4
    @extend #cloud-background
    right: 15px
    bottom: -12px

  &-5
    @extend #cloud-background
    right: 4px
    bottom: -5px
  &-6
    @extend #cloud-background
    right: -8px
    bottom: 5px

#moving-gradient
  position: absolute
  #gradient
    border-radius: 50%
    position: absolute
    transition: background-color 500ms linear
    &.day
      background: rgba(255, 254, 254, 0.11)
    &.night
      background: rgba(255, 254, 254, 0.03)
    &-1
      @extend #gradient
      height: 80px
      width: 80px
      top: -24px
      left: -24px
    &-2
      @extend #gradient
      height: 60px
      width: 60px
      top: -15px
      left: -15px
    &-3
      @extend #gradient
      height: 36px
      width: 36px
      top: -4px
      left: -4px
  &.animate
    animation: slide-x-out 1.3s 1 forwards
  &.animate-reverse
    animation: slide-x-out-reverse 1.3s 1 forwards

@keyframes slide-x-out
  0%
    background: #eedf00
    left: 0px
  100%
    background: white
    left: 50px
    top: 0px
@keyframes slide-x-out-reverse
  0%
    background: white
    left: 50px
    top: 0px
  100%
    background: #eedf00
    left: 0px
    top: 0px

#moon
  position: absolute
  z-index: 1
  opacity: 0%
  #crater
    background: rgb(148, 158, 177)
    border-radius: 50%
    position: absolute
    &-1
      @extend #crater
      left: 15px
      top: 8px
      height: 4px
      width: 4px
    &-2
      @extend #crater
      left: 8px
      top: 12px
      height: 7px
      width: 7px
    &-3
      @extend #crater
      left: 16px
      top: 17px
      height: 4px
      width: 4px
  &.animate
    animation: slide-x-invisible 1.3s 1 forwards
  &.animate-reverse
    animation: slide-x-invisible-reverse 1.3s 1 forwards
@keyframes slide-x-invisible
  0%
    background: #eedf00
    opacity: 0%
    left: 5px
    top: 0px
  100%
    background: rgb(195, 201, 210)
    opacity: 100%
    left: 50px
    top: 0px
@keyframes slide-x-invisible-reverse
  0%
    background: rgb(195, 201, 210)
    opacity: 100%
    left: 50px
    top: 0px
  100%
    background: #eedf00
    opacity: 0%
    left: 5px
    top: 0px

#stars
  position: absolute
  width: 100%
  top: 40px
  &.animate
    animation: slide-y-invisible 1.3s 1 forwards
  &.animate-reverse
    animation: slide-y-invisible-reverse 1.3s 1 forwards
#star
  position: absolute
  margin: 1em auto
  width: 1em
  font-size: 12em
  &:before
    content: ""
    position: absolute
    background: white
    width: 1em
    height: 1.15em
    transform: rotate(-45deg) skewX(22.5deg) skewY(22.5deg)
  &:after
    content: ""
    position: absolute
    background: white
    width: 1em
    height: 1.15em
    transform: rotate(45deg) skewX(22.5deg) skewY(22.5deg)
  &-1
    @extend #star
    font-size: 2px
    right: 30px
    top: 5px
  &-2
    @extend #star
    font-size: 1px
    right: 32px
    top: 13px
  &-3
    @extend #star
    font-size: 1.5px
    right: 38px
    top: 18px
  &-4
    @extend #star
    font-size: 1px
    right: 40px
    top: 5px
  &-5
    @extend #star
    font-size: 0.8px
    right: 45px
    top: 11px
  &-6
    @extend #star
    font-size: 0.8px
    left: 20px
    top: 14px
  &-7
    @extend #star
    font-size: 2px
    left: 20px
    top: 2px
  &-8
    @extend #star
    font-size: 0.8px
    left: 21px
    top: 25px
  &-9
    @extend #star
    font-size: 0.6px
    left: 10px
    top: 20px
  &-10
    @extend #star
    font-size: 0.6px
    left: 8px
    top: 23px
  &-11
    @extend #star
    font-size: 1px
    left: 9px
    top: 8px

@keyframes slide-y-invisible
  0%
    top: -40px
    opacity: 0%
  100%
    top: 0px
    opacity: 100%
@keyframes slide-y-invisible-reverse
  0%
    top: 0px
    opacity: 100%
  100%
    top: -40px
    opacity: 0%
</style>