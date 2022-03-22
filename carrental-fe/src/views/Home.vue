<template>
  <div class="home">
    <v-container class="">
      <navbar />
      <v-row class="mt-9">
        <v-col cols="10" md="7" lg="8">
          <p class="heading">
            Search, book, and rent vehicle
            <span class=""><br />easily.</span>
          </p>
          <p class="text-subtitle">
            Have that luxury and comfort driving around the city whether <br />
            it's for personal or office use, vacation or simply <br />
            enjoying the nature's beauty
          </p>
        </v-col>
        <div class="home-car">
          <img src="@/assets/images/car-home.webp" alt="" />
        </div>
      </v-row>
    </v-container>

    <v-container>
      <v-row class="mt-10 mb-8">
        <v-col>
          <v-sheet rounded="lg" class="z-index-1 pa-12" color="white" elevation="2">
            <v-row align="center">
              <v-col>
                <v-select :items="branch" item-value="id" item-text="branch" v-model="data.location_id" filled label="Branch" hide-details="auto"></v-select>
              </v-col>
              <v-col>
                <v-menu ref="menu" v-model="menu" :close-on-content-click="false" :return-value.sync="data.pickup_date" transition="scale-transition" offset-y min-width="auto">
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field hide-details="auto" filled v-model="data.pickup_date" label="Pick-Up Date" prepend-inner-icon="mdi-calendar" readonly v-bind="attrs" v-on="on"></v-text-field>
                  </template>
                  <v-date-picker v-model="date" no-title scrollable>
                    <v-spacer></v-spacer>
                    <v-btn text color="primary" @click="menu = false"> Cancel </v-btn>
                    <v-btn text color="primary" @click="$refs.menu.save(date)"> OK </v-btn>
                  </v-date-picker>
                </v-menu>
              </v-col>
              <v-col>
                <v-menu ref="menu2" v-model="menu2" :close-on-content-click="false" :return-value.sync="data.return_date" transition="scale-transition" offset-y min-width="auto">
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field hide-details="auto" filled v-model="data.return_date" label="Return Date" prepend-inner-icon="mdi-calendar" readonly v-bind="attrs" v-on="on"></v-text-field>
                  </template>
                  <v-date-picker v-model="date" no-title scrollable>
                    <v-spacer></v-spacer>
                    <v-btn text color="primary" @click="menu2 = false"> Cancel </v-btn>
                    <v-btn text color="primary" @click="$refs.menu2.save(date)"> OK </v-btn>
                  </v-date-picker>
                </v-menu>
              </v-col>
            </v-row>
            <p class="mb-0 mt-2 grey--text darken-1 text-subtitle">** Fill in all the fields to search for an available car</p>
          </v-sheet>
        </v-col>
      </v-row>
    </v-container>

    <v-container>
      <v-row class="mt-10 mb-8" justify="center">
        <v-col>
          <p class="text-center sub-heading">Our Services</p>
          <p class="text-center">We provide many of the best services for you and <br />you will get the best benefits here.</p>
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <v-card rounded="lg" class="z-index-1 pa-2">
            <v-list-item three-line>
              <v-list-item-content>
                <div class="text-overline mb-3">
                  <v-icon large color="blue darken-3"> mdi-star </v-icon>
                  <v-icon large color="blue darken-3"> mdi-star </v-icon>
                  <v-icon large color="blue darken-3"> mdi-star </v-icon>
                </div>
                <v-list-item-title class="text-h5 mb-2 font-weight-bold custom-primary-color"> Customer Satisfaction </v-list-item-title>
                <v-list-item-subtitle class="mb-5">We make sure to offer our customer a 100% satisfaction from our services.</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-card>
        </v-col>
        <v-col>
          <v-card rounded="lg" class="z-index-1 pa-2">
            <v-list-item three-line>
              <v-list-item-content>
                <div class="text-overline mb-3">
                  <v-icon large color="blue darken-3"> mdi-cash-multiple </v-icon>
                </div>
                <v-list-item-title class="text-h5 mb-2 font-weight-bold custom-primary-color"> Affordable Price </v-list-item-title>
                <v-list-item-subtitle class="mb-5">We've got the best selection of cars for the most affordable price.</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-card>
        </v-col>
        <v-col>
          <v-card rounded="lg" class="z-index-1 pa-2">
            <v-list-item three-line>
              <v-list-item-content>
                <div class="text-overline mb-3">
                  <v-icon large color="blue darken-3"> mdi-handshake </v-icon>
                </div>
                <v-list-item-title class="text-h5 mb-2 font-weight-bold custom-primary-color"> Easy to Use </v-list-item-title>
                <v-list-item-subtitle class="mb-5">We make it easier for you to be able to rent a car of your dream on our platform.</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-card>
        </v-col>
      </v-row>
    </v-container>

    <v-container id="select-car">
      <v-row class="mt-15 mb-8" justify="center">
        <v-col>
          <p class="text-center sub-heading">Selected Cars</p>
          <p class="text-center">We’ve sorted out the best cars that you might want to rent.</p>
          <carousel-cars />
        </v-col>
      </v-row>
    </v-container>

    <div class="custom-bg-primary pt-10 pb-10 mb-10 mt-15 position-relative">
      <v-container>
        <v-row class="">
          <v-col cols="11" md="6" lg="6">
            <p class="sub-heading white--text">Want to see more cars?</p>
            <p class="white--text">We've got a lot of other cars for rent from our management and our beloved users as well.</p>
            <v-btn depressed>View All</v-btn>
          </v-col>
        </v-row>
      </v-container>
      <img src="@/assets/images/car-home.webp" class="bg-car" alt="" />
    </div>

    <v-container id="aboutus">
      <v-row class="mt-15 mb-8" justify="center">
        <v-col cols="11" md="6" lg="6">
          <p class="sub-heading">Got Some Questions?</p>
          <p class="">If you got any questions, please do not hesitate to send us a message. We will reply within 24 hours!</p>
          <p class="">or you can send us an email at <a class="text-decoration-none" href="mailto:rentacar@gmail.com">rentacar@gmail.com</a></p>
        </v-col>
        <v-col cols="11" md="6" lg="6">
          <v-form ref="form" v-model="valid" @submit.prevent="sendMessage" lazy-validation>
            <v-text-field v-model="query.name" :counter="50" :rules="nameRules" label="Full Name" filled required></v-text-field>
            <v-text-field v-model="query.email" :rules="emailRules" label="Email Address" filled required></v-text-field>
            <v-textarea @keypress.enter="sendMessage" v-model="query.message" :counter="250" :rules="required" label="Message" filled required></v-textarea>
            <v-btn type="submit" color="blue darken-2" dark block depressed :loading="isLoading">Submit</v-btn>
          </v-form>
        </v-col>
      </v-row>
    </v-container>
    <div class="footer">
      <p class="white--text mb-0">ACLC - RENTA CAR 2021</p>
    </div>
  </div>
