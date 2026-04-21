---
title: Seo Tools Bundle
description: Provides tools for SEO optimization in Symfony applications and sitemap generation and management.
seo:
  ogImage:
    component: Bundle
---

::u-page-hero{orientation="horizontal"}
#headline
:last-release{:as-badge=true}

#title
:text-bundle-name

#description
:vars{n="project.description"}

#default
:svg-logo

#links
  :::u-button
  ---
  color: neutral
  size: xl
  to: /getting-started/installation
  trailing-icon: i-tabler-arrow-right
  ---
  Get started
  :::

:button-star-on-github
::

::u-page-c-t-a{orientation="horizontal" :reverse="true"}
#title
Support Me

#description
  ::note
  **Support me** 🩵 If you like this project, give it a 🌟 and share it with your friends!
  ::

#default
:svg-ship{width="320" height="364" alt="Illustration" class="w-full rounded-lg"}

#links
  :::u-button
  ---
  icon: i-simple-icons-paypal
  to: https://www.paypal.me/idmarinas
  target: _blank
  ---
  Help My Projects
  :::

  :::u-button
  ---
  icon: i-simple-icons-github
  color: purple
  to: https://github.com/sponsors/idmarinas
  target: _blank
  ---
  Sponsor
  :::
::

::u-page-section
#title
Easily add SEO tools to your Symfony application.

#description
Optimize your website for search engines with features such as automatic meta tags, sitemap generation, breadcrumb navigation.


#features
  :::u-page-feature
  ---
  icon: i-tabler-settings
  target: _blank
  to: https://www.github.com/idmarinas/seo-bundle
  ---
  #title
  Built your bundle with [:vars{n="project"}]{.text-primary}

  #description
  Create your Symfony Bundle with this Template
  :::
::
