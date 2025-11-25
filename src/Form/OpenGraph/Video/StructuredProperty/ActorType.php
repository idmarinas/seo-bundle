<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 25/11/2025, 18:30
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    ActorType.php
 * @date    20/11/2025
 * @time    14:12
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Form\OpenGraph\Video\StructuredProperty;

use Idm\Bundle\Seo\Form\Type\TextFieldType;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *  video:actor - profile array - Actors in the movie.
 *  video:actor:role - string - The role they played.
 */
final class ActorType extends AbstractType
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
				'help'               => 'entity.og.video.movie.actor.name.help',
				'column_css_classes' => 'col-sm-12 col-md-6',
			])
			->add('role', TextFieldType::class, [
				'label'              => false,
				'help'               => 'entity.og.video.movie.actor.role.help',
				'column_css_classes' => 'col-sm-12 col-md-6',
			])
		;
	}
}
