<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 19/11/2025, 20:23
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    DateTimeFieldType.php
 * @date    19/11/2025
 * @time    18:25
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Form\Type;

use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Idm\Bundle\Seo\Form\DataTransformer\StringToDateTimeTransformer;
use Override;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DateTimeFieldType extends AbstractFieldType
{
	protected const string FIELD_FQCN = DateTimeField::class;
	protected const string TYPE_FQCN  = DateTimeType::class;

	/**
	 * @inheritDoc
	 */
	#[Override]
	public function buildForm (FormBuilderInterface $builder, array $options): void
	{
		$builder->addModelTransformer(new StringToDateTimeTransformer());
	}

	/**
	 * @inheritDoc
	 */
	#[Override]
	public function configureOptions (OptionsResolver $resolver): void
	{
		parent::configureOptions($resolver);
		$resolver->setDefault('widget', 'single_text');
	}
}
