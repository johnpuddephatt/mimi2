<template>
  <div class="video-player--wrapper">
    <div :class="ratio ? `has-${ratio}-media` : 'has-square-media'">
      <b-loading :is-full-page="false" :active.sync="isLoading"></b-loading>
      <video
        class="video-js vjs-big-play-centered"
        controls
        preload="auto"
        width="640"
        height="264"
        :poster="poster"
        playsinline
        ref="player"
      >
        <source :src="source" :type="type" />
        <p class="vjs-no-js">
          To view this video please enable JavaScript, and consider upgrading to
          a web browser that
          <a href="https://videojs.com/html5-video-support/" target="_blank"
            >supports HTML5 video</a
          >
        </p>
      </video>
    </div>
  </div>
</template>

<script>
import videojs from "video.js";

export default {
  props: ["ratio", "source", "type", "poster", "should_autoplay"],
  data() {
    return {
      isLoading: true,
      player: null
    };
  },

  watch: {
    should_autoplay: function(autoplay) {
      if (autoplay && !this.isLoading) {
        this.$refs.player.currentTime = 0;
        this.$refs.player.play();
      } else {
        this.$refs.player.pause();
      }
    }
  },

  mounted() {
    if (this.supportsHLS()) {
      this.$refs.player.addEventListener("playing", () => {
        this.onEnded;
      });
      this.$refs.player.addEventListener("ended", () => {
        this.onPlay;
      });
      this.$refs.player.addEventListener("canplay", () => {
        this.$refs.player.currentTime = 0;
        if (this.should_autoplay) {
          this.$refs.player.play();
        }
      });
      this.$refs.player.addEventListener("canplaythrough", () => {
        this.isLoading = false;
      });
    } else {
      this.player = videojs(this.$refs.player, {});

      this.player.on("ended", this.onEnded);

      this.player.on("play", this.onPlay);

      this.player.ready(() => {
        this.isLoading = false;
        if (this.should_autoplay) {
          this.player.play();
        }
      });
    }
  },
  beforeDestroy() {
    if (!this.supportsHLS()) {
      this.player.dispose();
    }
  },

  methods: {
    pause() {
      this.$refs.player.pause();
    },
    onPlay() {
      console.log("onPlay");
      this.$emit("playing");
    },
    onEnded() {
      console.log("onEnded");
      this.$emit("stopped");
    },
    supportsHLS() {
      var video = document.createElement("video");
      return Boolean(video.canPlayType("application/vnd.apple.mpegURL"));
    }
  }
};
</script>

<style lang="scss">
@import "~video.js/dist/video-js.css";

.video-player--wrapper {
  position: relative;
  flex-grow: 0;
  background-color: #000;
}

.video-player--wrapper .has-square-media {
  border: none;
}

.vjs-poster {
  background-size: cover;
}

.video-js .vjs-big-play-button {
  height: 2em;
  width: 2em;
  margin-left: -1em;
  margin-top: -1em;
  border-radius: 9999px;
  background-color: #00c2cdaa;
  border: none;
}

.video-js:hover .vjs-big-play-button,
.video-js .vjs-big-play-button:focus {
  background-color: #00c2cdff;
}

.video-js .vjs-big-play-button .vjs-icon-placeholder::before {
  line-height: 1.333em;
  color: #fff;
  font-size: 1.5em;
}

.video-js.vjs-ended .vjs-big-play-button {
  display: block;
}

.video-js.vjs-ended .vjs-big-play-button .vjs-icon-placeholder::before {
  content: "\F116";
}

.video-js .vjs-control-bar {
  background-color: #00c2cdaa;
  bottom: 2.5rem;
  left: 2.5rem;
  right: 2.5rem;
  width: auto;
  border-radius: 5px;
  padding-top: 0.5em;
  padding-bottom: 0.5em;
  height: 4em;
}

.video-js video {
  opacity: 0;
  animation: fadeIn 0.5s 0.5s forwards;
}

@keyframes fadeIn {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

.vjs-has-started .vjs-control-bar {
  opacity: 0;
  transition: 400ms 750ms;
}

.vjs-ended .vjs-control-bar {
  opacity: 0 !important;
  transition: 400ms 750ms;
}

.vjs-has-started:hover .vjs-control-bar {
  opacity: 1;
  transition: 200ms 0ms;
}

.vjs-fullscreen video {
  object-fit: contain !important;
}
</style>
