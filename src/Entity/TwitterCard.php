<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 10/11/2025, 15:43
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    TwitterCard.php
 * @date    04/11/2025
 * @time    18:25
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Idm\Bundle\Common\Traits\Entity\UuidTrait;
use Idm\Bundle\Seo\Entity\TwitterCard\App;
use Idm\Bundle\Seo\Entity\TwitterCard\Player;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'idm_seo__twitter_card_data')]
#[Assert\Cascade]
class TwitterCard
{
	use UuidTrait;

	#[ORM\Column(type: Types::STRING, length: 25)]
	#[Assert\Choice(choices: ['summary', 'summary_large_image', 'app', 'player'])]
	public string $card = 'summary';

	#[ORM\Column(type: Types::STRING, length: 20)]
	#[Assert\Length(min: 0, maxMessage: 16)]
	#[Assert\When(
		expression : 'this.card == "player" || this.card == "app"',
		constraints: [new Assert\NotBlank(allowNull: false)]
	)]
	#[Assert\AtLeastOneOf([
		new Assert\Regex(pattern: '/^@.{4,15}$/'),
		new Assert\Blank(),
	])]
	#[Assert\NotNull]
	public string $site = '';

	#[ORM\Column(type: Types::STRING, length: 20)]
	#[Assert\AtLeastOneOf([
		new Assert\Regex(pattern: '/^@.{4,15}$/'),
		new Assert\Blank(),
	])]
	#[Assert\NotNull]
	public string $creator = '';

	#[ORM\Column(type: Types::STRING, length: 70)]
	#[Assert\Length(min: 0, max: 70, normalizer: 'trim')]
	#[Assert\NotNull]
	public string $title = '';

	#[ORM\Column(type: Types::STRING, length: 200)]
	#[Assert\Length(min: 0, max: 200)]
	#[Assert\NotNull]
	public string $description = '';

	#[ORM\Column(type: Types::STRING, length: 1000)]
	#[Assert\Length(min: 0, max: 1000)]
	#[Assert\Url(normalizer: 'trim')]
	#[Assert\NotNull]
	#[Assert\When(
		expression : 'this.card == "player"',
		constraints: [new Assert\NotBlank(allowNull: false)]
	)]
	public string $image = '';

	#[ORM\Column(type: Types::STRING, length: 420)]
	#[Assert\Length(min: 0, max: 420)]
	#[Assert\NotNull]
	public string $imageAlt = '';

	#[ORM\Embedded(class: Player::class)]
	public Player $player;

	#[ORM\Embedded(class: App::class)]
	public App $app;

	public function __construct ()
	{
		$this->player = new Player();
		$this->app = new App();
	}
}
