import type { BundleConfig } from './bundle.config'

declare module '@nuxt/schema' {
  interface AppConfig {
    bundle: BundleConfig
  }
}
