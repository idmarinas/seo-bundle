<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 03/11/2025, 17:08
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SeoPage.php
 * @date    03/11/2025
 * @time    15:35
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Service;

use Idm\Bundle\Seo\Provider\SeoMeta;
use Idm\Bundle\Seo\Traits\Service\SeoPage\AutoConfigureTrait;
use Idm\Bundle\Seo\Traits\Service\SeoPage\ConfigureTrait;
use Idm\Bundle\Seo\Traits\Service\SeoPage\SanitizerTrait;
use Idm\Bundle\Seo\Traits\Twig\RunTime\SeoRuntime\BuildTagsTrait;
use ReflectionException;

final class SeoPage implements SeoPageInterface
{
	use AutoConfigureTrait;
	use ConfigureTrait;
	use BuildTagsTrait;
	use SanitizerTrait;

	private readonly SeoMeta $seo;
	private array            $localeAlternate;
	private bool             $enabled = true;

	public function __construct (array $config, private readonly RouterGenerateSeoUrl $router)
	{
		$this->seo = new SeoMeta($config);
		$this->localeAlternate = array_fill_keys(
			array_values(array_intersect($config['supported_locales'], $config['enabled_locales'])),
			''
		);
	}

	public function getFormatedTitle (string $type): string
	{
		return $this->seo->getFormatedTitle($type, $this->getTitle());
	}

	/**
	 * @throws ReflectionException
	 */
	public function getMetaTags (): array
	{
		$tags = $this->seo->meta->toArray();
		array_walk($tags, function (&$value, $key): void {
			if (empty($value)) {
				$value = match ($key) {
					'title'       => $this->getTitle(),
					'description' => $this->getDescription(),
					default       => ''
				};
			}
		});

		return $tags;
	}

	/**
	 * @throws ReflectionException
	 */
	public function getOpenGraphTags (): array
	{
		$tags = $this->seo->og->toArray();
		array_walk($tags, function (&$value, $key): void {
			if (empty($value)) {
				$value = match ($key) {
					'title'            => $this->getTitle(),
					'description'      => $this->getDescription(),
					'url'              => $this->getCanonical(),
					'locale'           => $this->locale,
					'locale:alternate' => array_keys($this->localeAlternate),
					default            => ''
				};
			}
		});

		return $tags;
	}

	/**
	 * @throws ReflectionException
	 */
	public function getTwitterTags (): array
	{
		return $this->seo->twitter->toArray();
	}

	public function getTitle (): string
	{
		return (string)$this->title ?: $this->seo->meta->title->default;
	}

	public function getDescription (): string
	{
		return (string)$this->description ?: $this->seo->meta->description;
	}

	public function getCanonical (): string
	{
		return (string)$this->canonical;
	}

	public function getLocaleAlternate (): array
	{
		return $this->localeAlternate;
	}

	public function disableSeo (): void
	{
		$this->enabled = false;
	}

	public function isEnabled (): bool
	{
		return $this->enabled;
	}

	public function enableSeo (): void
	{
		$this->enabled = true;
	}
}
