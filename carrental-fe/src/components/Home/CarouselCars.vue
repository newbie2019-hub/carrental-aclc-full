<template>
  <div class="mt-10 mb-6">
    <v-row justify="end" class="mb-5 mt-5">
      <v-col cols="11" lg="4" md="5">
        <v-select :items="branch" item-value="id" item-text="branch" outlined v-model="location_id" dense label="Filter Branch" hide-details="auto"></v-select>
      </v-col>
    </v-row>
    <carousel :scrollPerPage="false" :perPage="3" :navigationEnabled="true" :paginationEnabled="false">
      <slide class="mr-3" v-for="(car, i) in cars" :key="i">
        <v-card max-width="450" rounded="xl" class="pa-3">
          <v-card-text>
            <div></div>
            <p class="text-h5 mb-0 font-weight-bold custom-primary-color">{{ car.name }}</p>
            <p>
              Owned by <span class="font-weight-bold">{{ car.owner }}</span>
            </p>
            <p class="text-subtitle font-weight-bold mb-0">
              <v-icon color="mr-3"> mdi-credit-card-outline </v-icon>
              ₱ {{ car.rate }}/day
            </p>
            <v-img
              contain
              lazy-src="https://www.enterprise.com/content/dam/global-vehicle-images/cars/FORD_FUSION_2020.png"
              max-height="180"
              max-width="250"
              src="https://www.enterprise.com/content/dam/global-vehicle-images/cars/FORD_FUSION_2020.png"
            ></v-img>
            <v-layout justify-space-around>
              <p class="mb-0 font-weight-bold"><v-icon color="" class="mr-2">mdi-account</v-icon>{{ car.seats }}</p>
              <p class="mb-0 font-weight-bold"><v-icon color="" class="mr-2">mdi-car-shift-pattern</v-icon>{{ car.transmission }}</p>
              <p class="mb-0 font-weight-bold"><v-icon color="" class="mr-2">mdi-map-marker</v-icon>{{ car.branch }}</p>
            </v-layout>
          </v-card-text>
        </v-card>
      </slide>
    </carousel>
  </div>
</template>
<script>
  import { Carousel, Slide } from 'vue-carousel';
import { mapState } from 'vuex';
  export default {
    components: {
      Carousel,
      Slide,
    },
    data: () => ({
      location_id: null,
      cars: [
        {
          name: 'Porsche 718 Cayman S',
          owner: 'Management',
          seats: '8',
          transmission: 'Automatic',
          branch: 'V&G Tacloban',
          rate: '1000.00',
        },
        {
          name: 'Porsche 718 Cayman S',
          owner: 'Management',
          seats: '6',
          transmission: 'Manual',
          branch: 'Calanipawan',
          rate: '1200.00',
        },
        {
          name: 'Porsche 718 Cayman S',
          owner: 'Management',
          seats: '6',
          transmission: 'Manual',
          branch: 'Diit',
          rate: '700.00',
        },
        {
          name: 'Porsche 718 Cayman S',
          owner: 'Management',
          seats: '6',
          transmission: 'Manual',
          branch: 'Sagkahan Bliss',
          rate: '2500.00',
        },
        {
          name: 'Toyota Vios 2019',
          owner: 'Management',
          seats: '6',
          transmission: 'Manual',
          branch: 'Sagkahan Bliss',
          rate: '2500.00',
        },
      ],
    }),
    computed: {
      ...mapState('home', ['cars', 'branch'])
    }
  };
</script>
<style>
  .VueCarousel-wrapper {
    padding: 1rem 0.5rem;
  }
</style>
