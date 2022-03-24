<template>
  <div class="mt-10 mb-6">
    <v-row justify="end" class="mb-5 mt-5">
      <v-col cols="11" lg="4" md="5">
        <v-select :items="branch" item-value="id" item-text="branch" outlined v-model="branch_id" dense label="Filter Branch" hide-details="auto"></v-select>
      </v-col>
    </v-row>
    <v-layout v-if="filterCars.length == 0" justify-center>
      <p>No cars available for this branch</p>
    </v-layout>
    <carousel v-else :scrollPerPage="false" :perPage="3" :navigationEnabled="true" class="" :paginationEnabled="false">
      <slide class="v-card v-sheet theme--light align-items-end elevation-2 rounded-xl mr-2 pa-2 cursor-pointer" v-for="(car, i) in filterCars" :key="i" style="min-width: 380px">
        <v-card-text>
          <v-layout>
            <p
              @click.stop="
                carData = car;
                dialog = true;
              "
              class="text-h5 mb-0 font-weight-bold custom-primary-color"
            >
              {{ car.brand.brand }} {{ car.model }}
            </p>
            <v-btn
              @click.stop="
                carData = car;
                dialog = true;
              "
              icon
              color="primary darken-3"
              ><v-icon>mdi-information-outline</v-icon></v-btn
            >
          </v-layout>
          <p class="mb-0">
            Owned by <span class="font-weight-bold">{{ car.user.info.last_name }}, {{ car.user.info.first_name }}</span>
          </p>
          <p>
            Seats: <span class="font-weight-bold">{{ car.seats }}</span>
          </p>
          <p class="text-subtitle font-weight-bold mb-0">
            <v-icon color="mr-3"> mdi-credit-card-outline </v-icon>
            ₱ {{ car.rate.per_day }}/day
          </p>
          <v-img
            v-if="car.image"
            contain
            class="mb-7"
            :lazy-src="`http://127.0.0.1:8000/images/cars/${car.image}`"
            max-height="220"
            max-width="330"
            :src="`http://127.0.0.1:8000/images/cars/${car.image}`"
          ></v-img>
          <v-img v-else contain class="mb-7" :lazy-src="`https://via.placeholder.com/250x140`" max-height="220" max-width="330" :src="`https://via.placeholder.com/250x140`"></v-img>
          <div class="car-info-bottom">
            <p class="mb-0 font-weight-bold mr-5 text-no-wrap text-capitalize"><v-icon color="" class="mr-2">mdi-car-shift-pattern</v-icon>{{ car.transmission }}</p>
            <p class="mb-0 font-weight-bold ellipse"><v-icon color="" class="mr-1">mdi-map-marker</v-icon>{{ car.branch.branch }}</p>
          </div>
        </v-card-text>
      </slide>
    </carousel>

    <v-dialog v-model="dialog" max-width="460">
      <v-card class="pt-2 pl-4 pr-4 pb-2">
        <v-layout align-center justify-center>
          <v-card-text>
            <div></div>
            <p class="text-h5 mb-0 font-weight-bold custom-primary-color">{{ carData.brand.brand }} {{ carData.model }}</p>
            <p class="mb-0">
              Owned by <span class="font-weight-bold">{{ carData.user.info.last_name }}, {{ carData.user.info.first_name }}</span>
            </p>
            <p>
              Fuel Type: <span class="font-weight-bold text-capitalize">{{ carData.fuel_type }}</span
              >&nbsp; Year: <span class="font-weight-bold text-capitalize">{{ carData.year }}</span>
            </p>
            <v-img
              v-if="carData.image"
              contain
              class="mb-7"
              :lazy-src="`http://127.0.0.1:8000/images/cars/${carData.image}`"
              max-height="240"
              max-width="350"
              :src="`http://127.0.0.1:8000/images/cars/${carData.image}`"
            ></v-img>
            <v-img v-else contain class="mb-7" :lazy-src="`https://via.placeholder.com/350x240`" max-height="240" max-width="350" :src="`https://via.placeholder.com/250x140`"></v-img>
            <div class="d-flex">
              <p class="mb-0 font-weight-bold mr-5 text-no-wrap text-capitalize"><v-icon color="" class="mr-2">mdi-car-shift-pattern</v-icon>{{ carData.transmission }}</p>
              <p class="mb-0 font-weight-bold"><v-icon color="" class="mr-1">mdi-map-marker</v-icon>{{ carData.branch.branch }}</p>
            </div>
          </v-card-text>
        </v-layout>
        <v-layout class="pl-3 pr-4">
          <v-col>
            <p><span class="font-weight-bold">Per Day:</span> ₱{{ formatCurrency(carData.rate.per_day) }}</p>
          </v-col>
          <v-col>
            <p><span class="font-weight-bold">Per Week:</span> ₱{{ formatCurrency(carData.rate.per_week) }}</p>
          </v-col>
          <v-col>
            <p><span class="font-weight-bold">Per Month:</span> ₱{{ formatCurrency(carData.rate.per_month) }}</p>
          </v-col>
        </v-layout>
        <v-layout class="pl-3 pr-4 justify-space-between">
          <v-col>
            <p>
              <span class="font-weight-bold">Driver's Fee:</span><br />
              ₱{{ formatCurrency(carData.rate.with_driver) }}
            </p>
          </v-col>
          <v-col>
            <p>
              <span class="font-weight-bold">Mileage:</span><br />
              {{ carData.mileage }}
            </p>
          </v-col>
          <v-col>
            <p><span class="font-weight-bold">Total Seats:</span> <br />{{ carData.seats }}</p>
          </v-col>
        </v-layout>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-1" text @click="dialog = false"> Close </v-btn>
          <v-btn color="green darken-1" text @click="showRentCar(carData)"> Rent </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <rental-form v-show="isFormVisible" @close="closeModal" :showForm="isFormVisible" :car="carSelected" />
  </div>
