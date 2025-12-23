<script setup>
import { computed } from "vue";
import bundle from '~~/bundle.config'

const bundle = useAppConfig().bundle

const props = defineProps({
  title: { type: String, required: false, default: "title" },
  description: { type: String, required: false, default: "description" },
  headline: { type: String, required: false, default: "headline" },
  socials: { type: Object, required: false, default: () => ({ icons: [], username: "" }) }
});

const title = computed(() => (props.title || "").slice(0, 60));
const description = computed(() => (props.description || "").slice(0, 200));
const socials = computed(() => {
  const socials = props.socials || { icons: [], username: "" }

  socials.icons = new Set(socials.icons)

  return socials
})
</script>

<template>
  <div class="flex flex-col justify-center w-full h-full bg-[#020420] p-[100px]">
    <div class="flex flex-row gap-5">
      <div class="flex-1 flex flex-col justify-center items-start">
        <p v-if="props.headline" class="uppercase text-[24px] text-blue-500 mb-4 font-semibold">
          {{ props.headline }}
        </p>
        <h1 v-if="title" class="w-[600px] m-0 text-[75px] font-semibold mb-4 text-white"
          style="display: block; line-clamp: 2; text-overflow: ellipsis;">
          {{ title }}
        </h1>
        <p v-if="description" class="text-[32px] text-[#E4E4E7] leading-tight"
          style="display: block; line-clamp: 3; text-overflow: ellipsis;">
          {{ description }}
        </p>
      </div>

      <div class="w-[340px] flex flex-col items-center justify-center gap-0 text-blue-300 dark:text-blue-900">
        <div class="text-4xl -mb-8">{{ bundle.author }}</div>
        <SvgShip width="340" height="340" class="opacity-65" />
        <span class="text-5xl -mt-24">{{ bundle.short_name }}</span>
        <div class="flex items-center justify-center gap-4 mt-1">
          <UIcon name="i-tabler-brand-symfony" mode="svg" class="w-12 h-12" />
          <span class="text-3xl">Bundle</span>
        </div>
      </div>
    </div>

    <div v-if="socials.username || !socials.icons.length" class="flex flex-row justify-end items-center gap-4">
      <p v-if="socials.username" class="flex-initial text-white text-[24px] font-bold">@{{ socials.username }}</p>
      <UIcon v-for="(icon, key) in socials.icons" :key="key" :name="icon" class="w-7 h-7 text-white" mode="svg" />
    </div>
  </div>
</template>
