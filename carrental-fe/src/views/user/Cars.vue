<template>
  <div class="pl-lg-6 pl-md-3">
    <div>
      <p class="text-h5 font-weight-bold mt-4 custom-primary-color mb-0">Cars Management</p>
      <p>You can manage all of the cars below</p>
    </div>
    <v-row class="mt-5 mb-5">
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="blue darken-1" class="pa-3" rounded="lg" dark>
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-car-brake-retarder </v-icon>
              </div>
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Total <br />Cars</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ getData(cars) }}</p>
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
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Archived <br />Cars</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ getData(archiveCars) }}</p>
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
            >New Car</v-btn
          >
          <v-spacer></v-spacer>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" outlined dense append-icon="mdi-magnify" class="mb-5" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="cars" :search="search" :loading="isLoading" :loading-text="'Retrieving cars data. Please wait ...'">
          <template v-slot:item.image="{ item }">
            <v-avatar class="ma-0" size="40" tile color="">
              <v-img class="cursor-pointer" @click.prevent="showProfile(item.image)" v-if="item.image" :src="`http://127.0.0.1:8000/images/cars/${item.image}`" />
            </v-avatar>
          </template>
          <template v-slot:item.for_rent_status="{ item }">
            <v-chip small dark :color="item.for_rent_status == 'Approved' ? 'green' : 'red'">{{ item.for_rent_status }}</v-chip>
          </template>
          <template v-slot:item.rate="{ item }">
            <v-btn
              text
              small
              color="primary"
              @click.prevent="
                carRates = item;
                rateDialog = true;
              "
              >View</v-btn
            >
          </template>
          <template v-slot:item.actions="{ item }">
            <v-layout>
              <v-btn
                @click="
                  viewInfoDialog = true;
                  carData = JSON.parse(JSON.stringify(item));
                "
                small
                text
                color="primary darken-1"
                >View</v-btn
              >
              <v-btn
                @click="
                  inputDialog = true;
                  inputType = 'update';
                  updateData = JSON.parse(JSON.stringify(item));
                "
                small
                text
                color="green darken-1"
                >Update</v-btn
              >
              <v-btn
                @click="
                  deleteData = item;
                  archiveDialog = true;
                "
                small
                text
                color="red darken-1"
                >Archive</v-btn
              >
            </v-layout>
          </template>
        </v-data-table>
      </v-col>
    </v-row>

    <v-row class="mb-5">
      <v-col>
        <v-card-title>
          Archived Cars
          <v-spacer></v-spacer>
          <v-spacer></v-spacer>
          <v-text-field v-model="searchArchived" outlined dense append-icon="mdi-magnify" class="mb-5" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table :headers="archivedHeaders" :items="archivedCars" :search="searchArchived" :loading="isLoading" :loading-text="'Retrieving cars data. Please wait ...'">
          <template v-slot:item.image="{ item }">
            <v-avatar class="ma-0" size="40" tile color="primary">
              <img class="cursor-pointer" @click.prevent="showProfile(item.image)" v-if="item.image" :src="`http://127.0.0.1:8000/images/cars/${item.image}`" />
            </v-avatar>
          </template>
          <template v-slot:item.rate="{ item }">
            <v-btn
              text
              small
              color="primary"
              @click.prevent="
                carRates = item;
                rateDialog = true;
              "
              >View</v-btn
            >
          </template>
          <template v-slot:item.for_rent_status="{ item }">
            <v-chip small dark :color="item.for_rent_status == 'Approved' ? 'green' : 'red'">{{ item.for_rent_status }}</v-chip>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-layout>
              <v-btn
                @click="
                  restoreDialog = true;
                  restoreData = item;
                "
                small
                text
                color="grey darken-1"
                >Restore</v-btn
              >
              <v-btn
                @click.prevent="
                  deleteData = item;
                  deleteDialog = true;
                "
                small
                text
                color="red"
                >Delete Forever</v-btn
              >
            </v-layout>
          </template>
        </v-data-table>
      </v-col>
    </v-row>

    <v-dialog v-model="archiveDialog" max-width="420">
      <v-card>
        <v-card-title class="text-h5"> Confirm Action </v-card-title>
        <v-card-text class="">
          Are you sure you want to archive this car?
          <span class="red--text darken-2">Note: This car will be marked as archived to preserve any related transactions.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="archiveDialog = false"> Cancel </v-btn>
          <v-btn color="red darken-1" text @click="archiveCars" :loading="isLoading"> Archive </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="inputDialog" max-width="720">
      <v-form ref="form" v-model="valid" @submit.prevent="saveCar" lazy-validation class="pa-0 ma-0">
        <v-card class="pa-2">
          <v-card-title class="text-h5"> {{ inputType == 'update' ? 'Update' : 'New' }} Car </v-card-title>
          <v-card-subtitle>All fields are required. <span v-if="inputType == 'update'">Image will be updated upon saving the changes</span></v-card-subtitle>
          <v-container class="mb-3">
            <v-layout justify-center class="mb-5">
              <v-img
                width="150"
                height="150"
                contain
                class="cursor-pointer"
                @click.prevent="showProfile(updateData.image)"
                v-if="updateData.image"
                :src="`http://127.0.0.1:8000/images/cars/${updateData.image}`"
              />
              <p class="mt-2 mb-2 grey lighten-2 pa-10" v-else>{{ inputType == 'create' ? 'Upload Image' : 'No Image Found' }}</p>
            </v-layout>
            <v-layout column class="mb-2">
              <v-file-input
                @change="uploadImage"
                v-model="updateData.car_img"
                prepend-icon=""
                label="Car Image"
                class=""
                accept="image/*"
                prepend-inner-icon="mdi-paperclip"
                outlined
                dense
                hide-details="auto"
                show-size
                truncate-length="15"
              >
              </v-file-input>
            </v-layout>
            <v-row dense>
              <v-col cols="12" sm="6" md="6" lg="6">
                <v-select
                  :items="branch"
                  item-value="id"
                  item-text="branch"
                  outlined
                  hide-details="auto"
                  dense
                  class="mt-2"
                  v-model="updateData.branch_id"
                  :rules="required"
                  label="Branch"
                  required
                ></v-select>
              </v-col>
              <v-col cols="12" sm="6" md="6" lg="6">
                <v-select
                  :items="brands"
                  item-value="id"
                  item-text="brand"
                  outlined
                  hide-details="auto"
                  dense
                  class="mt-2"
                  v-model="updateData.car_brand_id"
                  :rules="required"
                  label="Brand"
                  required
                ></v-select>
              </v-col>
            </v-row>
            <v-row dense>
              <v-col cols="12" sm="6" md="6" lg="6">
                <v-text-field outlined hide-details="auto" dense class="mt-2" v-model="updateData.model" :rules="required" label="Model" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6" lg="6">
                <v-select :items="transmissions" outlined hide-details="auto" dense class="mt-2" v-model="updateData.transmission" :rules="required" label="Transmission" required></v-select>
              </v-col>
            </v-row>
            <v-row dense>
              <v-col cols="12" sm="6" md="6" lg="6">
                <v-text-field outlined hide-details="auto" dense class="mt-2" v-model="updateData.plate_number" :rules="required" label="Plate Number" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6" lg="6">
                <v-text-field outlined hide-details="auto" dense class="mt-2" v-model="updateData.vehicle_identification_number" :rules="required" label="VIN" required></v-text-field>
              </v-col>
            </v-row>
            <v-row dense>
              <v-col cols="12" sm="6" md="6" lg="3">
                <v-text-field type="number" outlined hide-details="auto" dense class="mt-2" v-model="updateData.seats" :rules="required" label="Seats" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6" lg="3">
                <v-text-field type="number" outlined hide-details="auto" dense class="mt-2" v-model="updateData.mileage" :rules="required" label="Mileage" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6" lg="3">
                <v-text-field type="number" outlined hide-details="auto" dense class="mt-2" v-model="updateData.year" :rules="required" label="Year" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6" lg="3">
                <v-text-field outlined hide-details="auto" dense class="mt-2" v-model="updateData.fuel_type" :rules="required" label="Fuel Type" required></v-text-field>
              </v-col>
            </v-row>
            <v-row dense>
              <v-col cols="12" sm="6" md="6" lg="6">
                <v-textarea auto-grow rows="2" outlined hide-details="auto" dense class="mt-2" v-model="updateData.description" :rules="required" label="Description" required></v-textarea>
              </v-col>
              <v-col cols="12" sm="6" md="6" lg="6">
                <v-textarea auto-grow rows="1" outlined hide-details="auto" dense class="mt-2" v-model="updateData.remarks" :rules="required" label="Remarks" required></v-textarea>
              </v-col>
            </v-row>
            <v-divider class="mt-4 mb-4"/>
            <p class="mb-1 font-weight-bold">Rental Rates</p>
            <v-row dense>
              <v-col cols="12" sm="6" md="6" lg="3">
                <v-text-field type="number" outlined hide-details="auto" dense class="mt-2" v-model="updateData.rate.per_day" :rules="required" label="Per Day" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6" lg="3">
                <v-text-field type="number" outlined hide-details="auto" dense class="mt-2" v-model="updateData.rate.per_week" :rules="required" label="Per Week" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6" lg="3">
                <v-text-field type="number" outlined hide-details="auto" dense class="mt-2" v-model="updateData.rate.per_month" :rules="required" label="Per Month" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6" lg="3">
                <v-text-field type="number" outlined hide-details="auto" dense class="mt-2" v-model="updateData.rate.with_driver" :rules="required" label="With Driver/d" required></v-text-field>
              </v-col>
            </v-row>
          </v-container>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="grey darken-2" text @click="inputDialog = false"> Cancel </v-btn>
            <v-btn v-if="(user.info.role.role == 'Admin' || user.info.role.role == 'Branch Admin') && updateData.for_rent_status == 'Pending'" color="primary" text @click="inputDialog = false"> Approve </v-btn>
            <v-btn color="green darken-1" text type="submit" :loading="isLoading"> {{ inputType == 'update' ? 'Update' : 'Create' }} </v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>

    <v-dialog v-model="deleteDialog" max-width="420">
      <v-card>
        <v-card-title class="text-h5"> Confirm Delete </v-card-title>
        <v-card-text class="">
          Are you sure you want to permanently delete this car?
          <span class="red--text darken-2">Note: You cannot undo this action.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="deleteDialog = false"> Cancel </v-btn>
          <v-btn color="red darken-1" text @click="deleteCar" :loading="isLoading"> Delete </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="rateDialog" max-width="420">
      <v-card>
        <v-card-title class="text-h5"> Car Info </v-card-title>
        <v-card-text class="">
          <v-layout column>
            <p class="black--text font-weight-bold mb-0">Brand: {{ carRates.brand ? carRates.brand.brand : 'No Data' }}</p>
            <p class="black--text font-weight-bold">Model: {{ carRates.model }}</p>
            <p class="mb-0 black--text">Per Day: ₱ {{ formatCurrency(carRates.rate.per_day) }}</p>
            <p class="mb-0 black--text">Per Week: ₱ {{ formatCurrency(carRates.rate.per_week) }}</p>
            <p class="mb-0 black--text">Per Month: ₱ {{ formatCurrency(carRates.rate.per_month) }}</p>
            <p class="black--text">With Driver: + ₱ {{ formatCurrency(carRates.rate.with_driver) }}/d</p>
          </v-layout>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="rateDialog = false"> Cancel </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="restoreDialog" max-width="420">
      <v-card>
        <v-card-title class="text-h5"> Restore Car </v-card-title>
        <v-card-text class="">
          Are you sure you want to restore this car?
          <span class="red--text darken-2">Note: This will also restore any related transactions from this car.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="restoreDialog = false"> Cancel </v-btn>
          <v-btn color="green " text @click="restoreCar" :loading="isLoading"> Restore </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
  import moment from 'moment';
  import { mapState } from 'vuex';
  import API from '../../store/base/index';
  export default {
    data: () => ({
      required: [(v) => !!v || 'Field is required'],
      search: '',
      searchArchived: '',
      valid: true,
      rateDialog: false,
      archiveDialog: false,
      viewInfoDialog: false,
      deleteDialog: false,
      restoreDialog: false,
      inputDialog: false,
      inputType: null,
      transmissions: [
        {
          text: 'Automatic',
          value: 'automatic',
        },
        {
          text: 'Manual',
          value: 'manual',
        },
      ],
      carRates: {
        brand: {
          brand: '',
        },
        rate: {
          per_day: '',
          per_week: '',
          per_month: '',
          with_driver: '',
        },
      },
      updateData: {
        model: '',
        description: '',
        remarks: '',
        year: '',
        fuelType: '',
        mileage: '',
        branch_id: '',
        car_brand_id: '',
        seats: '',
        transmission: '',
        plate_number: '',
        vehicle_identification_number: '',
        image: '',
        car_img: '',
        rate: {
          per_day: '',
          per_week: '',
          per_month: '',
          with_driver: '',
        },
      },
      deleteData: {
        id: null,
      },
      carData: {},
      restoreData: {},
      isModalVisible: false,
      headers: [
        { text: 'Image', value: 'image', sortable: false },
        {
          text: 'Brand',
          align: 'start',
          sortable: false,
          value: 'brand.brand',
        },
        { text: 'Model', value: 'model' },
        { text: 'Seats', value: 'seats' },
        // { text: 'Qty', value: 'quantity' },
        { text: 'Plate Number', value: 'plate_number' },
        { text: 'Year', value: 'year' },
        { text: 'Rate', value: 'rate', align: 'center' },
        { text: 'Transmission', value: 'transmission' },
        { text: 'Status', value: 'for_rent_status' },
        { text: 'Actions', value: 'actions' },
      ],
      archivedHeaders: [
        { text: 'Image', value: 'image', sortable: false },
        {
          text: 'Brand',
          align: 'start',
          sortable: false,
          value: 'brand.brand',
        },
        { text: 'Model', value: 'model' },
        { text: 'Seats', value: 'seats' },
        { text: 'Plate Number', value: 'plate_number' },
        { text: 'Year', value: 'year' },
        // { text: 'Qty', value: 'quantity' },
        { text: 'Rate', value: 'rate', align: 'center' },
        { text: 'Transmission', value: 'transmission' },
        { text: 'Status', value: 'for_rent_status' },
        { text: 'Deleted At', value: 'deleted_at' },
        { text: 'Actions', value: 'actions' },
      ],
    }),
    async mounted() {
      this.isLoading = true;
      await this.$store.dispatch('auth/checkUser');
      await this.$store.dispatch('home/getData');
      await this.getCars();
      await this.getArchivedCars();
      this.isLoading = false;
    },
    methods: {
      showProfile(url) {
        this.$viewerApi({
          images: [`http://127.0.0.1:8000/images/cars/${url}`],
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
      async uploadImage(event) {
        console.log(event);
        if (event) {
          let formData = new FormData();
          formData.append('image', event);
          await API.post(`upload-car-image`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          })
            .then((response) => {
              this.updateData.image = response.data;
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
      async getCars() {
        await this.$store.dispatch('cars/getCars');
      },
      async getArchivedCars() {
        await this.$store.dispatch('cars/getArchivedCars');
      },
      async archiveCars() {
        this.isLoading = true;
        const { status, data } = await this.$store.dispatch('cars/archiveCar', this.deleteData);
        this.toastData(status, data);
        this.archiveDialog = false;
        this.deleteData = null;
        this.isLoading = false;
      },
      async deleteCar() {
        this.isLoading = true;
        const { status, data } = await this.$store.dispatch('cars/deleteCar', this.deleteData);
        this.toastData(status, data);
        this.deleteDialog = false;
        this.deleteData = null;
        this.isLoading = false;
      },
      async restoreCar() {
        this.isLoading = true;
        const { status, data } = await this.$store.dispatch('cars/restoreCar', this.restoreData);
        this.toastData(status, data);
        this.restoreDialog = false;
        this.restoreData = null;
        this.isLoading = false;
      },
      async saveCar() {
        const valid = this.$refs.form.validate();
        if (valid) {
          this.isLoading = true;
          if (this.inputType == 'create') {
            const { status, data } = await this.$store.dispatch('cars/newCar', this.updateData);
            this.toastData(status, data);
          }
          if (this.inputType == 'update') {
            const { status, data } = await this.$store.dispatch('cars/updateCar', this.updateData);
            this.toastData(status, data);
          }
          this.inputDialog = false;
          this.$refs.form.reset();
          this.isLoading = false;
        }
      },
      getData(data) {
        return data.length < 10 ? '0' + data.length : data.length;
      },
    },
    computed: {
      ...mapState('cars', ['cars', 'archivedCars']),
      ...mapState('home', ['branch', 'brands']),
      ...mapState('auth', ['user']),
    },
    watch: {
      inputDialog() {
        if (this.inputType == 'create') {
          this.valid = true;
          this.updateData = {
            model: '',
            description: '',
            user_id: '',
            remarks: '',
            image: '',
            mileage: '',
            plate_number: '',
            rate: {
              per_day: '',
              per_week: '',
              per_month: '',
              with_driver: '',
            },
          };
          this.$refs.form.reset();
        } else {
          this.$refs.form.resetValidation();
        }
      },
    },
  };
</script>