</template>
<script>
  import { mapState } from 'vuex';
  import Navbar from '../components/Home/Navbar.vue';
  import CarouselCars from '../components/Home/CarouselCars.vue';
  export default {
    name: 'Home',
    components: {
      Navbar,
      CarouselCars,
    },
    async mounted() {
      await this.$store.dispatch('auth/checkUser');
      await this.$store.dispatch('home/getData');
    },
    data: () => ({
      date: new Date(Date.now() - new Date().getTimezoneOffset() * 60000).toISOString().substr(0, 10),
      menu: false,
      menu2: false,
      items: ['Foo', 'Bar', 'Fizz', 'Buzz'],
      valid: true,
      data: {
        return_date: null,
        pickup_date: null,
        location_id: null,
      },
      query: {
        name: '',
        email: '',
        message: '',
      },
      nameRules: [(v) => !!v || 'Name is required', (v) => (v && v.length <= 50) || 'Name must be less than 10 characters'],
      required: [(v) => !!v || 'Field is required'],
      emailRules: [(v) => !!v || 'E-mail is required', (v) => /.+@.+\..+/.test(v) || 'E-mail must be valid'],
    }),
    methods: {
      async sendMessage() {
        const valid = this.$refs.form.validate();

        if (valid) {
          this.isLoading = true;
          const { status, data } = await this.$store.dispatch('inquiry/sendMessage', this.query);
          this.toastData(status, data);
          this.$refs.form.reset();
          this.isLoading = false;
        }
      },
    },
    computed: {
      ...mapState('home', ['cars', 'branch']),
    },
  };
</script>
