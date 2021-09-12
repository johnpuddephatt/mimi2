<template>
  <div class="editor-js-block">
    <div v-for="(key, row) in data" class="box" :key="key">
      <p>{{ row.original }}</p>
      <p>{{ data.translated }}</p>
    </div>
  </div>
</template>

<script>
export default {
  props: ["data"]
};
</script>
