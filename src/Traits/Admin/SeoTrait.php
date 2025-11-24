<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 24/11/2025, 19:06
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SeoTrait.php
 * @date    24/11/2025
 * @time    19:01
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Traits\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;

/**
 * @method null|AdminContext getContext()
 */
trait SeoTrait
{
	use SeoCreateEditFormBuilderTrait;
	use SeoCrudControllerTrait;
	use SeoFieldsTrait;
}
