<template>
  <v-container fluid class="vh-100">
    <v-row align="center" justify="center" class="h-100">
      <v-col cols="11" sm="8" md="8" lg="5" xl="6">
        <div class="text-center text-h5 font-grotesk font-weight-bold custom-primary-color">
          <v-icon color="blue darken-3 mr-2"> mdi-car-brake-retarder </v-icon>
          <router-link class="ml-2 text-decoration-none custom-primary-color" to="/">Renta Car</router-link>
        </div>
        <v-card rounded="lg" class="pt-8 pb-7 pl-4 pr-4 mt-5" elevation="1">
          <p class="text-center mb-0 sub-heading">Welcome Back</p>
          <p class="text-center">Enter your credentials to access your account.</p>
          <v-row align="center" justify="center">
            <v-col cols="11" sm="9" md="10" lg="8">
              <v-form ref="form" v-model="valid" @submit.prevent="login" lazy-validation>
                <v-text-field prepend-inner-icon="mdi-at" class="mt-5" outlined hide-details="auto" v-model="data.email" :rules="emailRules" label="Email Address" required></v-text-field>
                <v-text-field prepend-inner-icon="mdi-key" class="mt-4" outlined hide-details="auto" v-model="data.password" :rules="required" label="Password" type="password" required></v-text-field>
                <v-btn type="submit" block color="green darken-2" dark depressed class="mt-4 mb-4" :loading="isLoading">Sign In</v-btn>
              </v-form>
              <p class="text-center mt-5">Dont have an account? <router-link class="text-decoration-none" to="/signup">Sign-Up</router-link></p>
            </v-col>
          </v-row>
        </v-card>
        <v-layout justify-center class="mt-7">
          <router-link class="text-center text-decoration-none" to="/forgot-password">Forgot Password?</router-link>
        </v-layout>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
  export default {
    data: () => ({
      data: {
        email: '',
        password: '',
      },
      valid: true,
      required: [(v) => !!v || 'Field is required'],
      emailRules: [(v) => !!v || 'Email Address is required', (v) => /.+@.+\..+/.test(v) || 'E-mail must be valid'],
    }),
    methods: {
      async login() {
        const valid = this.$refs.form.validate();
        if (valid) {
          this.isLoading = true;
          const { status } = await this.$store.dispatch('auth/login', this.data);

          if (status == 200) {
            const data = { msg: 'Login successful! Welcome back.' };
            this.$router.push('/user/dashboard');
            this.toastData(status, data);
          } else {
            const data = { msg: 'Entered credentials are invalid. Please try again.' };
            this.showToast(data.msg, 'error');
          }

          this.isLoading = false;
          this.$refs.form.reset();
        }
      },
    },
  };
</script>
