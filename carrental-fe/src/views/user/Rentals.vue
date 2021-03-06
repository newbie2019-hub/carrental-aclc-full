<template>
  <div class="pl-lg-6 pl-md-3">
    <div>
      <p class="text-h5 font-weight-bold mt-4 custom-primary-color mb-0">Rentals Management</p>
      <p>You can manage all of the rentals below</p>
    </div>
    <v-row class="mt-5 mb-5">
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="blue darken-1" class="pa-3" rounded="lg" dark>
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-car-brake-retarder </v-icon>
              </div>
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Total <br />Rentals</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ getData(rentals) }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="red lighten-1" class="pa-3" rounded="lg" dark>
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-archive-clock-outline </v-icon>
              </div>
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Cancelled <br />Rentals</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ getData(archivedRentals) }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>
    <v-row class="mb-5">
      <v-col>
        <v-card-title>
          <v-btn
            color="green"
            @click="
              inputDialog = true;
              inputType = 'create';
            "
            dark
            >New Rental</v-btn
          >
          <v-spacer></v-spacer>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" outlined dense append-icon="mdi-magnify" class="mb-5" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="rentals" :search="search" :loading="isLoading" :loading-text="'Retrieving rentals data. Please wait ...'">
          <template v-slot:item.invoice="{ item }">
            <a v-if="item.rental_info.payment_type == 'On Branch'" class="text-decoration-none" :href="`http://127.0.0.1:8000${item.invoice}`" target="_">View Invoice</a>
            <a v-else class="text-decoration-none" :href="`${item.invoice}`" target="_">View Invoice</a>
          </template>
          <template v-slot:item.status="{ item }">
            <v-chip small dark :color="getChipColor(item.status)">{{ item.status }}</v-chip>
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
          <template v-slot:item.actions="{ item }">
            <v-layout>
              <v-btn
                v-if="item.status == 'On-going' && item.rental != 'Finished'"
                @click="
                  finishedDialog = true;
                  deleteData = item;
                "
                small
                text
                color="green darken-1"
                >Finished</v-btn>
              <v-btn
                v-if="item.rental_info.payment_status != 'Paid'"
                @click="
                  inputDialog = true;
                  inputType = 'update';
                  updateData = JSON.parse(JSON.stringify(item));
                "
                small
                text
                color="green darken-1"
                >Payment</v-btn
              >
              <v-btn
              v-if="item.status != 'Finished'"
                @click="
                  deleteData = item;
                  archiveDialog = true;
                "
                small
                text
                color="red darken-1"
                >Cancel</v-btn
              >
            </v-layout>
          </template>
        </v-data-table>
      </v-col>
    </v-row>

    <v-row class="mb-5">
      <v-col>
        <v-card-title>
          Cancelled Transactions
          <v-spacer></v-spacer>
          <v-spacer></v-spacer>
          <v-text-field v-model="searchArchived" outlined dense append-icon="mdi-magnify" class="mb-5" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table :headers="archivedHeaders" :items="archivedRentals" :search="searchArchived" :loading="isLoading" :loading-text="'Retrieving rentals data. Please wait ...'">
          <template v-slot:item.invoice="{ item }">
            <a class="text-decoration-none" :href="`http://127.0.0.1:8000${item.invoice}`" target="_">View Invoice</a>
          </template>
          <template v-slot:item.status="{ item }">
            <v-chip small dark color="grey">{{ item.status }}</v-chip>
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
      </v-col>
    </v-row>

    <v-dialog v-model="finishedDialog" max-width="420">
      <v-card>
        <v-card-title class="text-h5"> Confirm Action </v-card-title>
        <v-card-text class=""> Are you sure you want to mark this transaction as finished? <span class="red--text">Note: You cannot undo this action</span> </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="finishedDialog = false"> Cancel </v-btn>
          <v-btn color="green darken-1" text @click="finishedRental" :loading="isLoading"> Proceed </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="archiveDialog" max-width="420">
      <v-card>
        <v-card-title class="text-h5"> Confirm Action </v-card-title>
        <v-card-text class=""> Are you sure you want to cancel this transaction? </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <!-- <v-btn color="grey darken-2" text @click="archiveDialog = false"> Cancel </v-btn> -->
          <v-btn color="red darken-1" text @click="archiveRental" :loading="isLoading"> Cancel </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="inputDialog" max-width="420">
      <v-form ref="form" v-model="valid" @submit.prevent="createPayment" lazy-validation class="pa-0 ma-0">
        <v-card class="">
          <v-card-title class="text-h5"> Rental Payment </v-card-title>
          <v-card-subtitle>Please fill-in the input correctly</v-card-subtitle>
          <v-layout column class="mb-1 pr-4 pl-4">
            <v-text-field
              prepend-inner-icon="mdi-card-bulleted"
              outlined
              hide-details="auto"
              dense
              class="mt-2"
              v-model="updateData.amount_tendered"
              :rules="[checkAmount]"
              label="Amount Tendered"
              required
            ></v-text-field>
          </v-layout>
          <p class="mb-5 ml-4">Total Amount: ₱ {{ formatCurrency(updateData.rental_info.total_payment) }}</p>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="grey darken-2" text @click="inputDialog = false"> Cancel </v-btn>
            <v-btn color="green darken-1" text type="submit" :loading="isLoading"> Proceed </v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>

    <v-dialog v-model="deleteDialog" max-width="420">
      <v-card>
        <v-card-title class="text-h5"> Confirm Delete </v-card-title>
        <v-card-text class="">
          Are you sure you want to permanently delete this driver?
          <span class="red--text darken-2">Note: You cannot undo this action.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="deleteDialog = false"> Cancel </v-btn>
          <v-btn color="red darken-1" text @click="deleteRental" :loading="isLoading"> Delete </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="restoreDialog" max-width="420">
      <v-card>
        <v-card-title class="text-h5"> Restore Driver </v-card-title>
        <v-card-text class="">
          Are you sure you want to restore this driver?
          <span class="red--text darken-2">Note: This will also restore any related models for this driver.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="restoreDialog = false"> Cancel </v-btn>
          <v-btn color="green " text @click="restoreRental" :loading="isLoading"> Restore </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
  import API from '../../store/base/index';
  import { mapState } from 'vuex';
  export default {
    data: () => ({
      required: [(v) => !!v || 'Field is required'],
      search: '',
      searchArchived: '',
      valid: true,
      archiveDialog: false,
      viewInfoDialog: false,
      deleteDialog: false,
      restoreDialog: false,
      inputDialog: false,
      inputType: null,
      finishedDialog: false,
      updateData: {
        brand: '',
        logo: '',
        image: '',
        amount_tendered: '',
        rental_info: {
          total_payment: 0,
        },
      },
      deleteData: {
        id: null,
      },
      restoreData: {},
      isModalVisible: false,
      headers: [
        { text: 'Transaction No.', value: 'transaction_number' },
        { text: 'Rental Status', value: 'status' },
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
        { text: 'Actions', value: 'actions' },
      ],
      archivedHeaders: [
        { text: 'Transaction No.', value: 'transaction_number' },
        { text: 'Rental Status', value: 'status' },
        { text: 'Rentee', value: 'user.info.last_name' },
        { text: 'Car', value: 'car.brand.brand' },
        { text: 'Pick Up Date', value: 'rental_info.pickup_date' },
        { text: 'Return Date', value: 'rental_info.pickup_date' },
        { text: 'Transaction Date', value: 'created_at' },
        { text: 'Additional Instruction', value: 'rental_info.additional_instruction' },
        { text: 'Payment Type', value: 'rental_info.payment_type' },
        { text: 'Total Payment', value: 'total_payment' },
        { text: 'Payment Status', value: 'rental_info.payment_status' },
        { text: 'Invoice', value: 'invoice' },
        { text: 'Cancelled On', value: 'deleted_at' },
        // { text: 'Actions', value: 'actions' },
      ],
    }),
    async mounted() {
      this.isLoading = true;
      await this.$store.dispatch('auth/checkUser');
      await this.getRentals();
      await this.getArchivedRentals();
      this.isLoading = false;
    },
    methods: {
      getChipColor(data) {
        switch (data) {
          case 'Pending':
            return 'red';
          case 'Cancelled':
            return 'grey';
          case 'On-going':
            return 'green';
          default:
            return 'primary';
        }
      },
      showProfile(url) {
        this.$viewerApi({
          images: [`http://127.0.0.1:8000/images/${url}`],
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
      checkAmount() {
        if (parseFloat(this.updateData.amount_tendered) >= parseFloat(this.updateData.rental_info.total_payment)) {
          return true;
        } else {
          return 'Error! Amount tendered is less than the total payment.';
        }
      },
      async uploadImage(event) {
        if (event) {
          let formData = new FormData();
          formData.append('image', event);
          await API.post(`upload-image`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          })
            .then((response) => {
              this.updateData.profile_img = response.data;
            })
            .catch((error) => {
              console.log({ error });
            });
        }
      },
      showModal() {
        this.isModalVisible = true;
      },
      closeModal() {
        this.isModalVisible = false;
      },
      async getRentals() {
        await this.$store.dispatch('rentals/getRentals');
      },
      async getArchivedRentals() {
        await this.$store.dispatch('rentals/getArchivedRentals');
      },
      async finishedRental() {
        this.isLoading = true;
        const { status, data } = await this.$store.dispatch('rentals/finishedRental', this.deleteData);
        this.toastData(status, data);
        this.finishedDialog = false;
        this.deleteData = null;
        this.isLoading = false;
      },
      async archiveRental() {
        this.isLoading = true;
        const { status, data } = await this.$store.dispatch('rentals/archiveRental', this.deleteData);
        this.toastData(status, data);
        this.archiveDialog = false;
        this.deleteData = null;
        this.isLoading = false;
      },
      async restoreRental() {
        this.isLoading = true;
        const { status, data } = await this.$store.dispatch('rentals/restoreDriver', this.restoreData);
        this.toastData(status, data);
        this.restoreDialog = false;
        this.restoreData = null;
        this.isLoading = false;
      },
      async createPayment() {
        const valid = this.$refs.form.validate();
        if (valid) {
          this.isLoading = true;
          const { status, data } = await this.$store.dispatch('rentals/createPayment', this.updateData);
          this.toastData(status, data);
          this.inputDialog = false;
          this.isLoading = false;
          this.getRentals();
        }
      },
      async deleteRental() {
        this.isLoading = true;
        const { status, data } = await this.$store.dispatch('rentals/deleteRental', this.deleteData);
        this.toastData(status, data);
        this.deleteDialog = false;
        this.deleteData = null;
        this.isLoading = false;
      },
      getData(data) {
        return data.length < 10 ? '0' + data.length : data.length;
      },
    },
    computed: {
      ...mapState('rentals', ['rentals', 'archivedRentals']),
    },
    watch: {
      inputDialog() {
        if (this.inputType == 'create') {
          this.valid = true;
          this.updateData = { model: '', description: '', user_id: '', remarks: '' };
          // this.$refs.form.resetValidation();
        }
      },
    },
  };
</script>
