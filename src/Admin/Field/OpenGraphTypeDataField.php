<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 17/11/2025, 19:00
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    OpenGraphTypeDataField.php
 * @date    17/11/2025
 * @time    14:24
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Override;

class OpenGraphTypeDataField implements FieldInterface
{
	use FieldTrait;

	/**
	 * @inheritDoc
	 */
	#[Override]
	public static function new (string $propertyName, /* TranslatableInterface|string|false|null */ $label = null): self
	{
		return (new self())
			->setProperty($propertyName)
			->setLabel($label)
			->setRequired(false)
		;
	}
}
