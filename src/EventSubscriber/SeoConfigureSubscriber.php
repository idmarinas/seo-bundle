<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 09/12/2025, 16:33
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SeoConfigureSubscriber.php
 * @date    01/12/2025
 * @time    18:08
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\EventSubscriber;

use Idm\Bundle\Seo\Attributes\Seo;
use Idm\Bundle\Seo\Attributes\Sitemap;
use Idm\Bundle\Seo\Service\SeoPageInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class SeoConfigureSubscriber implements EventSubscriberInterface
{
	public function __construct(private SeoPageInterface $seo, private ?TranslatorInterface $translator = null) {}

	public static function getSubscribedEvents(): array
	{
		return [
			ControllerArgumentsEvent::class => 'onControllerArgumentsEvent',
		];
	}

	public function onControllerArgumentsEvent(ControllerArgumentsEvent $event): void
	{
		$attributes = $event->getAttributes();
		$routeName = $event->getRequest()->attributes->get('_route', '');

		if (
			!$event->isMainRequest()
			|| $event->isPropagationStopped()
			|| !array_key_exists(Seo::class, $attributes)
		) {
			$this->seo->disableSeo();

			return;
		}

		$locale = $event->getRequest()->attributes->get('_locale', $event->getRequest()->getDefaultLocale());

		$this->seo->setLocale($locale);
		$this->seo->setRouteName($routeName);

		/* @var Seo $seo */
		$seo = $attributes[Seo::class][0];

		$entity = array_find($event->getArguments(), fn($arg) => $arg instanceof $seo->entity);

		if (array_key_exists(Sitemap::class, $attributes)) {
			$this->seo->setSitemap($attributes[Sitemap::class][0]);
		}

		if (!empty($seo->title)) {
			$title = $seo->title;

			if ($title instanceof TranslatableInterface && $this->translator instanceof TranslatorInterface) {
				$title = $title->trans($this->translator, $locale);
			}

			$this->seo->setTitle($title);
		}

		if (!empty($seo->description)) {
			$description = $seo->description;

			if ($description instanceof TranslatableInterface && $this->translator instanceof TranslatorInterface) {
				$description = $description->trans($this->translator, $locale);
			}

			$this->seo->setDescription($description);
		}

		$this->seo->configure($entity);
	}
}
