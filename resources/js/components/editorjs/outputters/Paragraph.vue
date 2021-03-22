<template>
<div class="editor-js-block">
  <p v-html="data.text"></p>
</div>
</template>

<script>
  export default {
    props: ['data']
  }
</script>
