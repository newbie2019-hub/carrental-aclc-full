<template>
  <div class="nav" :class="{ 'navbar-fixed': isFixed }">
    <div class="nav-logo">
      <v-icon color="primary darken-3"> mdi-car-brake-retarder </v-icon>
     <span class="nav-logo--text"> Renta Car</span>
    </div>
    <ul>
      <li><router-link to="/home">Home</router-link></li>
      <li><a href="#select-car">Rent</a></li>
      <li><a href="#aboutus">About</a></li>
      <li v-if="!user.id"><router-link to="/login">Login</router-link></li>
      <li v-else><router-link to="/user/dashboard">Account</router-link></li>
    </ul>
  </div>
</template>
<script>
import { mapState } from 'vuex';
  export default {
    data: () => ({
      isFixed: false,
    }),
    computed: {
      ...mapState('auth', ['user'])
    },
    created() {
      window.addEventListener('scroll', this.handleScroll);
    },
    destroyed() {
      window.removeEventListener('scroll', this.handleScroll);
    },
    methods: {
      handleScroll(event) {
        this.scrollPosition = window.scrollY;
        if (this.scrollPosition >= 120) {
          this.isFixed = true;
        } else {
          this.isFixed = false;
        }
      },
    },
  };
</script>
<style lang="scss">
  .nav {
    position: sticky;
    top: 0;
    left: 0;
    padding: 1.5rem 0rem;
    display: flex;
    width: 100%;
    align-items: center;
    // transition: all 300ms;

    & .nav-logo {
      font-size: 1.5rem;
      font-weight: 600;
      margin-right: 10rem;
      color: #224773;
    }

    &.navbar-fixed {
      position: fixed;
      top: 0;
      left: 0;
      z-index: 5;
      background: rgba(255, 255, 255, 0.2);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(5px);
      -webkit-backdrop-filter: blur(5px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.3);
      padding: 1.5rem 4rem;
    }
  }

  .nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
  }

  .nav ul li {
    padding: 0rem 1.5rem;
  }

  .nav ul li a {
    text-decoration: none;
    color: rgb(24, 24, 24);
    font-weight: 600;
    position: relative;
    transition: all 450ms;

    &::before {
      position: absolute;
      content: '';
      bottom: 0;
      left: 0;
      width: 0%;
      height: 2px;
      background: #224773;
      transition: all 300ms;
    }

    &:hover {
      color: #224773;

      &::before {
        content: '';
        width: 100%;
      }
    }
  }
</style>
