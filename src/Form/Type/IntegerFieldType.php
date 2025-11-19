<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 19/11/2025, 20:23
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    IntegerFieldType.php
 * @date    19/11/2025
 * @time    20:06
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Form\Type;

use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

final class IntegerFieldType extends AbstractFieldType
{
	protected const string FIELD_FQCN = IntegerField::class;
	protected const string TYPE_FQCN  = IntegerType::class;
}
