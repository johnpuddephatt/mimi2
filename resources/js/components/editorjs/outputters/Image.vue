<template>
  <div class="editor-js-block">
    <figure class="editor-js-image" :class="{ 'with-background': data.withBackground, 'with-border': data.withBorder, 'stretched': data.stretched }">
      <img :src="data.file.url" :alt="data.caption">
      <figcaption v-if="data.caption">
        {{ data.caption }}
      </figcaption>
    </figure>
  </div>
</template>

<script>
  export default {
    props: ['data']
  }
</script>
