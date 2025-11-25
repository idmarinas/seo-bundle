<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 25/11/2025, 16:49
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    OpenGraphCrudController.php
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
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\LocaleField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Idm\Bundle\Seo\Admin\Field\OpenGraphTypeDataField;
use Idm\Bundle\Seo\Entity\OpenGraph;
use Idm\Bundle\Seo\Entity\Seo;
use Idm\Bundle\Seo\Form\Type\OpenGraph\OpenGraphTypeType;
use Idm\Bundle\Seo\Form\Type\OpenGraph\SeoLocaleType;
use function Idm\Bundle\Seo\t;
use function Symfony\Component\String\u;

final class OpenGraphCrudController extends AbstractCrudController
{
	public static function getEntityFqcn (): string
	{
		return OpenGraph::class;
	}

	public function configureFields (string $pageName): iterable
	{
		// PANEL: Basic Properties
		yield FormField::addTab(t('entity.og.tab.label'), 'fa fa-info-circle');

		yield ChoiceField::new('type', t('entity.og.type.label'))
			->setFormType(OpenGraphTypeType::class)
			->setHelp(t('entity.og.type.help'))
			->setRequired(true)
			->setColumns('col-md-4')
		;

		yield 'url' => UrlField::new('url', t('entity.og.url.label'))
			->setHelp(t('entity.og.url.help'))
			->setRequired(false)
			->setColumns('col-md-8')
			->setEmptyData('')
			->setFormTypeOption('attr', ['placeholder' => 'https://example.com/page'])
		;

		yield 'title' => TextField::new('title', t('entity.og.title.label'))
			->setHelp(t('entity.og.title.help', ['%max%' => 255]))
			->setRequired(true)
			->setColumns('col-md-12')
			->setEmptyData('')
			->setFormTypeOption('attr', [
				'maxlength'   => 255,
				'placeholder' => t('entity.og.title.placeholder'),
			])
		;

		yield 'description' => TextareaField::new('description', t('entity.og.description.label'))
			->setHelp(t('entity.og.description.help'))
			->setRequired(true)
			->setColumns('col-md-12')
			->setEmptyData('')
			->setFormTypeOption('attr', [
				'rows'        => 3,
				'placeholder' => t('entity.og.description.placeholder'),
			])
		;

		yield 'locale' => LocaleField::new('locale', t('entity.og.locale.label'))
			->setHelp(t('entity.og.locale.help'))
			->setRequired(true)
			->setFormType(SeoLocaleType::class)
			->setColumns('col-md-6')
			->setFormTypeOption('data', $this->getParameter('kernel.default_locale'))
		;

		yield 'locale_alternate' => ArrayField::new('localeAlternate', t('entity.og.locale_alternate.label'))
			->setHelp(t('entity.og.locale_alternate.help'))
			->setFormTypeOption('entry_type', SeoLocaleType::class)
			->setFormTypeOption('entry_options', ['row_attr' => ['class' => 'field-locale']])
			->addCssClass('field-locale')
			->setColumns('col-md-6')
			->setEmptyData([])
		;

		// PANEL: Image (Embeddable)
		yield from $this->structuredProperty('image');

		// PANEL: Video (Embeddable)
		yield from $this->structuredProperty('video');

		// PANEL: Audio (Embeddable)
		yield from $this->structuredProperty('audio');

		// PANEL: Specific Metadata by Type (JSON)
		/** @var null|Seo $seo */
		$seo = $this->getContext()->getEntity()->getInstance()?->getSeo();
		if ('website' != $seo?->getOg()?->type) {
			$type = u($seo->getOg()->type);
			$icon = match (true) {
				$type->equalsTo('article')             => 'fa fa-newspaper',
				$type->equalsTo('book')                => 'fa fa-book',
				$type->equalsTo('profile')             => 'fa fa-user',
				// Music
				$type->equalsTo('music.album')         => 'fa fa-compact-disc',
				$type->equalsTo('music.playlist')      => 'fa fa-file-audio',
				$type->equalsTo('music.radio_station') => 'fa fa-radio',
				$type->startsWith('music.')            => 'fa fa-music',
				// Video
				$type->equalsTo('video.movie')         => 'fa fa-film',
				$type->equalsTo('video.tv_show')       => 'fa fa-tv',
				$type->startsWith('video.')            => 'fa fa-file-video',
				default                                => 'fa fa-database',
			};

			yield FormField::addTab(t("entity.og.type_data.tab.{$seo->getOg()->type}"), $icon)
				->setHelp(t('entity.og.type_data.help'))
			;

			yield OpenGraphTypeDataField::new('typeData', false)
				->setColumns(12)
			;
		}
	}

	private function structuredProperty (string $name): iterable
	{
		$icon = 'audio' == $name ? 'volume-high' : $name;
		yield FormField::addTab(t("entity.og.$name.tab.label"), "fa fa-$icon")
			->setHelp(t("entity.og.$name.tab.help"))
		;

		yield UrlField::new("$name.url", t("entity.og.$name.url.label"))
			->setHelp(t("entity.og.$name.url.help"))
			->setColumns('col-md-6')
			->setEmptyData('')
			->setFormTypeOption('attr', ['placeholder' => t("entity.og.$name.url.placeholder")])
		;

		yield UrlField::new("$name.secureUrl", t("entity.og.$name.secure_url.label"))
			->setHelp(t("entity.og.$name.secure_url.help"))
			->setEmptyData('')
			->setColumns('col-md-6')
		;

		if ('image' == $name) {
			yield TextField::new("$name.alt", t("entity.og.$name.alt.label"))
				->setHelp(t("entity.og.$name.alt.help"))
				->setColumns('col-md-12')
				->setEmptyData('')
				->setFormTypeOption('attr', ['placeholder' => t('entity.og.image.alt.placeholder')])
			;
		}

		yield TextField::new("$name.type", t("entity.og.$name.type.label"))
			->setHelp(t("entity.og.$name.type.help"))
			->setColumns('col-md-6')
			->setEmptyData('')
			->setFormTypeOption('attr', ['placeholder' => t("entity.og.$name.type.placeholder")])
		;

		if ('audio' != $name) {
			yield IntegerField::new("$name.width", t("entity.og.$name.width.label"))
				->setHelp(t("entity.og.$name.width.help"))
				->setColumns('col-md-3')
				->setEmptyData(262)
				->setFormTypeOption('attr', ['min' => 262, 'placeholder' => '1200'])
			;

			yield IntegerField::new("$name.height", t("entity.og.$name.height.label"))
				->setHelp(t("entity.og.$name.height.help"))
				->setColumns('col-md-3')
				->setEmptyData(262)
				->setFormTypeOption('attr', ['min' => 262, 'placeholder' => '630'])
			;
		}
	}
}
