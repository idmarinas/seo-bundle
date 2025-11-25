<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 25/11/2025, 18:54
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    AlbumType.php
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
use Idm\Bundle\Seo\Form\OpenGraph\Music\StructuredProperty\SongType;
use Idm\Bundle\Seo\Form\Type\ArrayFieldType;
use Idm\Bundle\Seo\Form\Type\DateTimeFieldType;
use Idm\Bundle\Seo\Form\Type\TextFieldType;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * music:song - music.song - The song on this album.
 * music:song:disc - integer >=1 - The same as music:album:disc but in reverse.
 * music:song:track - integer >=1 - The same as music:album:track but in reverse.
 * music:musician - profile - The musician that made this song.
 * music:release_date - datetime - The date the album was released.
 */
class AlbumType extends AbstractType
{
	/**
	 * @inheritDoc
	 */
	#[Override]
	public function configureOptions (OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'translation_domain' => 'IdmSeoBundle',
			'required'           => true,
			'attr'               => ['class' => 'row'],
		]);
	}

	/**
	 * @inheritDoc
	 */
	#[Override]
	public function buildForm (FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('musician', TextFieldType::class, [
				'label'              => 'entity.og.music.album.musician.label',
				'help'               => 'entity.og.music.album.musician.help',
				'column_css_classes' => 'col-sm-12 col-md-6',
			])
			->add('releaseDate', DateTimeFieldType::class, [
				'label'              => 'entity.og.music.album.release_date.label',
				'help'               => 'entity.og.music.album.release_date.help',
				'column_css_classes' => 'col-sm-12 col-md-6',
			])
			->add('song', ArrayFieldType::class, [
				'label'              => 'entity.og.music.album.song.label',
				'help'               => 'entity.og.music.album.song.help',
				'entry_type'         => SongType::class,
				'column_css_classes' => 'col-12',
			])
		;

		$builder->add('type', HiddenType::class, ['data' => 'music.album']);
		$builder->setDataMapper(new OpenGraphTypeMapper('music.album'));
	}
}
