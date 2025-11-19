<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 19/11/2025, 20:25
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    ArrayFieldType.php
 * @date    19/11/2025
 * @time    16:57
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Form\Type;

use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use Override;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ArrayFieldType extends AbstractFieldType
{
	protected const string FIELD_FQCN = ArrayField::class;
	protected const string TYPE_FQCN  = CollectionType::class;

	/**
	 * @inheritDoc
	 */
	#[Override]
	public function configureOptions (OptionsResolver $resolver): void
	{
		parent::configureOptions($resolver);

		$resolver
			->setDefault('entry_options', ['label' => false,])
			->setDefault('row_attr', ['class' => 'field-array'])
			->setDefault('empty_data', [])
			->setDefault('allow_add', true)
			->setDefault('allow_delete', true)
			->setDefault('delete_empty', true)
		;
	}
}
