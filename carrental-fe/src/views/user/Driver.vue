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
          <template v-slot:item.profile="{ item }">
            <v-avatar class="ma-0" size="38" color="primary">
              <img class="cursor-pointer" @click.prevent="showProfile(item.profile_img)" v-if="item.profile_img" :src="`http://127.0.0.1:8000/images/${item.profile_img}`" />
              <p v-else class="white--text font-weight-bold mb-0">{{ item.first_name[0] }}{{ item.last_name[0] }}</p>
            </v-avatar>
          </template>
          <template v-slot:item.validid="{ item }">
            <v-avatar class="ma-0" size="38" color="grey lighten-1" tile>
              <img class="cursor-pointer" @click.prevent="showProfile(item.drivers_license)" :src="`http://127.0.0.1:8000/images/${item.drivers_license}`" />
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
          <template v-slot:item.profile="{ item }">
            <v-avatar class="ma-0" size="38" color="primary">
              <img class="cursor-pointer" @click.prevent="showProfile(item.profile_img)" v-if="item.profile_img" :src="`http://127.0.0.1:8000/images/${item.profile_img}`" />
              <p v-else class="white--text font-weight-bold mb-0">{{ item.first_name[0] }}{{ item.last_name[0] }}</p>
            </v-avatar>
          </template>
          <template v-slot:item.validid="{ item }">
            <v-avatar class="ma-0" size="38" color="grey lighten-1" tile>
              <img class="cursor-pointer" @click.prevent="showProfile(item.drivers_license)" :src="`http://127.0.0.1:8000/images/${item.drivers_license}`" />
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
        <v-card class="pb-2">
          <v-card-title class="text-h5"> {{ inputType == 'update' ? 'Update' : 'New' }} Driver </v-card-title>
          <v-card-subtitle>All fields are required</v-card-subtitle>
          <v-layout justify-center class="mb-5">
            <v-avatar class="ma-0" size="80" color="grey lighten-2">
              <img v-if="updateData.profile_img" :src="`http://127.0.0.1:8000/images/${updateData.profile_img}`" />
            </v-avatar>
          </v-layout>
          <v-layout column class="mb-4 pr-4 pl-4">
            <v-file-input
              @change="uploadImageLicense"
              v-model="updateData.licenseImg"
              prepend-icon=""
              label="Drivers License"
              class="mt-2"
              accept="image/*"
              prepend-inner-icon="mdi-paperclip"
              outlined
              dense
              hide-details="auto"
              show-size
              truncate-length="15"
            >
            </v-file-input>
            <v-file-input
              @change="uploadImage"
              v-model="updateData.profileImg"
              prepend-icon=""
              label="Profile Image"
              class="mt-2"
              accept="image/*"
              prepend-inner-icon="mdi-paperclip"
              outlined
              dense
              hide-details="auto"
              show-size
              truncate-length="15"
            >
            </v-file-input>
            <v-text-field outlined hide-details="auto" dense class="mt-2" v-model="updateData.first_name" :rules="required" label="First Name" required></v-text-field>
            <v-text-field outlined hide-details="auto" dense class="mt-2" v-model="updateData.middle_name" label="Middle Name" required></v-text-field>
            <v-text-field outlined hide-details="auto" dense class="mt-2" v-model="updateData.last_name" :rules="required" label="Last Name" required></v-text-field>
            <v-select class="mt-2" v-model="updateData.gender" :rules="required" :items="gender" label="Gender" hide-details="auto" outlined dense></v-select>
            <v-text-field outlined hide-details="auto" dense class="mt-2" v-model="updateData.contact_number" :rules="required" label="Contact Number" required></v-text-field>
            <v-textarea outlined hide-details="auto" dense class="mt-2" v-model="updateData.address" :rules="required" label="Address" auto-grow rows="2" required></v-textarea>
            <v-menu ref="menu" v-model="menu" :close-on-content-click="false" transition="scale-transition" offset-y min-width="auto">
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="updateData.birthday"
                  label="Date of Birth"
                  append-icon="mdi-calendar"
                  readonly
                  class="mt-2"
                  v-bind="attrs"
                  hide-details="auto"
                  v-on="on"
                  :loading="isLoading"
                  :rules="required"
                  dense
                  outlined
                ></v-text-field>
              </template>
              <v-date-picker v-model="updateData.birthday" :active-picker.sync="activePicker" min="1960-01-01" :max="maxBirthDate" @change="save" landscape></v-date-picker>
            </v-menu>
            <v-text-field outlined hide-details="auto" dense class="mt-2" v-model="updateData.email" :rules="required" label="Email" required></v-text-field>
          </v-layout>
          <v-card-actions class="">
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
      activePicker: null,
      menu: false,
      archiveDialog: false,
      viewInfoDialog: false,
      deleteDialog: false,
      restoreDialog: false,
      inputDialog: false,
      inputType: null,
      maxBirthDate: '',
      updateData: {
        brand: '',
        logo: '',
        image: '',
        first_name: '',
        middle_name: '',
        last_name: '',
        gender: '',
        address: '',
        licenseImg: '',
        profileImg: '',
        birthday: '',
        profile_img_uploaded: ''
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
        {
          text: 'Drivers License',
          align: 'start',
          sortable: false,
          value: 'validid',
        },
        { text: 'First Name', value: 'first_name' },
        { text: 'Middle Name', value: 'middle_name' },
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
        {
          text: 'Drivers License',
          align: 'start',
          sortable: false,
          value: 'validid',
        },
        { text: 'First Name', value: 'first_name' },
        { text: 'Middle Name', value: 'middle_name' },
        { text: 'Last Name', value: 'last_name' },
        { text: 'Email', value: 'email' },
        { text: 'Gender', value: 'gender' },
        { text: 'Date of Birth', value: 'birthday' },
        { text: 'Contact Number', value: 'contact_number' },
        { text: 'Date Created', value: 'created_at' },
        { text: 'Deleted On', value: 'deleted_at' },
        { text: 'Actions', value: 'actions' },
      ],
      gender: [
        {
          text: 'Male',
          value: 'Male'
        },
        {
          text: 'Female',
          value: 'Female'
        },
      ]
    }),
    async mounted() {
      this.isLoading = true;
      await this.$store.dispatch('auth/checkUser');
      await this.getDrivers();
      await this.getArchivedDrivers();
      this.maxDate()
      this.isLoading = false;
    },
    methods: {
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
      async uploadImageLicense(event) {
        if (event) {
          let formData = new FormData();
          formData.append('image', event);
          await API.post(`upload-image-license`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          })
            .then((response) => {
              this.updateData.drivers_license = response.data;
            })
            .catch((error) => {
              console.log({ error });
            });
        }
      },
      maxDate() {
        const date = new Date();
        const newDate = (date.getFullYear() - 17).toString() + '-01-01';
        this.maxBirthDate = newDate;
      },
      save(date) {
        this.$refs.menu.save(date);
      },
      async uploadImage(event) {
        if (event) {
          let formData = new FormData();
          formData.append('profile_img', event);
          await API.post(`upload-profile`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          })
            .then((response) => {
              this.updateData.profile_img_uploaded = response.data;
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
            const { status, data } = await this.$store.dispatch('drivers/newDriver', this.updateData);
            this.toastData(status, data);
          }
          if (this.inputType == 'update') {
            const { status, data } = await this.$store.dispatch('drivers/updateDriver', this.updateData);
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
