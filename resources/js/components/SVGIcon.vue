<template>
  <component :is="iconName">
  </component>
</template>

<script>
import { ArrowLeftIcon, ArrowRightIcon, CheckCircleIcon, CheckIcon, ChevronRightIcon, ChevronDownIcon, ChevronUpIcon, MinusIcon, MenuIcon, PlusIcon, SettingsIcon, XIcon } from 'vue-feather-icons'

export default {
  components: {
    ArrowLeftIcon,
    ArrowRightIcon,
    CheckCircleIcon,
    CheckIcon,
    ChevronDownIcon,
    ChevronUpIcon,
    ChevronRightIcon,
    MinusIcon,
    MenuIcon,
    PlusIcon,
    SettingsIcon,
    XIcon
  },
  props: ['icon','size'],
  data() {
    return {
    }
  },
  computed: {
    iconName: function() {
      return `${ this.icon[1].replace(/-./g, x=>x.toUpperCase()[1]).replace(/\b\w/g, c => c. toUpperCase()) }Icon`;
    }
  },
  mounted() {
  },
  beforeDestroy() {
  },
}

</script>

<style lang="scss">
  .icon svg {
    stroke-width: 1.5 !important;
    fill: none !important;
  }
</style>
