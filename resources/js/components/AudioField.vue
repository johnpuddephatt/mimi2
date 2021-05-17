<template>

<div class="camera-wrapper">

  <!-- Audio preview -->
  <div v-if="value">
    <div class="has-square-media audio-preview">
      <audio controls :src="dataUrl" />
    </div>

    <b-button class="restart-audio" @click.prevent="confirmRestart">Start again</b-button>

  </div>

  <!-- Audio capture -->
  <div v-else>

    <div>
      <div class="has-square-media">
        <span v-if="isRecording"  class="recording-indicator tag" :class="timeRemaining < 30 ? 'is-red': 'is-black'">{{ timeRemaining > 30 ? 'Rec' : timeRemaininginMinutes }}</span>
        <b-notification type="is-dark" v-if="mode == 'video' && isLoaded" class="recording-instructions" v-model="showInstructions" aria-close-label="Close instructions">
          <p class="is-size-7"><b-icon icon="check-circle" />Aim for a reply between 30 seconds and two minutes long. Five minutes is the maximum.</p>
          <p class="is-size-7"><b-icon icon="check-circle" />You’ll be able to play your audio back before uploading, and re-record it if you want to.</p>
        </b-notification>

        <canvas width="420" height="420" ref="canvas" class="visualizer"></canvas>

      </div>
      <div class="camera-controls has-background-light is-bordered has-text-centered">

        <b-tooltip :label="isRecording ? 'Stop recording' : 'Start recording'" type="is-dark" animated position="is-bottom" :delay="1000" class="shutter-tooltip">
          <b-button size="is-large" type="is-danger" @click.prevent="onRecordToggle" class="take-photo" icon-right="circle" />
        </b-tooltip>

      </div>
    </div>
  </div>
</div>
</template>

<script>
import VueWebCam from "./WebCam";
var platform = require('platform');

export default {
  components: {
    'vue-web-cam': VueWebCam
  },
  props: {
    mode: {
      default: 'photo',
      type: String
    },
    value: {
    }
  },
  data() {
    return {
      shouldStop: false,
      audioCtx: null,
      analyser: null,
      chunks: [],
      dataArray: null,
      bufferLength: null,
      showInstructions: true,
      cameraType: 'mediaRecorder',
      mediaRecorder: null,
      isStarted: false,
      newValue: null,
      isRecording: false,
      shouldStopRecording: false,
      errorMessage: null,
      warningMessage: null,
      timeRemaining: null,
      timer: null,
      maxDuration: 300,
      recordingBitrate: {
        audio: 128000
      },
      accept: {
        photo: 'audio/*',
        video: 'audio/mpeg,audio/mp4,video/*'
      }
    };
  },
  computed: {
    timeRemaininginMinutes: function() {
      let minutes = Math.floor(this.timeRemaining / 60);
      let seconds = ('0' + Math.floor(this.timeRemaining % 60)).slice(-2);;
      return `${minutes}:${seconds}`;
    },
    dataUrl: function() {
      return this.value instanceof Blob ? URL.createObjectURL(this.value) : this.value;
    }
  },
  watch: {

  },

  mounted: function () {
    this.setup();
  },

  beforeDestroy() {
    this.shouldStop = true;
  },

  methods: {

    setup() {
      if (navigator.mediaDevices.getUserMedia) {
        const constraints = { audio: true };
        navigator.mediaDevices.getUserMedia(constraints).then(this.onSuccess, this.onError);
      } else {
         console.log('getUserMedia not supported on your browser!');
      }
    },

    onRecordToggle() {
      if(!this.isRecording) {
        this.showInstructions = false;
        this.timeRemaining = this.maxDuration;
        this.isRecording = true;
        this.timer = setInterval(()=>{
          this.timeRemaining--;
          if(this.timeRemaining < 1) {
            this.onRecordToggle();
          }
        }, 1000);
        this.mediaRecorder.start()
      }
      else {
        this.isRecording = false;
        clearInterval(this.timer);
        this.timeRemaining = this.maxDuration;
        this.mediaRecorder.stop();
        // Timeout fallback for Safari, which only fires the dataavailable event once on stop
        setTimeout(() => {
          if (this.mediaRecorder && this.mediaRecorder.state == 'recording') {
            this.mediaRecorder.stop();
          }
        }, 1000);
      }
    },

    confirmRestart() {
      this.$buefy.dialog.confirm({
        title: 'Start again?',
        message: 'Are you sure you want to <b>re-record</b> your audio? What you’ve recorded so far will be lost.',
        confirmText: 'Confirm',
        type: 'is-danger',
        hasIcon: true,
        onConfirm: () => this.onRestart()
      })
    },

    onRestart() {
      this.$emit('input', null);
      this.setup();
    },

    onSuccess(stream) {
      this.mediaRecorder = new MediaRecorder(stream, this.options);
      this.visualize(stream);

      this.mediaRecorder.onstop = (e) => {
        const blob = new Blob(this.chunks, { 'type' : this.mediaRecorder.mimeType });
        this.chunks = [];
        this.mediaRecorder = null;
        this.audioCtx = null;
        this.analyser = null;
        this.$emit('input', blob)
      }

      this.mediaRecorder.ondataavailable = (e) => {
        this.chunks.push(e.data);
      }
    },

    onError(err) {
      console.log('The following error occured: ' + err);
    },

    visualize(stream) {

      if(!this.audioCtx) {
        this.audioCtx = new AudioContext();
      }

      const source = this.audioCtx.createMediaStreamSource(stream);
      this.analyser = this.audioCtx.createAnalyser();
      this.analyser.fftSize = 128;
      this.bufferLength = this.analyser.frequencyBinCount;
      this.dataArray = new Uint8Array(this.bufferLength);
      source.connect(this.analyser);
      this.requestDraw();
    },

    requestDraw() {
      if(!this.value && !this.shouldStop) {
        window.requestAnimationFrame(this.requestDraw);
        this.visCircle();
      }
    },

    visCircle() {
      const colBg   = 'rgba(98, 191, 205, 0.25)';
      const colBar0 = 'rgba(255, 255, 255, 0.04)';
      const colBar1 = 'rgba(255, 255, 255, 0.5)';
      const colBar2 = 'rgba(233, 169, 70, 0.8)';

      const barWidth   = 8;
      const barLength  = 0.1;
      const bassFactor = 1.2;

      this.analyser.getByteFrequencyData(this.dataArray);
      let width  = this.$refs.canvas.offsetWidth;
      let height = this.$refs.canvas.offsetHeight;
      let dtRot  = (360 / this.bufferLength * 2) * Math.PI/180;
    	let bass   = Math.floor(this.dataArray[1]);
    	let radius = -(width*barLength + bass*bassFactor);

      this.$refs.canvas.getContext("2d").fillStyle = colBg;
    	this.$refs.canvas.getContext("2d").fillRect(0, 0, width, height);
    	this.$refs.canvas.getContext("2d").save();
    	this.$refs.canvas.getContext("2d").scale(0.5, 0.5);
      this.$refs.canvas.getContext("2d").translate(width, (1.1 * height));

      this.$refs.canvas.getContext("2d").fillStyle = colBar1;
      this.draw(radius, barWidth, 0.50,  dtRot);
      this.draw(radius, barWidth, 0.50, -dtRot);
      this.$refs.canvas.getContext("2d").fillStyle = colBar2;
      this.draw(radius, barWidth, 0.1,  dtRot);
      this.draw(radius, barWidth, 0.1, -dtRot);
    	this.$refs.canvas.getContext("2d").restore();
    },

    draw(rad, wdt, mlt, rot) {
      let threshold = 0;
      for (let i = 0; i < this.bufferLength; ++i) {
        let smp = this.dataArray[i];
        if (smp >= threshold) {
         this.$refs.canvas.getContext("2d").fillRect(0, rad/2, wdt, -smp*mlt);
         this.$refs.canvas.getContext("2d").rotate(rot);
        }
      }
    }

  }
}
</script>


