<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 08/12/2025, 11:57
 *
 * @project IDMarinas Seo Bundle
 * @see https://github.com/idmarinas/seo-bundle
 *
 * @file TwitterCard.php
 * @date 04/11/2025
 * @time 18:25
 *
 * @author Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since 1.0.0
 */

namespace Idm\Bundle\Seo\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Idm\Bundle\Common\Traits\Entity\UuidTrait;
use Idm\Bundle\Seo\Entity\TwitterCard\App;
use Idm\Bundle\Seo\Entity\TwitterCard\Player;
use Stringable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'idm_seo__twitter_card_data')]
#[Assert\Cascade]
class TwitterCard implements Stringable
{
	use UuidTrait;

	#[ORM\Column(type: Types::STRING, length: 25)]
	#[Assert\Choice(choices: ['summary', 'summary_large_image', 'app', 'player'])]
	public string $card = 'summary';

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
	#[Assert\Length(min: 0, max: 1000, normalizer: 'trim')]
	#[Assert\NotNull]
	public string $site = '';

	#[Assert\Url(normalizer: 'trim')]
	#[Assert\NotNull]
	#[Assert\NotBlank(allowNull: false, groups: ['twitter-card-player'])]
	public string $image = '';

	#[ORM\Column(type: Types::STRING, length: 420)]
	#[Assert\Length(min: 0, max: 420, normalizer: 'trim')]
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

	public function __toString (): string
	{
		return $this->id;
	}
}
