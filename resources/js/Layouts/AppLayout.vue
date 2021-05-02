<template>
  <section class="section" :class="{'is-medium' : padded}">
    <div class="container">
      <slot></slot>
    </div>
  </section>
</template>

<script>

export default {
  props: {
    padded: {
      type: Boolean,
      default: true
    }
  }
}

</script>
