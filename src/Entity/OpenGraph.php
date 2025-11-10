<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 10/11/2025, 14:57
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    OpenGraph.php
 * @date    05/11/2025
 * @time    11:21
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
use Idm\Bundle\Seo\Entity\OpenGraph\StructuredProperty;
use LogicException;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'idm_seo__open_graph_data')]
#[Assert\Cascade]
class OpenGraph
{
	use UuidTrait;

	// Basic types
	const array TYPE_BASIC = ['website', 'article', 'book', 'profile'];

	// Music types
	const array TYPE_MUSIC = ['music.song', 'music.album', 'music.playlist', 'music.radio_station'];

	// Video types
	const array TYPE_VIDEO = ['video.movie', 'video.episode', 'video.tv_show', 'video.other'];

	const array TYPE_ALL = self::TYPE_BASIC + self::TYPE_MUSIC + self::TYPE_VIDEO;

	// Basic Properties (required for all)

	#[ORM\Column]
	#[Assert\Choice(choices: self::TYPE_ALL)]
	#[Assert\NotBlank]
	public string $type = 'website';

	#[ORM\Column]
	#[Assert\Length(min: 1, max: 255)]
	#[Assert\NotBlank]
	public string $title = '';

	#[ORM\Column(type: Types::TEXT)]
	#[Assert\NotBlank]
	public string $description = '';

	#[ORM\Column]
	#[Assert\Url(normalizer: 'trim')]
	#[Assert\NotBlank]
	public string $url = '';

	#[ORM\Column]
	#[Assert\Length(min: 0, max: 255)]
	#[Assert\NotBlank]
	public string $siteName = '';

	#[ORM\Column]
	#[Assert\Locale(canonicalize: true)]
	#[Assert\NotBlank(allowNull: false)]
	public string $locale = '';

	#[ORM\Column(type: Types::JSON)]
	#[Assert\All([new Assert\Locale(canonicalize: true)])]
	public array $localeAlternate = [];

	// Structured Properties

	/**
	 * Main image (required)
	 */
	#[ORM\Embedded(class: StructuredProperty\Image::class)]
	public StructuredProperty\Image $image;

	/**
	 * Preview video/trailer (optional)
	 */
	#[ORM\Embedded(class: StructuredProperty\Video::class)]
	public ?StructuredProperty\Video $video = null;

	/**
	 * Preview audio (optional)
	 */
	#[ORM\Embedded(class: StructuredProperty\Audio::class)]
	public ?StructuredProperty\Audio $audio = null;

	// Type-specific Data

	/**
	 * Type-specific data according to Open Graph type
	 */
	#[ORM\Column(type: Types::JSON)]
	public array $typeData = [];

	public function __construct ()
	{
		$this->image = new StructuredProperty\Image();
	}

	public function isVideoType (): bool
	{
		return in_array($this->type, self::TYPE_VIDEO, true);
	}

	public function isMusicType (): bool
	{
		return in_array($this->type, self::TYPE_MUSIC, true);
	}

	/**
	 * video.movie/video.episode/video.tv_show/video.other keys:
	 * - actor: string[]
	 * - director: string[]
	 * - writer: string[]
	 * - duration: int in seconds
	 * - release_date: string ISO 8601
	 * - tag: string[]
	 * - series: string only for `video.episode`
	 */
	public function setVideoData (array $data): self
	{
		if (!$this->isVideoType()) {
			throw new LogicException('Type must be a video type to set video data');
		}
		$this->typeData = $data;

		return $this;
	}

	/**
	 *    music.song keys:
	 *    - duration: int in seconds
	 *    - album: string
	 *    - disc: int
	 *    - track: int
	 *    - musician: string[]
	 *
	 *    music.album/music.playlist keys:
	 *    - song: string[] URLs
	 *    - musician: string[]
	 *    - release_date: string ISO 8601
	 *
	 *    music.radio_station keys:
	 *    - creator: string
	 */
	public function setMusicData (array $data): self
	{
		if (!$this->isMusicType()) {
			throw new LogicException('Type must be a music type to set music data');
		}
		$this->typeData = $data;

		return $this;
	}

	/**
	 *   Article keys:
	 *   - published_time: string ISO 8601
	 *   - modified_time: string ISO 8601
	 *   - expiration_time: string ISO 8601
	 *   - author: string[] URLs of profiles or Names
	 *   - section: string
	 *   - tag: string[]
	 */
	public function setArticleData (array $data): self
	{
		if ($this->type !== 'article') {
			throw new LogicException("Type must be 'article' to set article data");
		}
		$this->typeData = $data;

		return $this;
	}

	/**
	 *  Book keys:
	 *  - author: string[] URLs of profiles or Names
	 *  - isbn: string
	 *  - release_date: string ISO 8601
	 *  - tag: string[]
	 */
	public function setBookData (array $data): self
	{
		if ($this->type !== 'book') {
			throw new LogicException("Type must be 'book' to set book data");
		}
		$this->typeData = $data;

		return $this;
	}

	/**
	 * Profile keys:
	 * - first_name: string
	 * - last_name: string
	 * - username: string
	 * - gender: string male/female
	 */
	public function setProfileData (array $data): self
	{
		if ($this->type !== 'profile') {
			throw new LogicException("Type must be 'profile' to set profile data");
		}
		$this->typeData = $data;

		return $this;
	}
}
