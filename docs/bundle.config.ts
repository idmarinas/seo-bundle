import type { BadgeProps, UserProps, TooltipProps } from '@nuxt/ui';

export interface LabelProps extends BadgeProps {
  tooltip?: TooltipProps;
}

export interface Author extends UserProps {
  name: string;
  description: string;
  avatar: {
    src: string;
  };
  to: string;
}

export interface BundleConfig {
  name: string;
  short_name: string;
  description: string;
  author: string;
  package_name: string;
  repository: {
    name: string;
    owner: string;
  };
  socials: Record<string, string>;
  socialsOnlyIcons: (extra?: string[]) => string[];
  colors: Record<string, string>;
  labels: Record<string, Record<string, LabelProps> | LabelProps>;
  vars: Record<string, string | number | boolean>;
  links: Record<string, string> & { repository: string };
  support_links: { title: string, links: object[] };
}

// Author definition (default author)
const idmarinas: Author = {
  name: 'Iván Diaz',
  description: '@IDMarinas',
  avatar: { src: 'https://avatars.githubusercontent.com/u/35842929?v=4' },
  to: 'https://github.com/idmarinas',
  target: '_blank'
}

// Map of authors by username
const authors: Record<string, Author> = {
  idmarinas,
  author: idmarinas,
  developer: idmarinas,
  maintainer: idmarinas,
  default: idmarinas,
}

const defaultTooltip: TooltipProps = {
  arrow: true,
  delayDuration: 300,
}

const list = [
  //-- new versions here
  '1.0'
]
const versions = Object.fromEntries(list.map((version, index) => [
  `v${version.replace('.', '_')}`,
  {
    label: version,
    color: 0 === index ? 'primary' : 'secondary',
    icon: 'i-tabler-tag',
    tooltip: {
      ...defaultTooltip,
      text: `New in version ${version}`,
    }
  }
])
)

const bundleDefault: BundleConfig = {
  name: 'IDMarinas SEO Bundle',
  description: 'Provides tools for SEO optimization in Symfony applications and sitemap generation and management.',
  author: 'IDMarinas',
  package_name: 'idmarinas/seo-bundle',
  colors: {
    primary: 'blue',
    secondary: 'cyan',
    purple: 'purple',
  },
  labels: {
    versions,
    wip: {
      label: 'WIP',
      color: 'purple',
      icon: 'i-tabler-progress-bolt',
      tooltip: {
        ...defaultTooltip,
        text: 'Work in progress',
      }
    },
    beta: {
      label: 'β',
      color: 'purple',
      icon: 'i-tabler-beta',
      tooltip: {
        ...defaultTooltip,
        text: 'Beta version',
      }
    }
  },
  socials: {
    x: 'https://x.com/idmarinas',
    reddit: 'https://reddit.com/u/idmarinas',
    paypal: 'https://www.paypal.me/idmarinas',
    bitly: 'https://bit.ly/m/idmarinas',
    githubsponsors: 'https://github.com/sponsors/idmarinas'
  },
  support_links: {
    title: 'Support me',
    links: [
      {
        icon: 'i-tabler-brand-paypal',
        label: 'PayPal.Me',
        to: 'https://www.paypal.me/idmarinas',
        target: '_blank'
      },
      {
        icon: 'i-tabler-brand-github',
        label: 'GitHub Sponsor',
        to: 'https://github.com/sponsors/idmarinas',
        target: '_blank'
      }
    ]
  },
  short_name: '',
  repository: { name: '', owner: '' },
  socialsOnlyIcons: () => [],
  vars: {},
  links: { repository: '' },
}

export const bundle: BundleConfig = {
  ...bundleDefault,
  short_name: ((): string => {
    const firstIndex = bundleDefault.name.indexOf(' ')
    const lastIndex = bundleDefault.name.lastIndexOf(' ')

    return bundleDefault.name.slice(firstIndex + 1, lastIndex)
  })(),
  repository: (() => {
    const package_name = bundleDefault.package_name.trim()
    let name = ''
    let owner = ''
    if (package_name.includes('/')) {
      const index = package_name.indexOf('/')
      owner = package_name.slice(0, index)
      name = package_name.slice(index + 1, package_name.length)
    }

    return { name, owner }
  })(),
  vars: {
    ...bundleDefault.vars,
    namespace: (() => {
      const firstIndex = bundleDefault.name.indexOf(' ')
      const lastIndex = bundleDefault.name.lastIndexOf(' ')
      let vendor = bundleDefault.name.slice(0, firstIndex).toLocaleLowerCase()
      vendor = vendor === 'idmarinas' ? 'Idm' : vendor
      const name = bundleDefault.name.slice(firstIndex + 1, lastIndex)

      const capitalized = name.charAt(0).toUpperCase() + name.slice(1).toLocaleLowerCase()

      return `Idm\\Bundle\\${capitalized}\\${vendor}${capitalized}Bundle`
    })(),
  },
  links: {
    ...bundleDefault.links,
    repository: (() => `https://github.com/${bundleDefault.package_name}`)(),
  },
  socialsOnlyIcons: (extra = []): string[] => {
    const icons = new Set(Object.keys(bundle.socials))
    extra.forEach(v => !icons.has(v) && icons.add(v))

    return Array.from(icons).map(key => `i-simple-icons-${key}`)
  },
}

export const getAuthorByUserName = (userName: string): undefined | Author => {
  return authors[userName] ?? undefined
}

export default bundle;
