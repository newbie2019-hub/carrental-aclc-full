<template>
  <div class="pl-lg-6 pl-md-3">
    <div>
      <p class="text-h5 font-weight-bold mt-4 custom-primary-color mb-0">Drivers Management</p>
      <p>You can manage all of the drivers below</p>
    </div>
    <v-row class="mt-5 mb-5">
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="blue darken-1" class="pa-3" rounded="lg" dark>
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-car-brake-retarder </v-icon>
              </div>
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Total <br />Drivers</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ getData(drivers) }}</p>
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
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Archived <br />Drivers</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ getData(archivedDrivers) }}</p>
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
            >New Driver</v-btn
          >
          <v-spacer></v-spacer>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" outlined dense append-icon="mdi-magnify" class="mb-5" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="drivers" :search="search" :loading="isLoading" :loading-text="'Retrieving drivers data. Please wait ...'">
          <template v-slot:item.logo="{ item }">
            <v-avatar class="ma-0" size="38" color="grey lighten-2">
              <img v-if="item.profile_img" :src="`http://127.0.0.1:8000/images/${item.profile_img}`" />
            </v-avatar>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-layout>
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
          Archived Drivers
          <v-spacer></v-spacer>
          <v-spacer></v-spacer>
          <v-text-field v-model="searchArchived" outlined dense append-icon="mdi-magnify" class="mb-5" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table :headers="archivedHeaders" :items="archivedDrivers" :search="searchArchived" :loading="isLoading" :loading-text="'Retrieving drivers data. Please wait ...'">
          <template v-slot:item.logo="{ item }">
            <v-avatar class="ma-0" size="38" color="grey lighten-2">
              <img v-if="item.profile_img" :src="`http://127.0.0.1:8000/images/${item.profile_img}`" />
            </v-avatar>
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
          Are you sure you want to delete this driver's data?
          <span class="red--text darken-2">Note: This driver will be marked as archived to preserve any related transactions.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="archiveDialog = false"> Cancel </v-btn>
          <v-btn color="red darken-1" text @click="archiveDriver" :loading="isLoading"> Archive </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="inputDialog" max-width="420">
      <v-form ref="form" v-model="valid" @submit.prevent="saveDriver" lazy-validation class="pa-0 ma-0">
        <v-card class="">
          <v-card-title class="text-h5"> {{ inputType == 'update' ? 'Update' : 'New' }} Driver </v-card-title>
          <v-card-subtitle>All fields are required</v-card-subtitle>
          <v-layout justify-center class="mb-5">
            <v-avatar class="ma-0" size="80" color="grey lighten-2">
              <img v-if="updateData.profile_img" :src="`http://127.0.0.1:8000/images/${updateData.profile_img}`" />
            </v-avatar>
          </v-layout>
          <v-layout column class="mb-4 pr-4 pl-4">
            <v-file-input
              @change="uploadImage"
              v-model="updateData.image"
              prepend-icon=""
              label="New Profile Image"
              class=""
              :rules="required"
              accept="image/*"
              prepend-inner-icon="mdi-paperclip"
              outlined
              dense
              hide-details="auto"
              show-size
              truncate-length="15"
            >
            </v-file-input>
            <v-text-field prepend-inner-icon="mdi-card-bulleted" outlined hide-details="auto" dense class="mt-2" v-model="updateData.first_name" :rules="required" label="First Name" required></v-text-field>
            <v-text-field prepend-inner-icon="mdi-card-bulleted" outlined hide-details="auto" dense class="mt-2" v-model="updateData.middle_name" :rules="required" label="Middle Name" required></v-text-field>
          </v-layout>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="grey darken-2" text @click="inputDialog = false"> Cancel </v-btn>
            <v-btn color="green darken-1" text type="submit" :loading="isLoading"> {{ inputType == 'update' ? 'Update' : 'Save' }} </v-btn>
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
          <v-btn color="red darken-1" text @click="deleteDriver" :loading="isLoading"> Delete </v-btn>
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
          <v-btn color="green " text @click="restoreDriver" :loading="isLoading"> Restore </v-btn>
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
      updateData: {
        brand: '',
        logo: '',
        image: '',
      },
      deleteData: {
        id: null,
      },
      restoreData: {},
      isModalVisible: false,
      headers: [
        {
          text: 'Profile',
          align: 'start',
          sortable: false,
          value: 'profile',
        },
        { text: 'First Name', value: 'first_name' },
        { text: 'Last Name', value: 'last_name' },
        { text: 'Email', value: 'email' },
        { text: 'Gender', value: 'gender' },
        { text: 'Date of Birth', value: 'birthday' },
        { text: 'Contact Number', value: 'contact_number' },
        { text: 'Date Created', value: 'created_at' },
        { text: 'Actions', value: 'actions' },
      ],
      archivedHeaders: [
         {
          text: 'Profile',
          align: 'start',
          sortable: false,
          value: 'profile',
        },
        { text: 'First Name', value: 'first_name' },
        { text: 'Last Name', value: 'last_name' },
        { text: 'Email', value: 'email' },
        { text: 'Gender', value: 'gender' },
        { text: 'Date of Birth', value: 'birthday' },
        { text: 'Contact Number', value: 'contact_number' },
        { text: 'Date Created', value: 'created_at' },
        { text: 'Deleted On', value: 'deleted_at' },
        { text: 'Actions', value: 'actions' },
      ],
    }),
    async mounted() {
      this.isLoading = true;
      await this.$store.dispatch('auth/checkUser');
      await this.getDrivers();
      await this.getArchivedDrivers();
      this.isLoading = false;
    },
    methods: {
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
      async getDrivers() {
        await this.$store.dispatch('drivers/getDrivers');
      },
      async getArchivedDrivers() {
        await this.$store.dispatch('drivers/getArchivedDrivers');
      },
      async archiveDriver() {
        this.isLoading = true;
        const { status, data } = await this.$store.dispatch('drivers/archiveDriver', this.deleteData);
        this.toastData(status, data);
        this.archiveDialog = false;
        this.deleteData = null;
        this.isLoading = false;
      },
      async deleteDriver() {
        this.isLoading = true;
        const { status, data } = await this.$store.dispatch('drivers/deleteDriver', this.deleteData);
        this.toastData(status, data);
        this.deleteDialog = false;
        this.deleteData = null;
        this.isLoading = false;
      },
      async restoreDriver() {
        this.isLoading = true;
        const { status, data } = await this.$store.dispatch('drivers/restoreDriver', this.restoreData);
        this.toastData(status, data);
        this.restoreDialog = false;
        this.restoreData = null;
        this.isLoading = false;
      },
      async saveDriver() {
        const valid = this.$refs.form.validate();
        if (valid) {
          this.isLoading = true;
          if (this.inputType == 'create') {
            const { status, data } = await this.$store.dispatch('drivers/newBrand', this.updateData);
            this.toastData(status, data);
          }
          if (this.inputType == 'update') {
            const { status, data } = await this.$store.dispatch('drivers/updateBrand', this.updateData);
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
      ...mapState('drivers', ['drivers', 'archivedDrivers']),
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
