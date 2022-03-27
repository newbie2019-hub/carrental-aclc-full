<template>
  <div class="pl-lg-6 pl-md-3">
    <div>
      <p class="text-h5 font-weight-bold mt-4 custom-primary-color mb-0">Overall Summary</p>
      <p>Good Day! Here is your summary for Renta Car</p>
    </div>
    <v-row>
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="blue darken-1" class="pa-3" rounded="lg" dark>
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-car-brake-retarder </v-icon>
              </div>
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Car <br />Brands</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ brands }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="deep-purple" class="pa-3" rounded="lg" dark>
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-facebook-workplace </v-icon>
              </div>
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Active <br />Rents</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ rentals }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="blue-grey" class="pa-3" rounded="lg" dark>
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-car </v-icon>
              </div>
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Total <br />Cars</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ cars }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
      <v-col v-if="user.info.role.role == 'Admin'" cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="teal" class="pa-3" rounded="lg" dark>
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-forum </v-icon>
              </div>
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">User <br />Inquiry</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ inquiry }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
      <v-col v-if="user.info.role.role == 'Admin'" cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="green " class="pa-3" rounded="lg" dark>
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-account </v-icon>
              </div>
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Total <br />Users</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ users }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="deep-orange lighten-1 " class="pa-3" rounded="lg" dark>
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-source-branch </v-icon>
              </div>
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Total <br />Branch</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ branch }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>

    <div class="mt-14">
      <p class="text-h5 font-weight-bold mt-4 custom-primary-color mb-0">Latest Transaction</p>
      <p>Shown below are the latest transactions for this day</p>
      <v-data-table :headers="headers" :items="latest_transactions" :search="search" :loading="isLoading" :loading-text="'Retrieving rentals data. Please wait ...'">
        <template v-slot:item.invoice="{ item }">
          <a v-if="item.rental_info.payment_type == 'On Branch'" class="text-decoration-none" :href="`http://127.0.0.1:8000${item.invoice}`" target="_">View Invoice</a>
          <a v-else class="text-decoration-none" :href="`${item.invoice}`" target="_">View Invoice</a>
        </template>
        <template v-slot:item.user.info.last_name="{ item }">
          <p class="text-no-wrap">{{ item.user.info.last_name }}, {{ item.user.info.first_name }} {{ item.user.info.middle_name ? item.user.info.middle_name[0] : '' }}</p>
        </template>
        <template v-slot:item.total_payment="{ item }">
          <p class="text-no-wrap">₱ {{ formatCurrency(item.rental_info.total_payment) }}</p>
        </template>
        <template v-slot:item.rental_info.payment_status="{ item }">
          <v-chip small dark :color="item.rental_info.payment_status == 'Pending' ? 'red' : 'green'">{{ item.rental_info.payment_status }}</v-chip>
        </template>
        <template v-slot:item.car.brand.brand="{ item }">
          <p class="text-no-wrap">{{ item.car.brand.brand }} {{ item.car.model }} - {{ item.car.year }}</p>
        </template>
      </v-data-table>
    </div>
  </div>
</template>
<script>
  import { mapState } from 'vuex';
  export default {
    data: () => ({
      headers: [
        { text: 'Transaction No.', value: 'transaction_number' },
        { text: 'Rentee', value: 'user.info.last_name' },
        { text: 'Car', value: 'car.brand.brand' },
        { text: 'Pick Up Date', value: 'rental_info.pickup_date' },
        { text: 'Return Date', value: 'rental_info.pickup_date' },
        { text: 'Transaction Date', value: 'created_at' },
        { text: 'Additional Instruction', value: 'rental_info.additional_instruction' },
        { text: 'Payment Type', value: 'rental_info.payment_type' },
        { text: 'Payment Status', value: 'rental_info.payment_status' },
        { text: 'Total Payment', value: 'total_payment' },
        { text: 'Invoice', value: 'invoice' },
      ],
      search: ''
    }),
    async mounted() {
      this.isLoading = true;
      await this.$store.dispatch('auth/checkUser');
      await this.$store.dispatch('dashboard/getData');
      this.isLoading = false;
    },
    methods: {},
    computed: {
      ...mapState('auth', ['user']),
      ...mapState('dashboard', ['cars', 'brands', 'branch', 'latest_transactions', 'users', 'inquiry', 'rentals']),
    },
  };
</script>
