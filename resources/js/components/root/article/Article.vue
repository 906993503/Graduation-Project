<template>
  <div class="ql-snow">
    <article class="ql-editor" v-html="text.content" style="min-height: 46rem;"></article>
  </div>
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