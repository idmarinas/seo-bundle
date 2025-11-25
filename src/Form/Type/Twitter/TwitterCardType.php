<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 25/11/2025, 12:56
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    TwitterCardType.php
 * @date    25/11/2025
 * @time    12:52
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Form\Type\Twitter;

use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TwitterCardType extends AbstractType
{
	const array ALL_TYPES = [
		'form.twitter.type.summary'             => 'summary',
		'form.twitter.type.summary_large_image' => 'summary_large_image',
		'form.twitter.type.app'                 => 'app',
		'form.twitter.type.player'              => 'player',
	];

	/**
	 * @inheritDoc
	 */
	#[Override]
	public function getParent (): string
	{
		return ChoiceType::class;
	}

	/**
	 * @inheritDoc
	 */
	#[Override]
	public function configureOptions (OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'translation_domain' => 'IdmSeoBundle',
			'choice_loader'      => new CallbackChoiceLoader(static fn() => self::ALL_TYPES),
		]);
	}
}
