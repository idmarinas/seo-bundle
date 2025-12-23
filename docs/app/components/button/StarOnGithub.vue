<script lang="ts" setup>
const bundle = useAppConfig().bundle

</script>
<template>
  <UButton icon="i-tabler-brand-github" size="xl" target="_blank" variant="outline" color="neutral"
    :to="bundle.links.repository">Star on Github</UButton>
</template>
