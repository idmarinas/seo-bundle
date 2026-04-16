<?php
/**
 * Copyright 2026 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 05/01/2026, 17:41
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    ItemCrudController.php
 * @date    05/01/2026
 * @time    17:00
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace App\Controller\Admin;

use Override;
use App\Entity\Item;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\FieldProvider;
use Idm\Bundle\Seo\Traits\Admin\SeoTrait;

final class ItemCrudController extends AbstractCrudController
{
	use SeoTrait;

	public static function getEntityFqcn (): string
	{
		return Item::class;
	}

	#[Override]
    public function configureFields (string $pageName): iterable
	{
		$fields = ($this->container->get(FieldProvider::class)->getDefaultFields($pageName));

		yield FormField::addTab('General')
			->setFormTypeOption('translation_domain', false)
		;

		foreach ($fields as $field) {
			yield $field->setFormTypeOption('translation_domain', false);
		}

		yield DateTimeField::new('date')
			->setFormTypeOption('mapped', false)
			->setFormTypeOption('translation_domain', false)
			->setRequired(false)
		;

		yield from $this->getSeoFields();
	}
}
