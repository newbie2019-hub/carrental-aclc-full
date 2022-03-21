<template>
  <div>
    <viewer :images="images">
      <img v-for="src in images" :key="src" :src="src" />
    </viewer>
  </div>
</template>
<script>

  export default {
    props: {
      image: String,
      showViewer: Boolean,
    },
    data() {
      return {
        images: [this.image],
      };
    },
    methods: {
      show() {
        this.$viewerApi({
          images: this.images,
        });
      },
      close() {
        this.$emit('close');
      },
    },
    computed: {
      dialog: {
        get() {
          return this.showViewer;
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
