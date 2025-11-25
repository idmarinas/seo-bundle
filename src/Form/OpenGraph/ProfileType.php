<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 25/11/2025, 18:13
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    ProfileType.php
 * @date    19/11/2025
 * @time    18:08
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Form\OpenGraph;

use Idm\Bundle\Seo\Form\DataMapper\OpenGraphTypeMapper;
use Idm\Bundle\Seo\Form\Type\TextFieldType;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * profile:first_name - string - A name normally given to an individual by a parent or self-chosen.
 * profile:last_name - string - A name inherited from a family or marriage and by which the individual is commonly known
 * profile:username - string - A short unique string to identify them.
 * profile:gender - enum(male, female) - Their gender.
 */
final class ProfileType extends AbstractType
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
			->add('firstName', TextFieldType::class, [
				'label'              => 'entity.og.profile.first_name.label',
				'help'               => 'entity.og.profile.first_name.help',
				'column_css_classes' => 'col-sm-12 col-md-6',
			])
			->add('lastName', TextFieldType::class, [
				'label'              => 'entity.og.profile.last_name.label',
				'help'               => 'entity.og.profile.last_name.help',
				'column_css_classes' => 'col-sm-12 col-md-6',
			])
			->add('username', TextFieldType::class, [
				'label'              => 'entity.og.profile.username.label',
				'help'               => 'entity.og.profile.username.help',
				'column_css_classes' => 'col-sm-12 col-md-6',
			])
			->add('gender', TextFieldType::class, [
				'label'              => 'entity.og.profile.gender.label',
				'help'               => 'entity.og.profile.gender.help',
				'column_css_classes' => 'col-sm-12 col-md-6',
			])
			->add('type', HiddenType::class, ['data' => 'profile',])
		;

		$builder->setDataMapper(new OpenGraphTypeMapper('profile'));
	}
}
