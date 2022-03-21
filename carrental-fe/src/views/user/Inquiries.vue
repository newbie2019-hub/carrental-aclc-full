<template>
  <div class="pl-lg-6 pl-md-3">
    <div>
      <p class="text-h5 font-weight-bold mt-4 custom-primary-color mb-0">User Inquiries</p>
      <p>Shown below are the inquiries of the users who visited the platform.</p>
    </div>
    <v-row class="mb-5">
      <v-col>
        <v-card-title>
          <v-spacer></v-spacer>
          <v-spacer></v-spacer>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" outlined dense append-icon="mdi-magnify" class="mb-5" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="inquiries" :search="search" :loading="isLoading" :loading-text="'Retrieving users data. Please wait ...'">
          <template v-slot:item.email="{ item }">
            <a :href="`mailto:${item.email}`" class="text-no-wrap text-decoration-none">{{ item.email }}</a>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-layout>
              <v-btn
                @click="
                  deleteDialog = true;
                  deleteData = item;
                "
                small
                text
                color="red darken-1"
                >Delete</v-btn
              >
            </v-layout>
          </template>
        </v-data-table>
      </v-col>
    </v-row>

    <v-dialog v-model="deleteDialog" max-width="420">
      <v-card>
        <v-card-title class="text-h5"> Confirm Delete </v-card-title>
        <v-card-text class="">
          Are you sure you want to delete this user inquiry?
          <span class="red--text darken-2">Note: You cannot undo this action</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="deleteDialog = false"> Cancel </v-btn>
          <v-btn color="red darken-1" text @click="deleteInquiry" :loading="isLoading"> Delete </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
  import { mapState } from 'vuex';
  export default {
    data: () => ({
      search: '',
      deleteDialog: false,
      deleteData: {
        id: null,
      },
      headers: [
        {
          text: 'Full Name',
          align: 'start',
          sortable: false,
          value: 'name',
        },
        { text: 'Email', value: 'email' },
        { text: 'Message', value: 'message' },
        { text: 'Created On', value: 'created_at' },
        { text: 'Actions', value: 'actions' },
      ],
    }),
    async mounted() {
      this.isLoading = true;
      await this.$store.dispatch('auth/checkUser');
      await this.getInquiries();
      this.isLoading = false;
    },
    methods: {
      async getInquiries() {
        await this.$store.dispatch('inquiry/getInquiries');
      },
      async deleteInquiry() {
        this.isLoading = true;
        const { status, data } = await this.$store.dispatch('inquiry/deleteInquiry', this.deleteData);
        this.toastData(status, data);
        this.deleteDialog = false;
        this.deleteData = null;
        this.isLoading = false;
      },
    },
    computed: {
      ...mapState('inquiry', ['inquiries']),
    },
  };
</script>
