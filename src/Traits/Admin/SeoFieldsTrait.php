<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 24/11/2025, 19:16
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SeoFieldsTrait.php
 * @date    24/11/2025
 * @time    19:03
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Traits\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use Idm\Bundle\Seo\Controller\Admin\SeoCrudController;
use function Idm\Bundle\Seo\t;

trait SeoFieldsTrait
{
	protected function getSeoFields (): iterable
	{
		// Shows whether the entity has SEO.
		yield 'has_seo' => BooleanField::new('hasSeo', t('admin.index.has_seo'))
			->setVirtual(true)
			->renderAsSwitch(false)
			->onlyOnIndex()
		;

		// Add the SEO field if the entity has SEO.
		if ($this->getContext()->getEntity()->getInstance()?->hasSeo()) {
			yield 'form_field_tab_seo' => FormField::addTab(t('admin.form.tab.seo'), 'fa fa-hashtag');

			yield 'seo' => AssociationField::new('seo', false)
				->renderAsEmbeddedForm(SeoCrudController::class)
				->setColumns(12)
				->setFormTypeOption('required', false)
				->setTemplatePath('@IdmSeo/crud/field/seo.html.twig')
				->hideOnIndex()
			;
		}
	}
}
