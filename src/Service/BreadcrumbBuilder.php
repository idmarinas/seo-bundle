<?php
/**
 * Copyright 2026 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 30/03/2026, 19:14
 *
 * @project InfoMMO
 * @see     https://github.com/idmarinas/infommo
 *
 * @file    BreadcrumbBuilder.php
 * @date    30/03/2026
 * @time    19:09
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license proprietary
 *
 * @since   4.0.0
 */

namespace Idm\Bundle\Seo\Service;

use Exception;
use Idm\Bundle\Seo\Attribute\Breadcrumb;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use function explode;
use function in_array;
use function preg_replace_callback;
use function str_contains;

/**
 * Builds the breadcrumb chain by traversing the #[Breadcrumb] attributes
 * of the Controllers, following the `parent` chain to the root.
 *
 * The root node (Home, route 'web_home') is automatically prepended
 * unless the current route is Home itself.
 */
final readonly class BreadcrumbBuilder
{
	public function __construct(
		private RouterInterface     $router,
		private RequestStack        $requestStack,
		private TranslatorInterface $translator,
		private array               $itemHome,
	) {}

	/**
	 * @return list<array{label: string, url: string|null, current: bool, icon: string|null}>
	 */
	public function build(): array
	{
		$request = $this->requestStack->getCurrentRequest();
		$routeName = $request?->attributes->get('_route');

		if (!$routeName) {
			return [];
		}

		$routeParams = $request->attributes->get('_route_params', []);
		$items = [];
		$visited = [];

		$this->collectAncestors($routeName, $routeParams, $items, $visited, isLeaf: true);

		$homeRoute = $this->itemHome['route'];
		// Home always leads the breadcrumb (unless it's already the current route)
		if ($routeName !== $homeRoute && !in_array($homeRoute, $visited, true)) {
			$attr = $this->getBreadcrumbAttr($homeRoute);

			array_unshift($items, [
				'label'   => $this->resolveLabel($attr ? $attr->label : $this->itemHome['label'], $routeParams),
				'url'     => $this->router->generate($homeRoute),
				'current' => false,
				'icon'    => $attr ? $attr->icon : $this->itemHome['icon'],
			]);
		}

		return $items;
	}

	/**
	 * Ascending recursion: inserts the parent first, then the current node.
	 */
	private function collectAncestors(
		string $routeName,
		array  $routeParams,
		array  &$items,
		array  &$visited,
		bool   $isLeaf = false,
	): void {
		if (in_array($routeName, $visited, true)) {
			return; // protection against cycles
		}
		$visited[] = $routeName;

		$attr = $this->getBreadcrumbAttr($routeName);
		if (!$attr) {
			return;
		}

		// First the parent (recursion)
		if ($attr->parent) {
			$this->collectAncestors($attr->parent, $routeParams, $items, $visited);
		}

		// Then this node
		$items[] = [
			'label'   => $this->resolveLabel($attr->label, $routeParams),
			'url'     => (!$isLeaf && $attr->link)
				? $this->tryGenerate($routeName, $routeParams)
				: null,
			'current' => $isLeaf,
			'icon'    => $attr->icon,
		];
	}

	/**
	 * Gets the #[Breadcrumb] attribute from the Controller action associated with the route.
	 */
	private function getBreadcrumbAttr(string $routeName): ?Breadcrumb
	{
		$route = $this->router->getRouteCollection()->get($routeName);
		$controller = $route?->getDefault('_controller');

		if (!$controller || !str_contains($controller, '::')) {
			return null;
		}

		[$class, $method] = explode('::', $controller, 2);

		try {
			$ref = new ReflectionMethod($class, $method);
			/** @var Breadcrumb[]|null $attrs */
			$attrs = $ref->getAttributes(Breadcrumb::class);

			return $attrs ? $attrs[0]->newInstance() : null;
		} catch (ReflectionException) {
			return null;
		}
	}

	/**
	 * Resolves the label: translates TranslatableInterface and substitutes {param} tokens.
	 */
	private function resolveLabel(
		string|TranslatableInterface $label,
		array                        $params
	): string {
		// Translation
		$text = $label instanceof TranslatableInterface
			? $label->trans($this->translator)
			: $label;

		// Substitution of {param} tokens with the actual route value
		return preg_replace_callback('#{(\w+)}#', static function ($m) use ($params) {
			return $params[$m[1]] ?? $m[0];
		}, $text);
	}

	/**
	 * Generates the URL of a route without throwing an exception if parameters are missing.
	 */
	private function tryGenerate(string $routeName, array $params): ?string
	{
		try {
			return $this->router->generate($routeName, $params);
		} catch (Exception) {
			return null;
		}
	}
}
