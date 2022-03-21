<template>
  <v-app>
    <router-view />
    <toast />
  </v-app>
</template>
<script>
  import Toast from './components/ToastNotification.vue';
  export default {
    components: { Toast },
    watch: {
      async $route(to, from) {
        if (to.meta.auth) return;
        await this.$store.dispatch('auth/checkUser');
      },
    },
  };
</script>
<style></style>
