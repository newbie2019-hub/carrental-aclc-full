<template>
  <div>
    <!-- <div class="content-container"> -->
    <side-nav v-show="isSidebarVisible" @close="closeModal" :drawer="isSidebarVisible" />
    <v-main>
      <v-app-bar-nav-icon @click.prevent="showModal" class="mt-5 ml-10"></v-app-bar-nav-icon>
      <v-container class="pl-7 pr-7">
        <transition name="fade" mode="out-in">
          <router-view name="dashboard" />
          <router-view name="cars" />
          <router-view name="settings" />
          <router-view name="branch" />
          <router-view name="brands" />
          <router-view name="driver" />
          <router-view name="users" />
          <router-view name="inquiries" />
          <router-view name="rentals" />
        </transition>
      </v-container>
    </v-main>
    <!-- </div> -->
  </div>
</template>
<script>
  import SideNav from '../../components/Dashboard/Sidebar.vue';
  export default {
    components: { SideNav },
    data: () => ({
      isSidebarVisible: true,
    }),
    async mounted() {
      await this.$store.dispatch('auth/checkUser');
    },
    methods: {
      showModal() {
        this.isSidebarVisible = !this.isSidebarVisible;
      },
      closeModal() {
        this.isSidebarVisible = false;
      },
    },
  };
</script>
<style>
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.6s ease;
  }

  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
</style>
