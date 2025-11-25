<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 25/11/2025, 19:07
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SongType.php
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
 * music:song - music.song - The song on this album.
 * music:song:disc - integer >=1 - The same as music:album:disc but in reverse.
 * music:song:track - integer >=1 - The same as music:album:track but in reverse.
 */
final class SongType extends AbstractType
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
	public function buildForm (FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name', TextFieldType::class, [
				'label'              => false,
				'help'               => 'entity.og.music.album.song.name',
				'column_css_classes' => 'col-sm-12 col-md-4',
			])
			->add('disc', IntegerFieldType::class, [
				'label'              => false,
				'help'               => 'entity.og.music.album.song.disc',
				'constraints'        => [new GreaterThanOrEqual(1),],
				'column_css_classes' => 'col-sm-12 col-md-4',
			])
			->add('track', IntegerFieldType::class, [
				'label'              => false,
				'help'               => 'entity.og.music.album.song.track',
				'constraints'        => [new GreaterThanOrEqual(1),],
				'column_css_classes' => 'col-sm-12 col-md-4',
			])
		;
	}
}
