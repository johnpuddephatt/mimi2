<template>
  <div class="video-player--wrapper">
    <div class="has-square-media audio-background">
      <audio ref="audioPlayer" class="audio-player" :src="source" :autoplay="should_autoplay" controls />
    </div>
  </div>
</div>
</template>

<script>

export default {
  props: ['source', 'should_autoplay'],
  data() {
    return {
    };
  },

  mounted() {

  },

  watch: {
    should_autoplay: function(val) {
      if(val) {
        this.$refs.audioPlayer.play();
      }
      else {
        this.$refs.audioPlayer.pause();
        this.$refs.audioPlayer.currentTime = 0;

      }
    },

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
  width: 90%;
  transform: translateX(-50%);
}
</style>