<style lang="scss">
@import "../../sass/variables";

.camera-wrapper {
  position: relative;
}

.restart-audio {
  position: absolute;
  top: 10px;
  right: 20px;
}

.audio-preview {
  background-color: lighten($success, 35%);
  background-image: url(/images/sine.svg);
  background-repeat: no-repeat;
  background-size: contain;
  background-position: center;

  audio {
    position: absolute;
    bottom: 1rem;
    left: 50%;
    transform: translateX(-50%);
  }
}

  .visualizer {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    background-color: rgb(98, 191, 205);
    display: block;
    margin-bottom: 0.5rem;
  }

  // .camera-wrapper {
  //   position: relative;
  // }

  .field .camera-wrapper .has-square-media {
    border-radius: 5px 5px 0 0;
    border: 1px solid #dbdbdb;
  }

  .camera-controls {
    padding: .5em 0;
  }

  .field .camera-controls {
    border-radius: 0 0 5px 5px;
    border: 1px solid #dbdbdb;
    border-top: none;
  }

  .camera-controls button,
  .camera-controls a {
    border-radius: 9999px;
  }

  .camera-controls>* {
    vertical-align: middle;
  }

  .change-camera-tooltip {
    margin-left: -5rem;
    margin-right: 1.5rem;
  }

  .file-upload-tooltip {
    margin-left: 1.5rem;
    margin-right: -5rem;
  }


  .recording-indicator {
    position: absolute;
    right: .5em;
    top: .5em;
    z-index: 9;
    padding-right: 2em !important;

    &.is-danger::after {
      background-color: white;
    }
  }

  .recording-indicator::after {
    width: .75em;
    height: .75em;
    position: absolute;
    right: .75em;
    background-color: red;
    border-radius: 9999px;
    content: '';
    animation: pulse 2s infinite ease;
  }

  @keyframes pulse {
    0%, 100% {
      transform: scale(0.75);
    }
    90% {
      transform: scale(1);
    }
  }

  .recording-instructions {
    position: absolute;
    bottom: 0;
    left: 1em;
    right: 1em;
    background-color: #000A !important;
    z-index: 9;

    p + p {
      margin-top: 0.5em;
    }

    p {
      padding-left: 2.2rem;
      margin-bottom: 0;
    }

    .icon {
      margin-left: -2rem;
      margin-right: 0.5rem;
      transform: translateY(40%);
    }
  }
</style>
