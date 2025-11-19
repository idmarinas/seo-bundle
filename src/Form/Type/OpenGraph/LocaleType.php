<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 19/11/2025, 16:22
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    LocaleType.php
 * @date    10/11/2025
 * @time    14:15
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Form\Type\OpenGraph;

use Override;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\LocaleType as BaseLocaleType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Symfony\Component\String\u;

final  class LocaleType extends BaseLocaleType
{
	private string $pattern;

	public function __construct (readonly array $enabledLocales)
	{
		$this->pattern = '/^(' . implode('|', $enabledLocales) . ')(|_).*$/';
	}

	/**
	 * @inheritDoc
	 */
	#[Override]
	public function configureOptions (OptionsResolver $resolver): void
	{
		parent::configureOptions($resolver);

		// Show only enabled locales
		$resolver->setDefaults([
			'choice_filter' => ChoiceList::filter($this, fn(?string $locale) => u($locale)->match($this->pattern)),
		]);
	}
}
