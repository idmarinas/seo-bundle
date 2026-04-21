<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "idmarinas" on 13/06/2025, 17:00
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    CheckAttributesValidityPass.php
 * @date    29/05/2025
 * @time    19:56
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\DependencyInjection\Compiler;

use Idm\Bundle\Seo\Attribute\Sitemap\SitemapDynamic;
use Idm\Bundle\Seo\Attribute\Sitemap\SitemapUrl;
use LogicException;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CheckAttributesValidityPass implements CompilerPassInterface
{
	/**
	 * @throws ReflectionException
	 */
	public function process(ContainerBuilder $container): void
	{
		$services = $container->findTaggedServiceIds('controller.service_arguments', true);

		foreach (array_keys($services) as $id) {
			$definition = $container->getDefinition($id);
			if (!class_exists($definition->getClass())) {
				continue;
			}

			$reflection = new ReflectionClass($definition->getClass());

			foreach ($reflection->getMethods() as $method) {
				$hasSitemapUrl = !empty($method->getAttributes(SitemapUrl::class));
				$hasSitemapDynamic = !empty($method->getAttributes(SitemapDynamic::class));

				if ($hasSitemapUrl && $hasSitemapDynamic) {
					throw new LogicException(
						sprintf(
							'The method %s::%s has incompatible attributes: SitemapUrl and SitemapDynamic cannot be used together.',
							$reflection->getName(),
							$method->getName()
						)
					);
				}
			}
		}
	}
}
