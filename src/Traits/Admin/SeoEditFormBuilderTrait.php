<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 24/11/2025, 19:06
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SeoEditFormBuilderTrait.php
 * @date    24/11/2025
 * @time    19:04
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
use Idm\Bundle\Seo\Entity\Seo;
use Symfony\Component\Form\FormInterface;

trait SeoEditFormBuilderTrait
{
	protected function seoEditFormBuilder (EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): void
	{
		/** @var null|Seo $seo */
		$seo = $entityDto->getInstance()?->getSeo();

		if (null === $seo) {
			return;
		}

		$oldCard = $seo->getTwitter()->card;

		$formOptions->set('validation_groups', function (FormInterface $form) use ($oldCard) {
			$groups = ['Default'];

			if (!$form->has('seo')) {
				return $groups;
			}

			$newCard = $form->get('seo')->get('twitter')->get('card')->getData();

			if ('app' == $oldCard && 'app' == $newCard) {
				$groups[] = 'twitter-card-app';
			} elseif ('player' == $oldCard && 'player' == $newCard) {
				$groups[] = 'twitter-card-player';
			}

			return $groups;
		});

		$formOptions->set('error_mapping', [
			'seo.twitter.app.iphone'     => 'seo.twitter.app_iphone_id',
			'seo.twitter.app.ipad'       => 'seo.twitter.app_ipad_id',
			'seo.twitter.app.googleplay' => 'seo.twitter.app_googleplay_id',
		]);
	}
}
