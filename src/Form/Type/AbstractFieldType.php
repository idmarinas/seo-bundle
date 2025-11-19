<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 19/11/2025, 20:22
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    AbstractFieldType.php
 * @date    19/11/2025
 * @time    20:10
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Form\Type;

use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FormVarsDto;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractFieldType extends AbstractType
{
	protected const string FIELD_FQCN = '';
	protected const string TYPE_FQCN  = '';

	/**
	 * @inheritDoc
	 */
	#[Override]
	public function getParent (): string
	{
		return static::TYPE_FQCN;
	}

	/**
	 * @inheritDoc
	 */
	#[Override]
	public function configureOptions (OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'empty_data'         => '',
			'column_css_classes' => 'col-md-12',
		]);

		$resolver->setAllowedTypes('column_css_classes', ['string']);
	}

	/**
	 * @inheritDoc
	 */
	#[Override]
	public function finishView (FormView $view, FormInterface $form, array $options): void
	{
		$field = new FieldDto();
		$field->setFieldFqcn(static::FIELD_FQCN);

		if (is_string($options['column_css_classes'])) {
			$field->setColumns($options['column_css_classes']);
		}

		$view->vars['ea_vars'] = new FormVarsDto($field);
	}
}
