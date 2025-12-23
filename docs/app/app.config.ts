import bundle from '../bundle.config'

export default defineAppConfig({
  bundle,
  seo: {
    title: bundle.name,
  },
  socials: bundle.socials,
  ui: {
    colors: bundle.colors
  },
  github: {
    rootDir: 'docs'
  },
  toc: {
    bottom: bundle.support_links
  },
})
