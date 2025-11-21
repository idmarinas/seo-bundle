<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 21/11/2025, 17:27
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    AppCard.php
 * @date    04/11/2025
 * @time    20:24
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Entity\TwitterCard;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Embeddable]
class AppCard
{
	public const string PATTERN_APPLE  = '/^\d{3,}$/';
	public const string PATTERN_GOOGLE = '/^\w+(?:\.\w+)+$/';

	#[ORM\Column(type: Types::STRING)]
	#[Assert\Length(min: 0, max: 255)]
	#[Assert\NotNull(groups: ['twitter-card-app'])]
	public string $id = '';

	#[ORM\Column(type: Types::STRING)]
	#[Assert\Length(min: 0, max: 255)]
	#[Assert\NotNull(groups: ['twitter-card-app'])]
	public string $name = '';

	#[ORM\Column(type: Types::STRING)]
	#[Assert\Length(min: 0, max: 255)]
	#[Assert\NotNull]
	public string $url = '';

	public static function doGooglePlayIdValidation (AppCard $appCard, ExecutionContextInterface $context): void
	{
		if ('' === $appCard->id) {
			return;
		}

		if (!preg_match(self::PATTERN_GOOGLE, $appCard->id)) {
			$context
				->buildViolation('idm_seo.twitter_card.app_id_google_must_be_alphanumeric')
				->addViolation()
			;
		}
	}

	public static function doAppleIdValidation (AppCard $appCard, ExecutionContextInterface $context): void
	{
		if ('' === $appCard->id) {
			return;
		}

		if (!preg_match(self::PATTERN_APPLE, $appCard->id)) {
			$context
				->buildViolation('idm_seo.twitter_card.app_id_apple_must_be_numeric')
				->addViolation()
			;
		}
	}
}
