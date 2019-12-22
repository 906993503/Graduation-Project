<template>
  <article v-html="text.content" style="min-height: 46rem;"></article>
</template>

<script>
export default {
  props: {
    text: Object
  },
  data: function() {
    return {};
  }
};
</script>
<style>
</style>