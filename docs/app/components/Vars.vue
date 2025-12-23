<script setup lang="ts">
const bundle = useAppConfig().bundle
const props = defineProps({
  n: {
    type: String,
    default: ''
  },
  text: {
    type: String,
    default: ''
  }
})

const content = computed((): string => {
  return {
    project: bundle.name,
    package_name: bundle.package_name,
    security_advisories_url: `${bundle.links.repository}/security/advisories`,
    ...bundle.vars,
    '': '',
  }[props.n] ?? ''
})

function isUrl(str: string): boolean {
  try {
    new URL(str)
    return true
  } catch {
    return false
  }
}

</script>
<template>
  <MDC v-if="isUrl(content) && text" :value="`[${text}](${content})`" :tag="false" :unwrap="true" />
  <span v-else>{{ content }}</span>
</template>
