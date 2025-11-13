<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 13/11/2025, 13:40
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    OpenGraphLocaleType.php
 * @date    10/11/2025
 * @time    14:15
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Form\Type;

use Override;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Symfony\Component\String\u;

final  class OpenGraphLocaleType extends LocaleType
{
	public function __construct (private readonly ParameterBagInterface $parameterBag) {}

	/**
	 * @inheritDoc
	 */
	#[Override]
	public function configureOptions (OptionsResolver $resolver): void
	{
		parent::configureOptions($resolver);

		$enabledLocales = $this->parameterBag->get('kernel.enabled_locales');

		// Show only enabled locales
		$resolver->setDefaults([
			'choice_filter' => ChoiceList::filter(
				$this,
				fn(?string $locale) => u($locale)->match('/^(' . implode('|', $enabledLocales) . ')(|_).*$/')
			),
		]);
	}
}
