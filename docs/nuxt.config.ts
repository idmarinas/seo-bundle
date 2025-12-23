import type { FileAfterParseHook } from '@nuxt/content'
import { bundle, getAuthorByUserName } from './bundle.config'
import type { PageMeta } from 'nuxt/app'
import { createResolver } from '@nuxt/kit'

const { resolve } = createResolver(import.meta.url)

export default defineNuxtConfig({
  modules: ['nuxt-seo-utils'],
  site: {
    url: `https://${bundle.repository.owner}.github.com`,
    name: bundle.name,
    description: bundle.description,
  },
  seo: {
    meta: {
      title: bundle.name,
      description: bundle.description,
      twitterCreator: `@${bundle.repository.owner} `
    }
  },
  ui: {
    theme: {
      colors: (() => {
        const colors = new Set(['primary', 'secondary', 'success', 'info', 'warning', 'error'])
        Object.keys(bundle.colors).forEach(color => !colors.has(color) && colors.add(color))

        return Array.from(colors)
      })()
    }
  },
  ogImage: {
    defaults: {
      component: 'Docs',
      props: {
        headline: bundle.name,
        description: bundle.description,
        socials: {
          icons: bundle.socialsOnlyIcons(['github']),
          username: bundle.repository.owner
        }
      }
    }
  },
  hooks: {
    'pages:resolved'(pages: PageMeta[]) {
      const exclude = ['index', 'lang-index']
      const landingTemplate = resolve('./app/templates/landing.vue')

      pages.map(page => {
        if (exclude.includes(page.name!) && (page.file as string).endsWith('docus/app/templates/landing.vue')) {
          page.file = landingTemplate
        }
      })
    },
    'content:file:afterParse'(ctx: FileAfterParseHook) {
      if (ctx.collection.name.startsWith('changelog_v')) {
        if (ctx.content.authors === undefined || Array.isArray(ctx.content.authors) && ctx.content.authors.length === 0) {
          ctx.content.authors = [getAuthorByUserName('idmarinas')]
        } else if (typeof ctx.content.authors === 'string') {
          const author = getAuthorByUserName(ctx.content.authors)
          ctx.content.authors = author ? [author] : []
        } else if (Array.isArray(ctx.content.authors)) {
          for (const [index, author] of ctx.content.authors.entries()) {
            if (typeof author === 'string') {
              const obj = getAuthorByUserName(author)
              if (obj) {
                ctx.content.authors[index] = obj
              } else {
                ctx.content.authors.splice(index, 1)
              }
            }
          }
        }
      }
    }
  },
})
