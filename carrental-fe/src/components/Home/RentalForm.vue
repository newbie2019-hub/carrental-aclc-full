<template>
  <div class="mt-15">
    <v-layout column class="mb-5">
      <p class="text-center sub-heading mb-0">Rental</p>
      <p class="text-center">Fill-in all the fields to continue with your rental</p>
    </v-layout>
    <v-layout class="mb-5" justify-end>
      <v-btn color="green mr-2" dark @click="close">Cancel Rent</v-btn>
      <v-btn @click.stop="dialogCarPolicy = true" color="red" dark>Rental Policy</v-btn>
    </v-layout>
    <v-row v-if="car != null" justify="center" align="start">
      <v-col cols="12" sm="11" md="6" lg="4">
        <v-card class="pt-2 pl-4 pr-4 pb-2" max-width="400">
          <v-layout align-center justify-center>
            <v-card-text>
              <div></div>
              <p class="text-h5 mb-0 font-weight-bold custom-primary-color">{{ car.brand.brand }} {{ car.model }}</p>
              <p class="mb-0">
                Owned by <span class="font-weight-bold">{{ car.user.info.last_name }}, {{ car.user.info.first_name }}</span>
              </p>
              <p>
                Fuel Type: <span class="font-weight-bold text-capitalize">{{ car.fuel_type }}</span
                >&nbsp; Year: <span class="font-weight-bold text-capitalize">{{ car.year }}</span>
              </p>
              <v-img
                v-if="car.image"
                contain
                class="mb-7"
                :lazy-src="`http://127.0.0.1:8000/images/cars/${car.image}`"
                max-height="240"
                max-width="350"
                :src="`http://127.0.0.1:8000/images/cars/${car.image}`"
              ></v-img>
              <v-img v-else contain class="mb-7" :lazy-src="`https://via.placeholder.com/350x240`" max-height="240" max-width="350" :src="`https://via.placeholder.com/250x140`"></v-img>
              <div class="d-flex">
                <p class="mb-0 font-weight-bold mr-5 text-no-wrap text-capitalize"><v-icon color="" class="mr-2">mdi-car-shift-pattern</v-icon>{{ car.transmission }}</p>
                <p class="mb-0 font-weight-bold"><v-icon color="" class="mr-1">mdi-map-marker</v-icon>{{ car.branch.branch }}</p>
              </div>
            </v-card-text>
          </v-layout>
          <v-layout class="pl-3 pr-4">
            <v-col>
              <p><span class="font-weight-bold">Per Day:</span> ₱{{ formatCurrency(car.rate.per_day) }}</p>
            </v-col>
            <v-col>
              <p><span class="font-weight-bold">Per Week:</span> ₱{{ formatCurrency(car.rate.per_week) }}</p>
            </v-col>
            <v-col>
              <p><span class="font-weight-bold">Per Month:</span> ₱{{ formatCurrency(car.rate.per_month) }}</p>
            </v-col>
          </v-layout>
          <v-layout class="pl-3 pr-4 justify-space-between">
            <v-col>
              <p>
                <span class="font-weight-bold">Driver's Fee:</span><br />
                ₱{{ formatCurrency(car.rate.with_driver) }}
              </p>
            </v-col>
            <v-col>
              <p>
                <span class="font-weight-bold">Mileage:</span><br />
                {{ car.mileage }}
              </p>
            </v-col>
            <v-col>
              <p><span class="font-weight-bold">Total Seats:</span> <br />{{ car.seats }}</p>
            </v-col>
          </v-layout>
        </v-card>
      </v-col>
      <v-col cols="12" sm="11" md="6" lg="6">
        <v-form ref="form" lazy-validation v-model="valid">
          <v-card class="pt-6 pl-6 pr-6 pb-6">
            <p class="text-h5 mb-0 font-weight-bold custom-primary-color">Rental Form</p>
            <p class="">Please fill-in all the fields to your choice.</p>
            <v-row dense>
              <v-col>
                <v-menu ref="menu" v-model="menu" :close-on-content-click="false" transition="scale-transition" offset-y min-width="auto">
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="data.pickup_date"
                      label="Pick Up Date"
                      append-icon="mdi-calendar"
                      hide-details="auto"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                      :loading="isLoading"
                      :rules="required"
                      dense
                      outlined
                    ></v-text-field>
                  </template>
                  <v-date-picker v-model="data.pickup_date" :active-picker.sync="activePicker" :min="minDate" @change="save"></v-date-picker>
                </v-menu>
              </v-col>
              <v-col>
                <v-menu ref="menu" v-model="menu2" :close-on-content-click="false" transition="scale-transition" offset-y min-width="auto">
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="data.return_date"
                      label="Return Date"
                      class=""
                      append-icon="mdi-calendar"
                      hide-details="auto"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                      :loading="isLoading"
                      :rules="required"
                      dense
                      outlined
                    ></v-text-field>
                  </template>
                  <v-date-picker v-model="data.return_date" :active-picker.sync="activePicker" :min="maxDate" @change="save"></v-date-picker>
                </v-menu>
              </v-col>
            </v-row>
            <v-textarea rows="3" outlined hide-details="auto" dense class="mt-2" v-model="data.additional_instruction" label="Additional Instructions" required></v-textarea>
            <v-row class="">
              <v-checkbox hide-details="auto" v-model="data.with_driver" class="ml-2">
                <template v-slot:label>
                  <div>With Driver</div>
                </template>
              </v-checkbox>
            </v-row>
            <v-row v-if="data.with_driver">
              <!--- GET TOTAL DAYS AND ADD INPUT BUTTON MAX IS BASED ON THE MAX RENTAL DAYS  --->
              <v-text-field
                type="number"
                outlined
                hide-details="auto"
                dense
                class="mt-3 ml-3 mr-3"
                v-model="data.days_with_driver"
                :rules="[maxDayswithDriver]"
                label="No. of Days with Driver"
                required
              ></v-text-field>
            </v-row>
            <v-layout class="mt-3" justify-end>
              <v-btn class="mt-3" @click="rentalCarDialog = true" depressed color="grey" dark>Rental Info</v-btn>
            </v-layout>
            <v-layout align-end justify-end class="mt-4">
              <p class="mb-2 font-grotesk font-weight-bold">Payment Method</p>
            </v-layout>
            <v-layout align-end class="">
              <v-spacer />
              <v-btn @click.prevent="createRent('On Branch')" class="mr-1" color="green" dark depressed>
                On Branch
                <v-icon small class="ml-1">mdi-receipt</v-icon>
              </v-btn>
              <v-btn @click.prevent="createRent('Credit Card')" class="" color="primary" depressed>
                Credit Card
                <v-icon small class="ml-1">mdi-credit-card-outline</v-icon>
              </v-btn>
            </v-layout>
          </v-card>
        </v-form>
      </v-col>
    </v-row>
    <v-dialog v-model="dialogCarPolicy" max-width="460">
      <v-card class="pt-6 pl-6 pr-5 pb-4">
        <h3 class="fw-bold">Car Use Policy</h3>
        <p class="mt-4 mb-0">The vehicle should be used only within Region VIII and shall not be used for any illegal purposes.</p>
        <p class="mt-2">The vehicle will have a full tank and should be returned in full tank as well.</p>
        <p class="mb-2">Valid Government Issued ID (Philippine Resident), Passport (Foreign Resident), National ID.</p>
        <p class="mb-0">
          <span class="font-weight-bold">PHP 5,000</span> Security Deposit. You will provide this when you get the car. This will be given back to you in full upon returning the car and there are no
          damages.
        </p>
        <h3 class="mt-5 fw-bold">Additional for Self-Drive</h3>
        <p class="mt-4 mb-0">Driver's License</p>
        <v-card-actions class="mt-2">
          <v-spacer></v-spacer>
          <v-btn @click="dialogCarPolicy = false" color="green" dark depressed>Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="rentalCarDialog" max-width="460">
      <v-card class="pt-6 pl-6 pr-5 pb-4">
        <p class="text-h5 font-grotesk font-weight-bold mb-5">Rental Information</p>
        <p class="mb-0"><span class="font-weight-bold">Rental Days:</span> {{ totalDays }}d</p>
        <p class="mb-0"><span class="font-weight-bold">Rental Fee:</span> ₱ {{ formatCurrency(total - driversFee) }}</p>
        <p class="mb-0"><span class="font-weight-bold">With Driver:</span> {{ data.with_driver ? 'Yes' : 'No' }}</p>
        <p class="mb-0"><span class="font-weight-bold">Drivers Fee:</span> ₱ {{ formatCurrency(driversFee) }}</p>
        <p><span class="font-weight-bold">Total Payment:</span> ₱ {{ formatCurrency(total) }} + ₱ {{ formatCurrency(5000) }} Security Deposit</p>
        <v-card-actions class="mt-2">
          <v-spacer></v-spacer>
          <v-btn @click="rentalCarDialog = false" color="green" dark depressed>Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
  import moment from 'moment';
  import { mapState } from 'vuex';
  export default {
    props: {
      car: Object,
      showForm: Boolean,
    },
    data: () => ({
      rentalCarDialog: false,
      dialogCarPolicy: false,
      menu: false,
      menu2: false,
      date: '',
      activePicker: null,
      minDate: '',
      maxDate: '',
      required: [(v) => !!v || 'Field is required'],
      data: {
        pickup_date: '',
        return_date: '',
        additional_instruction: '',
        with_driver: false,
        days_with_driver: 1,
        name: '',
        cvc: '',
        number: '',
        drivers_fee: 0,
        exp_month: '',
        exp_year: '',
        payment_type: '',
      },
      total: 0,
      totalDays: 0,
      driversFee: 0,
      valid: true,
    }),
    mounted() {
      this.setMinDate();
    },
    methods: {
      close() {
        this.$emit('close');
      },
      setMaxDate(data) {
        const date = new Date(data);
        date.get;
        const newDate = date.getFullYear().toString() + '-' + '0' + (date.getMonth() + 1).toString() + '-' + (date.getDate() + 1).toString();
        this.maxDate = newDate;
      },
      setMinDate() {
        const date = new Date();
        date.get;
        const newDate = date.getFullYear().toString() + '-' + '0' + (date.getMonth() + 1).toString() + '-' + date.getDate().toString();
        this.minDate = newDate;
      },
      save(date) {
        this.$refs.menu.save(date);
        this.setMaxDate(date);
      },
      maxDayswithDriver: function () {
        if (this.data.with_driver && (this.data.days_with_driver <= this.totalDays) && (this.data.pickup_date != '' || this.data.return_date != '')) {
          return true;
        } else  {
          return 'Incomplete or Invalid input';
        }
      },
      async createRent(payment) {
        const valid = this.$refs.form.validate();
        if (valid) {
          this.isLoading = true;
          const carDataRent = {
            pickup_date: this.data.pickup_date,
            return_date: this.data.return_date,
            additional_instruction: this.data.additional_instruction,
            with_driver: this.data.with_driver,
            days_with_driver: this.data.days_with_driver,
            driver_payment: this.driversFee,
            name: this.data.name,
            cvc: this.data.cvc,
            number: this.data.number,
            exp_month: this.data.exp_month,
            exp_year: this.data.exp_year,
            payment_type: payment,
            total: this.total,
            car_id: this.car.id,
          };

          const { status, data } = await this.$store.dispatch('rentals/create', carDataRent);
          this.toastData(status, data);
          this.total = 0;
          this.driversFee = 0;
          this.isLoading = false;
          this.close();
        }
      },
      dateDifference() {
        var start = moment(this.data.pickup_date, 'YYYY-MM-DD');
        var end = moment(this.data.return_date, 'YYYY-MM-DD');
        //GET DAYS
        const days = moment.duration(end.diff(start)).asDays();
        // console.log(`Start: ${start} | End: ${end} | Days: ${days}`);
        this.totalDays = days;
        if (this.data.pickup_date != '' && this.data.return_date != '') {
          if (days >= 30) {
            this.total = parseFloat(this.total) + parseFloat(this.car.rate.per_month * (days / 30).toFixed(0));
            console.log(`Initial total for a month: ${this.total}\nDays remaining: ${days % 30}`);
            if (days % 30 >= 7) {
              this.total = parseFloat(this.total) + parseFloat(this.car.rate.per_week * ((days % 30) / 7).toFixed(0));
              console.log(`Initial total for a week: ${this.total}`);
              this.total = parseFloat(this.total) + parseFloat(((days % 30) % 7) * this.car.rate.per_day);
              console.log(`Total for the remaining days: ${this.total}`);
            } else {
              this.total = parseFloat(this.total) + (days % 30) * this.car.rate.per_day;
            }
          } else if (days >= 7 && days <= 30) {
            this.total = this.car.rate.per_week * (days / 7).toFixed(0);
            this.total = parseFloat(this.total) + (days % 7) * this.car.rate.per_day;
          } else {
            this.total = parseFloat(this.car.rate.per_day * days);
          }
        }

        if (this.data.with_driver) {
          this.driversFee = parseFloat(this.car.rate.with_driver * this.data.days_with_driver);
          this.total = parseFloat(this.total) + parseFloat(this.car.rate.with_driver * this.data.days_with_driver);
        }
      },
    },
    computed: {
      ...mapState('auth', ['user']),
      rentalForm: {
        get() {
          return this.showForm;
        },
        set(value) {
          if (!value) {
            this.$emit('close');
          }
        },
      },
    },
    watch: {
      data: {
        handler() {
          if (this.data.pickup_date && this.data.return_date) {
            this.dateDifference();
          }
        },
        deep: true,
      },
    },
  };
</script>
