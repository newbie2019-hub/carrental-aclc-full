<template>
  <div class="pl-lg-6 pl-md-3">
    <div>
      <p class="text-h5 font-weight-bold mt-4 custom-primary-color mb-0">Branch Management</p>
      <p>You can manage all of the Renta Car Branch below</p>
    </div>
    <v-row class="mt-5 mb-5">
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="blue darken-1" class="pa-3" rounded="lg" dark>
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-car-brake-retarder </v-icon>
              </div>
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Total <br />Branch</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ getData(branch) }}</p>
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
              <v-card-title class="text-h5 font-grotesk font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Archived <br />Branch</v-card-title>
            </div>
            <div>
              <p class="text-h1 font-grotesk pr-5 font-weight-bold mb-0">{{ getData(archivedBranch) }}</p>
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
            >New Branch</v-btn
          >
          <v-spacer></v-spacer>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" outlined dense append-icon="mdi-magnify" class="mb-5" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="branch" :search="search" :loading="isLoading" :loading-text="'Retrieving users data. Please wait ...'">
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
                color="primary darken-1"
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
          Archived Branch
          <v-spacer></v-spacer>
          <v-spacer></v-spacer>
          <v-text-field v-model="searchArchived" outlined dense append-icon="mdi-magnify" class="mb-5" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table :headers="archivedHeaders" :items="archivedBranch" :search="searchArchived" :loading="isLoading" :loading-text="'Retrieving users data. Please wait ...'">
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
          Are you sure you want to delete this branch?
          <span class="red--text darken-2">Note: This branch will be marked as archived to preserve any related transactions.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="archiveDialog = false"> Cancel </v-btn>
          <v-btn color="red darken-1" text @click="archiveBranch" :loading="isLoading"> Archive </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="inputDialog" max-width="420">
      <v-form ref="form" v-model="valid" @submit.prevent="saveBranch" lazy-validation class="pa-0 ma-0">
        <v-card class="">
          <v-card-title class="text-h5"> {{ inputType == 'update' ? 'Update' : 'Create' }} Branch </v-card-title>
          <v-card-subtitle>All fields are required</v-card-subtitle>
          <v-layout column class="pl-4 pr-4 mb-4">
            <v-text-field outlined hide-details="auto" dense class="mt-2" v-model="updateData.branch" :rules="required" label="Branch" required></v-text-field>
            <v-textarea auto-grow rows="2" outlined hide-details="auto" dense class="mt-2" v-model="updateData.description" :rules="required" label="Description" required></v-textarea>
            <v-textarea auto-grow rows="1" outlined hide-details="auto" dense class="mt-2" v-model="updateData.address" :rules="required" label="Branch Address" required></v-textarea>
          </v-layout>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="grey darken-2" text @click="inputDialog = false"> Cancel </v-btn>
            <v-btn color="green darken-1" text type="submit" :loading="isLoading"> {{ inputType == 'update' ? 'Update' : 'Create' }} </v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>

    <v-dialog v-model="deleteDialog" max-width="420">
      <v-card>
        <v-card-title class="text-h5"> Confirm Delete </v-card-title>
        <v-card-text class="">
          Are you sure you want to permanently delete this branch?
          <span class="red--text darken-2">Note: You cannot undo this action.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="deleteDialog = false"> Cancel </v-btn>
          <v-btn color="red darken-1" text @click="deleteBranch" :loading="isLoading"> Delete </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="restoreDialog" max-width="420">
      <v-card>
        <v-card-title class="text-h5"> Restore Account </v-card-title>
        <v-card-text class="">
          Are you sure you want to restore this user account?
          <span class="red--text darken-2">Note: This will also restore any related transactions from this branch.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="restoreDialog = false"> Cancel </v-btn>
          <v-btn color="green " text @click="restoreBranch" :loading="isLoading"> Restore </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
  import moment from 'moment';
  import { mapState } from 'vuex';
  export default {
    data: () => ({
      required: [(v) => !!v || 'Field is required'],
      search: '',
      searchArchived: '',
      valid: true,
      archiveDialog: false,
      deleteDialog: false,
      restoreDialog: false,
      inputDialog: false,
      inputType: null,
      updateData: {
        branch: '',
        description: '',
        address: '',
      },
      deleteData: {
        id: null,
      },
      branchData: {},
      restoreData: {},
      isModalVisible: false,
      headers: [
        {
          text: 'Branch',
          align: 'start',
          sortable: false,
          value: 'branch',
        },
        { text: 'Address', value: 'address' },
        { text: 'Description', value: 'description' },
        { text: 'Assignee', value: 'assignee' },
        { text: 'Added on', value: 'created_at' },
        { text: 'Actions', value: 'actions' },
      ],
      archivedHeaders: [
        {
          text: 'Branch',
          align: 'start',
          sortable: false,
          value: 'branch',
        },
        { text: 'Address', value: 'address' },
        { text: 'Description', value: 'description' },
        { text: 'Assignee', value: 'assignee' },
        { text: 'Deleted On', value: 'deleted_at' },
        { text: 'Actions', value: 'actions' },
      ],
    }),
    async mounted() {
      this.isLoading = true;
      await this.$store.dispatch('auth/checkUser');
      await this.getBranch();
      await this.getArchivedBranch();
      this.isLoading = false;
    },
    methods: {
      showModal() {
        this.isModalVisible = true;
      },
      closeModal() {
        this.isModalVisible = false;
      },
      async getBranch() {
        await this.$store.dispatch('branch/getBranch');
      },
      async getArchivedBranch() {
        await this.$store.dispatch('branch/getArchivedBranch');
      },
      async archiveBranch() {
        this.isLoading = true;
        const { status, data } = await this.$store.dispatch('branch/archiveBranch', this.deleteData);
        this.toastData(status, data);
        this.archiveDialog = false;
        this.deleteData = null;
        this.isLoading = false;
      },
      async deleteBranch() {
        this.isLoading = true;
        const { status, data } = await this.$store.dispatch('branch/deleteBranch', this.deleteData);
        this.toastData(status, data);
        this.deleteDialog = false;
        this.deleteData = null;
        this.isLoading = false;
      },
      async restoreBranch() {
        this.isLoading = true;
        const { status, data } = await this.$store.dispatch('branch/restoreBranch', this.restoreData);
        this.toastData(status, data);
        this.restoreDialog = false;
        this.restoreData = null;
        this.isLoading = false;
      },
      async saveBranch() {
        const valid = this.$refs.form.validate();
        if (valid) {
          this.isLoading = true;
          if (this.inputType == 'create') {
            const { status, data } = await this.$store.dispatch('branch/newBranch', this.updateData);
            this.toastData(status, data);
          }
          if (this.inputType == 'update') {
            const { status, data } = await this.$store.dispatch('branch/updateBranch', this.updateData);
            this.toastData(status, data);
          }
          this.inputDialog = false
          this.$refs.form.reset()
          this.isLoading = false;
        }
      },
      getData(data) {
        return data.length < 10 ? '0' + data.length : data.length;
      },
    },
    computed: {
      ...mapState('branch', ['branch', 'archivedBranch']),
    },
    watch: {
      inputDialog() {
        if (this.inputType == 'create') {
          this.valid = true;
          this.updateData = {branch: '', address: '', user_id: '', description: ''}
          this.$refs.form.resetValidation()
        }
      },
    },
  };
</script>
