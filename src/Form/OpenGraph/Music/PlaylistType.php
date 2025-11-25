<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 25/11/2025, 18:50
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    PlaylistType.php
 * @date    19/11/2025
 * @time    18:42
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Form\OpenGraph\Music;

use Idm\Bundle\Seo\Form\DataMapper\OpenGraphTypeMapper;
use Idm\Bundle\Seo\Form\Type\TextFieldType;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * music:song - Identical to the ones on music.album
 * music:song:disc
 * music:song:track
 * music:creator - profile - The creator of this playlist.
 */
class PlaylistType extends AbstractType
{
	/**
	 * @inheritDoc
	 */
	#[Override]
	public function getParent (): string
	{
		return AlbumType::class;
	}

	/**
	 * @inheritDoc
	 */
	#[Override]
	public function buildForm (FormBuilderInterface $builder, array $options): void
	{
		$builder->remove('musician');
		$builder->remove('releaseDate');

		$builder->add('creator', TextFieldType::class, [
			'label'              => 'entity.og.music.playlist.creator.label',
			'help'               => 'entity.og.music.playlist.creator.help',
			'column_css_classes' => 'col-12',
		]);

		$builder->get('type')->setData('music.playlist');
		$builder->setDataMapper(new OpenGraphTypeMapper('music.playlist'));
	}
}
