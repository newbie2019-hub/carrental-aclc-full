<template>
  <v-container fluid class="vh-100">
    <v-row align="center" justify="center" class="h-100">
      <v-col cols="11" sm="8" md="8" lg="8" xl="9">
        <div class="text-center text-h5 font-grotesk font-weight-bold custom-primary-color">
          <v-icon color="blue darken-3 mr-2"> mdi-car-brake-retarder </v-icon>
          <router-link class="ml-2 text-decoration-none custom-primary-color" to="/">Renta Car</router-link>
        </div>
        <v-card rounded="lg" class="pt-8 pb-7 pl-8 pr-8 mt-5" elevation="1">
          <p class="text-center mb-0 sub-heading">Create Account</p>
          <p class="text-center">Please fill-in all the fields to create an account.</p>

          <v-form ref="form" v-model="valid" @submit.prevent="create" lazy-validation>
            <v-stepper v-model="stepper" alt-labels flat non-linear class="pb-5">
              <v-stepper-header class="elevation-0 pa-0">
                <v-stepper-step class="pa-0 pr-4 pl-4 pt-3" editable step="1" :complete="stepper > 1">
                  <p class="text-center">Account Credentials</p>
                </v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step class="pa-0 pr-4 pl-4 pt-3" editable step="2" :complete="stepper > 2">
                  <p class="text-center">Personal Information</p>
                </v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step class="pa-0 pr-4 pl-4 pt-3" editable step="3" :complete="stepper > 3">
                  <p class="text-center">Confirm</p>
                </v-stepper-step>
              </v-stepper-header>

              <v-stepper-items class="pa-0">
                <v-stepper-content step="1" class="pa-0">
                  <v-row no-gutters align="center" justify="center">
                    <v-col cols="11" sm="11" md="6" lg="6">
                      <v-text-field prepend-inner-icon="mdi-at" class="mt-5 mr-4" outlined hide-details="auto" v-model="data.email" :rules="emailRules" label="Email Address" required></v-text-field>
                      <v-text-field
                        prepend-inner-icon="mdi-phone-in-talk"
                        class="mt-4 mr-4"
                        outlined
                        hide-details="auto"
                        v-model="data.contact_number"
                        :rules="required"
                        label="Contact Number"
                        type="text"
                        required
                      ></v-text-field>
                    </v-col>
                    <v-col cols="11" sm="11" md="6" lg="6" class="">
                      <v-text-field
                        prepend-inner-icon="mdi-key"
                        class="mt-5 mr-4"
                        outlined
                        hide-details="auto"
                        v-model="data.password"
                        :rules="required"
                        label="Password"
                        type="password"
                        required
                      ></v-text-field>
                      <v-file-input
                        @change="uploadValidID"
                        v-model="data.valid_id"
                        prepend-icon=""
                        label="Valid ID"
                        class="mt-4 mr-4"
                        :rules="required"
                        accept="image/*"
                        prepend-inner-icon="mdi-paperclip"
                        outlined
                        hide-details="auto"
                        show-size
                        truncate-length="15"
                      >
                      </v-file-input>
                    </v-col>
                  </v-row>
                  <v-layout justify-space-between class="mt-5 pl-3 pr-3">
                    <p class="text-center mt-5">Already have an account? <router-link class="text-decoration-none" to="/login">Login</router-link></p>
                    <v-btn color="blue darken-2" @click="stepper = 2" dark depressed class="mt-4 mb-4">Continue</v-btn>
                  </v-layout>
                </v-stepper-content>

                <v-stepper-content step="2" class="pa-0">
                  <v-row no-gutters align="center" justify="center">
                    <v-col cols="11" sm="11" md="6" lg="6">
                      <v-text-field
                        prepend-inner-icon="mdi-card-bulleted"
                        class="mt-5 mr-4"
                        outlined
                        hide-details="auto"
                        v-model="data.first_name"
                        :rules="required"
                        label="First Name"
                        required
                      ></v-text-field>
                      <v-text-field
                        prepend-inner-icon="mdi-card-bulleted"
                        class="mt-4 mr-4"
                        outlined
                        hide-details="auto"
                        v-model="data.address"
                        :rules="required"
                        label="Address"
                        type="text"
                        required
                      ></v-text-field>
                    </v-col>
                    <v-col cols="11" sm="11" md="6" lg="6">
                      <v-text-field
                        prepend-inner-icon="mdi-card-bulleted"
                        class="mt-5 mr-4"
                        outlined
                        hide-details="auto"
                        v-model="data.last_name"
                        :rules="required"
                        label="Last Name"
                        required
                      ></v-text-field>
                      <v-file-input
                        @change="uploadProfile"
                        v-model="data.profile_img"
                        prepend-icon=""
                        :rules="required"
                        accept="image/*"
                        label="Profile Image"
                        class="mr-4 mt-4"
                        prepend-inner-icon="mdi-paperclip"
                        outlined
                        hide-details="auto"
                        show-size
                        truncate-length="15"
                      >
                      </v-file-input>
                    </v-col>
                  </v-row>
                  <v-row no-gutters align="center" justify="center">
                    <v-col cols="11" sm="11" md="6" lg="6">
                      <v-select prepend-inner-icon="mdi-card-bulleted" :items="gender" class="mr-4 mt-4" v-model="data.gender" :rules="required" outlined label="Gender" hide-details="auto"></v-select>
                    </v-col>
                    <v-col cols="11" sm="11" md="6" lg="6">
                      <v-menu ref="menu" v-model="menu" :close-on-content-click="false" :return-value.sync="data.birthday" transition="scale-transition" offset-y min-width="auto">
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            class="mr-4 mt-4"
                            hide-details="auto"
                            outlined
                            v-model="data.birthday"
                            :rules="required"
                            label="Date of Birth"
                            prepend-inner-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                          ></v-text-field>
                        </template>
                        <v-date-picker v-model="date"  min="1965-01-01" :max="maxBirthDate" no-title scrollable>
                          <v-spacer></v-spacer>
                          <v-btn text color="primary" @click="menu = false"> Cancel </v-btn>
                          <v-btn text color="primary" @click="$refs.menu.save(date)"> OK </v-btn>
                        </v-date-picker>
                      </v-menu>
                    </v-col>
                  </v-row>
                  <v-layout justify-space-between class="mt-5 pl-3 pr-3">
                    <p class="text-center mt-5">Already have an account? <router-link class="text-decoration-none" to="/login">Login</router-link></p>
                    <v-btn color="blue darken-2" @click="stepper = 3" dark depressed class="mt-4 mb-4">Continue</v-btn>
                  </v-layout>
                </v-stepper-content>

                <v-stepper-content step="3" class="pa-0">
                  <v-layout class="pt-5 pb-5" align-center justify-center>
                    <p class="text-center">
                      Your account informations will be secured by our servers and no information will be given to anyone. <br />Note: By creating your account you are accepting our
                      <a href="" class="text-decoration-none" @click.prevent="showModal">terms and conditions. </a>
                    </p>
                  </v-layout>
                  <v-layout justify-center>
                    <v-btn type="submit" color="green darken-3" dark>Create Account</v-btn>
                  </v-layout>
                </v-stepper-content>
              </v-stepper-items>
            </v-stepper>
          </v-form>
        </v-card>
      </v-col>
    </v-row>
    <Dialog v-show="isModalVisible" @close="closeModal" :showModal="isModalVisible" />
    <v-snackbar v-model="snackbar" :timeout="3500" color="red darken-2">
      All fields are required.
      <template v-slot:action="{ attrs }">
        <v-btn color="white" text v-bind="attrs" @click="snackbar = false"> Close </v-btn>
      </template>
    </v-snackbar>
  </v-container>
