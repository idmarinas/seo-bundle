<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 25/11/2025, 16:55
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    TwitterCardCrudController.php
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
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Idm\Bundle\Seo\Entity\TwitterCard;
use Idm\Bundle\Seo\Form\Type\Twitter\TwitterCardType;
use function Idm\Bundle\Seo\t;

final class TwitterCardCrudController extends AbstractCrudController
{
	public static function getEntityFqcn(): string
	{
		return TwitterCard::class;
	}

	public function configureFields(string $pageName): iterable
	{
		// Basic Properties
		yield FormField::addTab(t('entity.twitter.tab.card.label'), 'fa-brands fa-x-twitter')
			->setHelp(t('entity.twitter.tab.card.help'))
		;

		yield ChoiceField::new('card', t('entity.twitter.card.label'))
			->setFormType(TwitterCardType::class)
			->setHelp(t('entity.twitter.card.help'))
			->setEmptyData('summary')
			->setRequired(true)
			->setColumns('col-md-6')
		;

		yield TextField::new('creator', t('entity.twitter.creator.label'))
			->setHelp(t('entity.twitter.creator.help'))
			->setColumns('col-md-6')
			->setRequired(false)
			->setEmptyData('')
			->setFormTypeOption('attr', [
				'placeholder' => t('entity.twitter.creator.placeholder'),
				'maxlength'   => 16,
			])
		;

		yield TextField::new('title', t('entity.twitter.title.label'))
			->setHelp(t('entity.twitter.title.help'))
			->setColumns('col-md-12')
			->setEmptyData('')
			->setRequired(false)
			->setFormTypeOption('attr', [
				'maxlength'   => 70,
				'placeholder' => t('entity.twitter.title.placeholder'),
			])
		;

		yield TextareaField::new('description', t('entity.twitter.description.label'))
			->setHelp(t('entity.twitter.description.help'))
			->setColumns('col-md-12')
			->setEmptyData('')
			->setRequired(false)
			->setFormTypeOption('attr', [
				'rows'        => 3,
				'maxlength'   => 200,
				'placeholder' => t('entity.twitter.description.placeholder'),
			])
		;

		// Imagen
		yield FormField::addTab(t('entity.twitter.tab.image.label'), 'fa fa-image')
			->setHelp(t('entity.twitter.tab.image.help'))
		;

		yield UrlField::new('image', t('entity.twitter.image.label'))
			->setHelp(t('entity.twitter.image.help'))
			->setColumns('col-md-12')
			->setRequired(false)
			->setEmptyData('')
			->setFormTypeOption('attr', [
				'maxlength'   => 1000,
				'placeholder' => t('entity.twitter.image.placeholder'),
			])
		;

		yield TextField::new('imageAlt', t('entity.twitter.image_alt.label'))
			->setHelp(t('entity.twitter.image_alt.help'))
			->setColumns('col-md-12')
			->setRequired(false)
			->setEmptyData('')
			->setFormTypeOption('attr', [
				'maxlength'   => 420,
				'placeholder' => t('entity.twitter.image_alt.placeholder'),
			])
		;

		$entity = $this->getContext()->getEntity()->getInstance();
		// Show only if a card type is PLAYER
		if ('player' == $entity->getSeo()->getTwitter()->card) {
			yield from $this->getPlayer();
		} elseif ('app' == $entity->getSeo()->getTwitter()->card) {
			// Show only if a card type is APP
			yield from $this->getApp();
		}
	}

	/**
	 * App Card Type
	 */
	private function getApp(): iterable
	{
		yield FormField::addTab(t('entity.twitter.tab.app.label'), 'fa fa-mobile')
			->setHelp(t('entity.twitter.tab.app.help'))
		;

		yield FormField::addColumn(12);
		yield CountryField::new('app.country', t('entity.twitter.app.country.label'))
			->setHelp(t('entity.twitter.app.country.help'))
			->setColumns('col-md-12')
			->setFormTypeOption('placeholder', false)
			->setRequired(false)
		;

		// iPhone
		yield from $this->getCardAppFields('iphone');

		// iPad
		yield from $this->getCardAppFields('ipad');

		// Google Play App
		yield from $this->getCardAppFields('googleplay');
	}

	/**
	 * Player Card Type
	 */
	private function getPlayer(): iterable
	{
		yield FormField::addTab(t('entity.twitter.tab.player.label'), 'fa fa-play-circle')
			->setHelp(t('entity.twitter.tab.player.help'))
		;

		yield UrlField::new('player.url', t('entity.twitter.player.url.label'))
			->setHelp(t('entity.twitter.player.url.help'))
			->setColumns('col-md-12')
			->setRequired(false)
			->setEmptyData('')
			->setFormTypeOption('translation_domain', null)
			->setFormTypeOption('attr', [
				'placeholder' => t('entity.twitter.player.url.placeholder'),
			])
		;

		yield IntegerField::new('player.width', t('entity.twitter.player.width.label'))
			->setHelp(t('entity.twitter.player.width.help'))
			->setColumns('col-md-6')
			->setRequired(false)
			->setFormTypeOption('attr', [
				'min' => 262,
			])
		;

		yield IntegerField::new('player.height', t('entity.twitter.player.height.label'))
			->setHelp(t('entity.twitter.player.height.help'))
			->setColumns('col-md-6')
			->setRequired(false)
			->setFormTypeOption('attr', [
				'min' => 262,
			])
		;
	}

	private function getCardAppFields(string $store): iterable
	{
		if (!in_array($store, ['iphone', 'ipad', 'googleplay'])) {
			return;
		}

		// Google Play App
		yield FormField::addFieldset("entity.twitter.app.$store.label");

		yield TextField::new('app.'.$store.'.id', t("entity.twitter.app.$store.id.label"))
			->setHelp(t("entity.twitter.app.$store.id.help"))
			->setColumns('col-md-4')
			->setRequired(false)
			->setEmptyData('')
			->setFormTypeOption('attr', ['placeholder' => t("entity.twitter.app.$store.id.placeholder")])
		;

		yield TextField::new('app.'.$store.'.name', t("entity.twitter.app.$store.name.label"))
			->setHelp(t("entity.twitter.app.$store.name.help"))
			->setColumns('col-md-4')
			->setRequired(false)
			->setEmptyData('')
			->setFormTypeOption('attr', ['placeholder' => t("entity.twitter.app.$store.name.placeholder")])
		;

		yield TextField::new('app.'.$store.'.url', t("entity.twitter.app.$store.url.label"))
			->setHelp(t("entity.twitter.app.$store.url.help"))
			->setColumns('col-md-4')
			->setEmptyData('')
			->setRequired(false)
			->setFormTypeOption('attr', ['placeholder' => t("entity.twitter.app.$store.url.placeholder")])
		;
	}
}
