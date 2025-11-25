<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 25/11/2025, 18:47
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SongType.php
 * @date    19/11/2025
 * @time    18:43
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Form\OpenGraph\Music;

use Idm\Bundle\Seo\Form\DataMapper\OpenGraphTypeMapper;
use Idm\Bundle\Seo\Form\OpenGraph\Music\StructuredProperty\AlbumType;
use Idm\Bundle\Seo\Form\Type\ArrayFieldType;
use Idm\Bundle\Seo\Form\Type\IntegerFieldType;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

/**
 * music:duration - integer >=1 - The song's length in seconds.
 * music:album - music.album array - The album this song is from.
 * music:album:disc - integer >=1 - Which disc of the album this song is on.
 * music:album:track - integer >=1 - Which track this song is.
 * music:musician - profile array - The musician that made this song.
 */
class SongType extends AbstractType
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
			->add('album', ArrayFieldType::class, [
				'label'              => 'entity.og.music.song.album.label',
				'help'               => 'entity.og.music.song.album.help',
				'entry_type'         => AlbumType::class,
				'column_css_classes' => 'col-12',
			])
			->add('musician', ArrayFieldType::class, [
				'label'              => 'entity.og.music.song.musician.label',
				'help'               => 'entity.og.music.song.musician.help',
				'column_css_classes' => 'col-sm-12 col-md-6',
			])
			->add('duration', IntegerFieldType::class, [
				'label'              => 'entity.og.music.song.duration.label',
				'help'               => 'entity.og.music.song.duration.help',
				'constraints'        => [new GreaterThanOrEqual(1),],
				'column_css_classes' => 'col-sm-12 col-md-6',
			])
		;

		$builder->add('type', HiddenType::class, ['data' => 'music.song']);
		$builder->setDataMapper(new OpenGraphTypeMapper('music.song'));
	}
}
