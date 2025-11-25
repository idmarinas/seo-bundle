<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 25/11/2025, 18:39
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    RadioStationType.php
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
use Idm\Bundle\Seo\Form\Type\TextFieldType;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * music:creator - profile - The creator of this station.
 */
class RadioStationType extends AbstractType
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
		$builder->add('creator', TextFieldType::class, [
			'label'              => 'entity.og.music.radio_station.creator.label',
			'help'               => 'entity.og.music.radio_station.creator.help',
			'column_css_classes' => 'col-12',
		]);

		$builder->add('type', HiddenType::class, ['data' => 'music.radio_station']);
		$builder->setDataMapper(new OpenGraphTypeMapper('music.radio_station'));
	}
}
