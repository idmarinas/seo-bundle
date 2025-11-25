<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 25/11/2025, 18:45
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    AlbumType.php
 * @date    20/11/2025
 * @time    16:17
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Form\OpenGraph\Music\StructuredProperty;

use Idm\Bundle\Seo\Form\Type\IntegerFieldType;
use Idm\Bundle\Seo\Form\Type\TextFieldType;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

/**
 *  music:album - music.album array - The album this song is from.
 *  music:album:disc - integer >=1 - Which disc of the album this song is on.
 *  music:album:track - integer >=1 - Which track this song is.
 */
final class AlbumType extends AbstractType
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
			->add('name', TextFieldType::class, [
				'label'              => false,
				'help'               => 'entity.og.music.song.album.name',
				'column_css_classes' => 'col-sm-12 col-md-4',
			])
			->add('disc', IntegerFieldType::class, [
				'label'              => false,
				'help'               => 'entity.og.music.song.album.disc',
				'constraints'        => [new GreaterThanOrEqual(1),],
				'column_css_classes' => 'col-sm-12 col-md-4',
			])
			->add('track', IntegerFieldType::class, [
				'label'              => false,
				'help'               => 'entity.og.music.song.album.track',
				'constraints'        => [new GreaterThanOrEqual(1),],
				'column_css_classes' => 'col-sm-12 col-md-4',
			])
		;
	}
}
