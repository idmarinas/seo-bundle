<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 24/11/2025, 19:58
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SeoCrudController.php
 * @date    06/11/2025
 * @time    17:34
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Idm\Bundle\Seo\Entity\Seo;
use function Idm\Bundle\Seo\t;

final class SeoCrudController extends AbstractCrudController
{
	public static function getEntityFqcn (): string
	{
		return Seo::class;
	}

	public function configureFields (string $pageName): iterable
	{
		// PANEL: Meta Tags
		yield FormField::addColumn('col-12', t('admin.form.tab.meta_tags.label'), 'fa fa-tags')
			->setHelp(t('admin.form.tab.meta_tags.help'))
		;

		yield FormField::addColumn('col-sm-12 col-md-4');
		yield TextField::new('meta.title', t('entity.seo.meta.title.label'))
			->setHelp(t('entity.seo.meta.title.help', ['%max%' => 160]))
			->setEmptyData('')
			->setRequired(true)
			->setFormTypeOption('attr', [
				'maxlength'   => 160,
				'placeholder' => t('entity.seo.meta.title.placeholder'),
			])
		;

		yield UrlField::new('meta.canonical', t('entity.seo.meta.canonical.label'))
			->setHelp(t('entity.seo.meta.canonical.help'))
			->setEmptyData('')
			->setFormTypeOption('attr', [
				'placeholder' => 'https://example.com/page',
			])
		;

		yield FormField::addColumn('col-sm-12 col-md-4');
		yield TextareaField::new('meta.description', t('entity.seo.meta.description.label'))
			->setHelp(t('entity.seo.meta.description.help'))
			->setEmptyData('')
			->setFormTypeOption('attr', [
				'rows'        => 3,
				'placeholder' => t('entity.seo.meta.description.placeholder'),
			])
		;

		yield ChoiceField::new('meta.robots', t('entity.seo.meta.robots.label'))
			->setChoices([
				'Index'          => 'index',
				'No Index'       => 'noindex',
				'Follow'         => 'follow',
				'No Follow'      => 'nofollow',
				'No Archive'     => 'noarchive',
				'No Snippet'     => 'nosnippet',
				'No Image Index' => 'noimageindex',
				'No Translate'   => 'notranslate',
			])
			->allowMultipleChoices()
			->setFormTypeOption('choice_translation_domain', false)
			->setHelp(t('entity.seo.meta.robots.help'))
		;

		yield FormField::addColumn('col-sm-12 col-md-4');
		yield ArrayField::new('meta.keywords', t('entity.seo.meta.keywords.label'))
			->setHelp(t('entity.seo.meta.keywords.help'))
			->setFormTypeOption('error_bubbling', false)
			->setEmptyData([])
		;

		// PANEL: Open Graph
		yield FormField::addColumn('col-sm-12 col-md-6');
		yield FormField::addFieldset(t('admin.form.field_set.og.label'), 'fa fa-share-alt')
			->setHelp(t('admin.form.field_set.og.help'))
		;

		yield AssociationField::new('og', false)
			->renderAsEmbeddedForm(OpenGraphCrudController::class)
		;

		// PANEL: Twitter Card
		yield FormField::addColumn('col-sm-12 col-md-6');
		yield FormField::addFieldset(t('admin.form.field_set.twitter.label'), 'fa-brands fa-x-twitter')
			->setHelp(t('admin.form.field_set.twitter.help'))
		;

		yield AssociationField::new('twitter', false)
			->renderAsEmbeddedForm(TwitterCardCrudController::class)
		;
	}
}
