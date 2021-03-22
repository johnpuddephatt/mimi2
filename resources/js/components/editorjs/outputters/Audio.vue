<template>
  <div class="editor-js-block">
      <audio controls class="editor-js-audio" :src="data.file.url" />
  </div>
</template>

<script>
  export default {
    props: ['data']
  }
</script>
