<template>
  <video
    ref="video"
    :width="constraints.video.width.ideal"
    :height="constraints.video.height.ideal"
    :src="source"
    :autoplay="autoplay"
    :playsinline="playsinline"
    muted
  />
</template>

<script>
import adapter from 'webrtc-adapter';

export default {
  name: "VueWebCam",

  props: {
    autoplay: {
      type: Boolean,
      default: true
    },
    deviceId: {
      type: String,
      default: null
    },
    screenshotFormat: {
      type: String,
      default: "image/jpeg"
    },
    playsinline: {
      type: Boolean,
      default: true
    }
  },

  data() {
    return {
      source: null,
      canvas: null,
      camerasListEmitted: false,
      cameras: [],
      constraints: {
        audio: true,
        video: {
          deviceId: null,
          facingMode: "user",
          width: {
            ideal: 480,
            max: 480
          },
          height: {
            ideal: 480
          },
          frameRate: {
            ideal: 16
          }
        }
      }
    };
  },

  watch: {
    deviceId: function(deviceId) {
      this.changeCamera(deviceId);
    }
  },

  mounted() {
    this.setupMedia();
  },

  beforeDestroy() {
    this.stop();
  },

  methods: {

    /**
     * setup media
     */
    setupMedia() {
      this.testMediaAccess();
    },

    /**
     * load available cameras
     */
    loadCameras() {
      navigator.mediaDevices
        .enumerateDevices()
        .then(deviceInfos => {

          let videoDevices = deviceInfos.filter((item) => {
            return item.kind == 'videoinput'
          });

          if(!videoDevices.length) {
            this.$emit("error", {name: 'NotFoundError', message: 'Couldn’t find at least one video device (WebCam.vue)'})
          }
          else {
            for (let i = 0; i !== deviceInfos.length; ++i) {
              let deviceInfo = deviceInfos[i];
              if (deviceInfo.kind === "videoinput") {
                this.cameras.push(deviceInfo);
              }
            }
          }
        })
        .then(() => {
          if (!this.camerasListEmitted) {
            if(this.deviceId) {
              this.constraints.video.deviceId = this.deviceId;
            }
            else if (this.cameras.length > 0) {
              this.constraints.video.deviceId = this.cameras[0].deviceId;
            }
            this.$emit("cameras", this.cameras);
            this.camerasListEmitted = true;
          }
        })
        .catch(error => {
          this.$emit("error", error)
          console.log('error');
        });
    },

    /**
     * change to a different camera stream, like front and back camera on phones
     */
    changeCamera(deviceId) {
      this.constraints.video.deviceId = deviceId;
      this.stop();
      this.$emit("camera-change", deviceId);
      this.start();
    },

    /**
     * load the stream to the
     */
    loadSrcStream(stream) {
      this.$refs.video.srcObject = stream;

      this.$refs.video.onloadedmetadata = () => {
        this.$emit("video-live", stream);
      };

      this.$emit("started", stream);
    },

    /**
     * stop the selected streamed video to change camera
     */
    stopStreamedVideo(videoElem) {
      let stream = videoElem.srcObject;
      let tracks = stream.getTracks();

      tracks.forEach(track => {
        // stops the video track
        track.stop();
        this.$emit("stopped", stream);

        this.$refs.video.srcObject = null;
        this.source = null;
      });
    },

    // stop the video
    stop() {
      if (this.$refs.video !== null && this.$refs.video.srcObject) {
        this.stopStreamedVideo(this.$refs.video);
      }
    },

    // start the video
    start() {
      if (this.constraints.video.deviceId) {
        this.loadCamera();
      }
    },

    /**
     * test access
     */
    testMediaAccess() {

      navigator.mediaDevices
        .getUserMedia(this.constraints)
        .then(stream => {
          //Make sure to stop this MediaStream
          let tracks = stream.getTracks();
          tracks.forEach(track => {
            track.stop();
          });
          this.loadCameras();
        })
        .catch(error => {
          this.$emit("error", error)
          console.log(error);
        });
    },

    /**
     * load the camera passed as index!
     */
    loadCamera() {
      navigator.mediaDevices
        .getUserMedia(this.constraints)
        .then(stream => {
          console.log(stream.getVideoTracks()[0].getSettings());
          console.log(navigator.mediaDevices.getSupportedConstraints());

          this.loadSrcStream(stream)
        })
        .catch(error => {
          this.$emit("error", error)
          console.log(error);
        });

    },

    /**
     * capture screenshot
     */
    capture() {
      this.getCanvas().toBlob((blob) => {
         this.$emit("photo", blob);
      }, "image/jpeg", 0.8);
    },

    /**
     * get canvas
     */
    getCanvas() {
      let video = this.$refs.video;
      if (!this.ctx) {
        let canvas = document.createElement("canvas");
        canvas.height = video.videoHeight;
        canvas.width = video.videoWidth;
        this.canvas = canvas;

        this.ctx = canvas.getContext("2d");
      }

      const { ctx, canvas } = this;
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

      return canvas;
    }
  }
};
</script>
