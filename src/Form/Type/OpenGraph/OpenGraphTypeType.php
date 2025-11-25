<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 25/11/2025, 12:29
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    OpenGraphTypeType.php
 * @date    19/11/2025
 * @time    19:11
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Form\Type\OpenGraph;

use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class OpenGraphTypeType extends AbstractType
{
	const array ALL_TYPES = [
		'form.seo.og.type.website'             => 'website',
		'form.seo.og.type.article'             => 'article',
		'form.seo.og.type.book'                => 'book',
		'form.seo.og.type.profile'             => 'profile',
		// Music
		'form.seo.og.type.music.song'          => 'music.song',
		'form.seo.og.type.music.album'         => 'music.album',
		'form.seo.og.type.music.playlist'      => 'music.playlist',
		'form.seo.og.type.music.radio_station' => 'music.radio_station',
		// Video
		'form.seo.og.type.video.movie'         => 'video.movie',
		'form.seo.og.type.video.episode'       => 'video.episode',
		'form.seo.og.type.video.tv_show'       => 'video.tv_show',
		'form.seo.og.type.video.other'         => 'video.other',
	];

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
			'group_by'           => ChoiceList::groupBy($this, function ($choice) {
				if (str_starts_with($choice, 'music.')) {
					return 'form.seo.og.type.music';
				} elseif (str_starts_with($choice, 'video.')) {
					return 'form.seo.og.type.video';
				}

				return null;
			}),
		]);
	}
}
