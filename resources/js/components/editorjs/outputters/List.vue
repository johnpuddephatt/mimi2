<template>
<div class="editor-js-block">
  <ul v-if="data.style == 'unordered'">
    <li v-for="item in data.items">
      {{ item }}
    </li>
  </ul>
  <ol v-else>
    <li v-for="item in data.items">
      {{ item }}
    </li>
  </ol>
</div>
</template>

<script>
  export default {
    props: ['data']
  }
</script>
