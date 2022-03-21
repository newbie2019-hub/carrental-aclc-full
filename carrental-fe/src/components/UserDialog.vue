<template>
  <div>
    <v-dialog v-model="dialog" max-width="420" :retain-focus="false">
      <v-card>
        <v-layout align-center justify-center class="pt-6" column>
          <v-avatar class="ma-0" size="100" color="primary">
            <img v-if="userData.info.profile_img" :src="`http://127.0.0.1:8000/images/${userData.info.profile_img}`" />
            <p v-else class="white--text font-weight-bold mb-0">{{ userData.info.first_name[0] }}{{ userData.info.last_name[0] }}</p>
          </v-avatar>

          <p class="font-weight-bold text-h6 font-grotesk mt-3 mb-0">{{ userData.info.last_name }}, {{ userData.info.first_name }} {{ userData.info.middle_name }}</p>
          <p class="text-subtitle mb-0 mt-1">
            <v-icon color="primary" small class="mr-1">mdi-email</v-icon>
            {{ userData.email }}
          </p>
          <p class="text-subtitle mb-0 w-75 mt-1 text-center">
            <v-icon color="primary" small> mdi-map-marker </v-icon>
            <small>{{ userData.info.address }}</small>
          </p>
        </v-layout>
        <v-layout align-content-space-around class="mt-5">
          <p class="text-subtitle mb-0 mx-auto">
            <v-icon color="primary" small> mdi-account </v-icon>
            {{ userData.info.gender }}
          </p>
          <p class="text-subtitle mb-0 mx-auto">
            <v-icon color="primary" small class="mr-1">mdi-calendar</v-icon>
            {{ userData.info.birthday }}
          </p>
          <p class="text-subtitle mb-0 mx-auto">
            <v-icon color="primary" small class="mr-1">mdi-phone</v-icon>
            {{ userData.info.contact_number }}
          </p>
        </v-layout>
        <v-layout class="mt-5" column>
          <p class="ml-4 mb-0"><small>Attachments</small></p>
          <p class="ml-4">
            <small
              >Valid ID:<a href="" @click.prevent="show" class="text-decoration-none"> {{ userData.info.valid_id }}</a></small
            >
          </p>
        </v-layout>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" class="mb-3 mt-2" text @click="close"> Close </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </div>
</template>
<script>
  export default {
    props: {
      showModal: Boolean,
      userData: Object,
    },
    data: () => ({
    }),
    methods: {
      close() {
        this.$emit('close');
      },
      show() {
        this.$viewerApi({
         images: [`http://127.0.0.1:8000/images/${this.userData.info.valid_id}`],
         options: { "inline": false, "button": true, "navbar": true, "title": true, "toolbar": true, "tooltip": true, "movable": true, "zoomable": true, "rotatable": true, "scalable": true, "transition": true, "fullscreen": true, "keyboard": true}
        });
      },
    },
    computed: {
      dialog: {
        get() {
          return this.showModal;
        },
        set(value) {
          if (!value) {
            this.$emit('close');
          }
        },
      },
    },
  };
</script>
