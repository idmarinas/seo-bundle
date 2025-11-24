<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 24/11/2025, 19:06
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SeoCreateEditFormBuilderTrait.php
 * @date    24/11/2025
 * @time    18:59
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Traits\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use Override;
use Symfony\Component\Form\FormBuilderInterface;

trait SeoCreateEditFormBuilderTrait
{
	use SeoEditFormBuilderTrait;

	#[Override]
	public function createEditFormBuilder (
		EntityDto     $entityDto,
		KeyValueStore $formOptions,
		AdminContext  $context
	): FormBuilderInterface {
		$this->seoEditFormBuilder($entityDto, $formOptions, $context);

		return parent::createEditFormBuilder($entityDto, $formOptions, $context);
	}
}
