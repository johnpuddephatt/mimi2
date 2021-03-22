<template>
<div class="editor-js-block">
  <h2>{{ data.heading }}</h2>
  <h3>{{ data.subheading }}</h3>
</div>
</template>

<script>
  export default {
    props: ['data']
  }
</script>
