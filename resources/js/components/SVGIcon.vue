<template>
  <component :is="iconName">
  </component>
</template>

<script>
import { ApertureIcon, ArrowLeftIcon, ArrowRightIcon, BarChartIcon, CheckCircleIcon, CheckIcon, ChevronRightIcon, ChevronDownIcon, ChevronUpIcon, CircleIcon, MinusIcon, MenuIcon, PlusIcon, RefreshCwIcon, SendIcon, SettingsIcon, StarIcon, UploadIcon, XIcon } from 'vue-feather-icons'

export default {
  components: {
    ApertureIcon,
    ArrowLeftIcon,
    ArrowRightIcon,
    BarChartIcon,
    CheckCircleIcon,
    CheckIcon,
    ChevronDownIcon,
    ChevronUpIcon,
    ChevronRightIcon,
    CircleIcon,
    MinusIcon,
    MenuIcon,
    PlusIcon,
    RefreshCwIcon,
    SendIcon,
    SettingsIcon,
    StarIcon,
    UploadIcon,
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
