<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 20/11/2025, 12:14
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    TvShowType.php
 * @date    19/11/2025
 * @time    18:44
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Form\OpenGraph\Video;

use Idm\Bundle\Seo\Form\DataMapper\OpenGraphTypeMapper;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * A multi-episode TV show. The metadata is identical to video.movie.
 */
class TvShowType extends AbstractType
{
	/**
	 * @inheritDoc
	 */
	#[Override]
	public function getParent (): string
	{
		return MovieType::class;
	}

	/**
	 * @inheritDoc
	 */
	#[Override]
	public function buildForm (FormBuilderInterface $builder, array $options): void
	{
		$builder->get('type')->setData('video.tv_show');
		$builder->setDataMapper(new OpenGraphTypeMapper('video.tv_show'));
	}
}