</template>
<script>
  import API from '../../store/base/index';
  import Dialog from '../TermsCondition.vue';
  export default {
    components: { Dialog },
    data: () => ({
      data: {
        email: '',
        password: '',
        birthday: '',
        first_name: '',
        last_name: '',
        gender: '',
        address: '',
        contact_number: '',
        valid_id: null,
        valid_id_uploaded: null,
        profile_img: null,
        profile_img_uploaded: null,
      },
      gender: [
        {
          text: 'Male',
          value: 'Male',
        },
        {
          text: 'Female',
          value: 'Female',
        },
      ],
      snackbar: false,
      isModalVisible: false,
      menu: false,
      date: new Date(Date.now() - new Date().getTimezoneOffset() * 60000).toISOString().substr(0, 10),
      valid: true,
      required: [(v) => !!v || 'Field is required'],
      emailRules: [(v) => !!v || 'Email Address is required', (v) => /.+@.+\..+/.test(v) || 'E-mail must be valid'],
      stepper: 1,
      maxBirthDate: '',
    }),
    mounted() {
      this.maxDate();
    },
    methods: {
      showModal() {
        this.isModalVisible = true;
      },
      closeModal() {
        this.isModalVisible = false;
      },
      async create() {
        const valid = this.$refs.form.validate();

        if (valid) {
          this.isLoading = true;
          const { status, data } = await this.$store.dispatch('auth/create', this.data);
          this.$refs.form.reset();
          this.toastData(status, data);
        } else {
          this.snackbar = true;
        }
        this.isLoading = false;
      },
      maxDate() {
        const date = new Date();
        date.get
        const newDate = (date.getFullYear() - 18).toString() + '-' + (date.getMonth() + 1).toString() + '-' + date.getDate().toString();
        this.maxBirthDate = newDate;
        console.log(this.maxBirthDate)
      },
      async uploadProfile(event) {
        if (event) {
          let formData = new FormData();
          formData.append('profile_img', event);
          await API.post(`upload-profile`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          })
            .then((response) => {
              this.data.profile_img_uploaded = response.data;
            })
            .catch((error) => {
              console.log({ error });
            });
        }
      },
      async uploadValidID(event) {
        if (event) {
          let formData = new FormData();
          formData.append('valid_id', event);
          await API.post(`upload-valid_id`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          })
            .then((response) => {
              this.data.valid_id_uploaded = response.data;
            })
            .catch((error) => {
              console.log({ error });
            });
        }
      },
    },
  };
</script>
