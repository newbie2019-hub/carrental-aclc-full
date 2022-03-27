<template>
  <v-navigation-drawer width="200" v-model="showDrawer" app>
    <p class="ml-5 font-weight-bold mb-4 mt-8">Main Menu</p>
    <v-list dense v-for="(item, i) in items" :key="i" class="pa-0 mb-1">
      <v-list-item v-if="checkRole(item)" link :to="item.link" active-class="v-list-active--item">
        <v-list-item-content class="ml-4 pa-0">
          <v-list-item-title>
            <v-icon size="22" class="mr-1">
              {{ item.icon }}
            </v-icon>
            {{ item.title }}
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list>
    <p class="ml-5 font-weight-bold mb-4 mt-5">Account</p>
    <v-list dense class="pa-0 mb-1">
      <v-list-item link to="settings" active-class="v-list-active--item">
        <v-list-item-content class="ml-4 pa-0">
          <v-list-item-title>
            <v-icon size="22" class="mr-1"> mdi-cog-refresh-outline </v-icon>
            Settings
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list>
    <template v-slot:append>
      <v-list-item two-line class="mb-4">
        <v-list-item-avatar class="ma-0" color="primary">
          <img class="cursor-pointer" @click="showProfile" v-if="user.info.profile_img" :src="`http://127.0.0.1:8000/images/${user.info.profile_img}`" />
          <p v-else class="white--text font-weight-bold mb-0">{{ user.info.first_name[0] }}{{ user.info.last_name[0] }}</p>
        </v-list-item-avatar>

        <v-list-item-content class="ml-1">
          <v-list-item-title>
            <p class="mb-0 font-weight-bold">{{ `${user.info.last_name}, ${user.info.first_name}` }}</p>
          </v-list-item-title>
          <v-list-item-subtitle>
            <v-btn @click.stop="dialog = true" small color="red darken-2" text class="pa-0">
              <v-icon small> mdi-exit-to-app </v-icon>
              Log-out
            </v-btn>
          </v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
    </template>
    <!-- <v-btn icon @click.stop="close">
        <v-icon>mdi-chevron-left</v-icon>
      </v-btn> -->

    <v-dialog v-model="dialog" max-width="420">
      <v-card>
        <v-card-title class="text-h5"> Confirm Log-out </v-card-title>
        <v-card-text class=""> Are you sure you want to logout your account? </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="dialog = false"> Cancel </v-btn>
          <v-btn color="red darken-1" text @click="logout" :loading="isLoading"> Logout </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-navigation-drawer>
</template>
<script>
  import { mapState } from 'vuex';
  export default {
    props: {
      drawer: Boolean,
    },
    data: () => ({
      dialog: false,
      items: [
        { title: 'Dashboard', icon: 'mdi-chart-pie', link: 'dashboard', role: 'All' },
        { title: 'Branch', icon: 'mdi-source-branch', link: 'branch', role: 'All' },
        { title: 'Cars', icon: 'mdi-car', link: 'cars', role: 'All' },
        { title: 'Brands', icon: 'mdi-car-multiple', link: 'brands', role: 'All' },
        { title: 'Users', icon: 'mdi-account-group-outline', link: 'users', role: 'Admin' },
        { title: 'Drivers', icon: 'mdi-account-group-outline', link: 'driver', role: 'Admin' },
        { title: 'Rentals', icon: 'mdi-notebook', link: 'rentals' , role: 'All'},
        { title: 'Inquiries', icon: 'mdi-forum', link: 'inquiries', role: 'Admin' },
      ],
      overwriteBreakpoint: false,
    }),
    methods: {
      showProfile() {
        this.$viewerApi({
          images: [`http://127.0.0.1:8000/images/${this.user.info.profile_img}`],
          options: {
            'inline': false,
            'button': true,
            'navbar': true,
            'title': true,
            'toolbar': true,
            'tooltip': true,
            'movable': true,
            'zoomable': true,
            'rotatable': true,
            'scalable': true,
            'transition': true,
            'fullscreen': true,
            'keyboard': true,
          },
        });
      },
      close() {
        this.$emit('close');
      },
      checkRole(item) {
        if(item.role == 'All') return true
        if(item.role == 'Admin' && this.user.info.role.role == 'Admin') return true
      },
      async logout() {
        this.isLoading = true;
        const { status, data } = await this.$store.dispatch('auth/logout');
        this.toastData(status, data);
        this.dialog = false;
        this.isLoading = false;
        this.$router.push('/');
      },
    },
    computed: {
      ...mapState('auth', ['user']),
      mini: {
        get() {
          return this.$vuetify.breakpoint.mdAndDown || this.overwriteBreakpoint;
        },
        set(value) {
          this.overwriteBreakpoint = value;
        },
      },
      showDrawer: {
        get() {
          return this.drawer;
        },
        set(value) {
          if (!value) {
            this.$emit('close');
          }
        },
      },
    },
  };
</script>
<style>
  .v-list .v-list-active--item {
    /* background-color: #9c5353; */
    color: #3b85df;
    position: relative;
  }

  .v-list-active--item.v-list-item--link:before {
    content: '';
    opacity: 1;
    background: #3b85df;
    position: absolute;
    top: 0;
    width: 4px;
    border-top-right-radius: 6px;
    border-bottom-right-radius: 6px;
  }
</style>
