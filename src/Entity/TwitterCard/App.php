<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 06/11/2025, 11:21
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    App.php
 * @date    04/11/2025
 * @time    20:03
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

#[ORM\Embeddable]
#[Assert\Cascade]
class App
{
	#[ORM\Embedded(class: AppCard::class)]
	public AppCard $iphone;

	#[ORM\Embedded(class: AppCard::class)]
	public AppCard $ipad;

	#[ORM\Embedded(class: AppCard::class)]
	public AppCard $googleplay;

	#[ORM\Column(type: Types::STRING, length: 2)]
	#[Assert\Country]
	#[Assert\NotNull]
	public string $country = '';

	public function __construct ()
	{
		$this->iphone = new AppCard();
		$this->ipad = new AppCard();
		$this->googleplay = new AppCard();
	}
}
