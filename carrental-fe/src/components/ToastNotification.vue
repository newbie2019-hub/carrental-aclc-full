<template>
 <div class="notification">
  <v-alert class="" :value="isVisible" border="left" :type="alertType" transition="scroll-x-transition">{{msg}}</v-alert>
 </div>
</template>
<script>
import {mapState} from 'vuex'
export default {
 computed: {
  ...mapState('alert', ['msg', 'isVisible', 'alertType'])
 },
 watch: {
  msg(){
    if(this.isVisible){
     setTimeout(() => {
      const alert = {
        isVisible: false,
      }
  
      this.$store.commit('alert/setAlert', alert)
     }, 3500)  
    }
  }
 }
}
</script>
<style scoped>
.notification {
 position: fixed;
 top: 1.5rem;
 left: 1.5rem;
 max-width: 380px;
 z-index: 9;
}
</style>