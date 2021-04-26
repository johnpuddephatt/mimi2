<template>
  <div class="video-player--wrapper">
    <div class="has-square-media audio-background">
      <audio class="audio-player" :src="source" controls />
    </div>
  </div>
</div>
</template>

<script>

export default {
  props: ['source'],
  data() {
    return {
    };
  },

  watch: {

  },

  mounted() {

  },



  methods: {

  }
};
</script>


<style lang="scss">
@import "../../sass/variables";

.audio-background {
  background-color: lighten($success, 35%);
  background-image: url(/images/sine.svg);
  background-repeat: no-repeat;
  background-size: contain;
  background-position: center;
}

.audio-player {
  position: absolute;
  bottom: 1em;
  left: 50%;
  max-width: 90%;
  transform: translateX(-50%);
}
</style>
