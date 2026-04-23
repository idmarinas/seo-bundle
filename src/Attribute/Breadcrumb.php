<?php
/**
 * Copyright 2026 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 17/04/2026, 16:41
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    Breadcrumb.php
 * @date    17/04/2026
 * @time    16:41
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Attribute;

use Attribute;
use Symfony\Contracts\Translation\TranslatableInterface;

/**
 * Marks a Controller method as a breadcrumb node.
 *
 * Basic usage:
 *   #[Breadcrumb(label: 'Index', parent: 'web_index')]
 *
 * With translatable label:
 *   #[Breadcrumb(label: new TranslatableMessage('breadcrumb.legal.index'), parent: 'web_home')]
 *
 * With dynamic parameter in the label:
 *   #[Breadcrumb(label: new TranslatableMessage('breadcrumb.game', ['{slug}' => '{slug}']), parent: 'web_home')]
 *   → the {slug} token is resolved with the current request's _route_params.
 */
#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Breadcrumb
{
	/**
	 * @param string|TranslatableInterface $label  Visible label. In strings, accepts {param} tokens that are resolved
	 *                                             with the current route parameters.
	 *                                             Predefined tokens:
	 *                                             - {seoTitle} This resolves to the current page's SEO title.'
	 * @param string|null                  $icon   Name of the icon to use with UX Icons
	 * @param string|null                  $parent Parent route name.
	 *                                             - null = root node (Home is added automatically).
	 * @param bool                         $link   true  → linkable when not the active node.
	 *                                             false → only text, no link (leaf pages).
	 */
	public function __construct(
		public string|TranslatableInterface $label,
		public ?string                      $icon = null,
		public ?string                      $parent = null,
		public bool                         $link = true,
	) {}
}
