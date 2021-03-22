<template>
  <div class="editor-js-block">
      <div class="notification is-info">
        <p><strong>{{ data.title }}</strong></p>
        {{ data.message }}
      </div>
  </div>
</template>

<script>
  export default {
    props: ['data']
  }
</script>