</template>
<script>
  import RentalForm from '../../components/Home/RentalForm.vue';
  import { Carousel, Slide } from 'vue-carousel';
  import { mapState } from 'vuex';
  export default {
    components: {
      Carousel,
      Slide,
      RentalForm,
    },
    data: () => ({
      branch_id: null,
      location_id: null,
      dialog: false,
      isFormVisible: false,
      carSelected: null,
      carData: {
        model: '',
        brand: {
          brand: '',
        },
        branch: {
          branch: '',
          name: '',
        },
        rate: {
          per_day: '',
          per_week: '',
          per_month: '',
        },
        user: {
          info: {
            first_name: '',
            last_name: '',
            gender: '',
          },
        },
      },
    }),
    async mounted() {
      await this.$store.dispatch('home/getData');
      this.branch.push({ branch: 'Show All', id: 0 });
    },
    methods: {
      showModal() {
        this.isFormVisible = true;
      },
      closeModal() {
        this.isFormVisible = false;
      },
      showRentCar(data) {
        if(this.user.info.first_name != ''){
          this.carSelected = data;
          this.isFormVisible = true;
          this.dialog = false;
          window.history.replaceState(null, null, "#rental-form");
          this.toastData(200, {msg: 'Complete your rental information below'})
        }
        else {
          this.$router.push('/login')
          this.toastData(422, {msg: 'Please login or create an account first!'})
        }
      },

    },
    computed: {
      ...mapState('home', ['cars', 'branch']),
      ...mapState('auth', ['user']),
      filterCars() {
        if (this.branch_id == null) return this.cars;
        if (this.branch_id == 0) return this.cars;

        return this.cars.filter((car) => car.branch_id == this.branch_id);
      },
    },
  };
</script>
<style>
  .VueCarousel-wrapper {
    padding: 2rem 5rem 1rem 1rem;
  }

  .car-info-bottom {
    position: absolute;
    bottom: 0;
    display: flex;
    margin-top: 10px;
    margin-bottom: 16px;
  }

  .list-enter-active,
  .list-leave-active {
    transition: all 0.5s ease;
  }
  .list-enter-from,
  .list-leave-to {
    opacity: 0;
    transform: translateX(30px);
  }
</style>
