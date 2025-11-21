<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 19/11/2025, 18:57
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    OpenGraphTypeDataConfigurator.php
 * @date    17/11/2025
 * @time    14:26
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Admin\Field\Configurator;

use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldConfiguratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use Idm\Bundle\Seo\Admin\Field\OpenGraphTypeDataField;
use Idm\Bundle\Seo\Entity\Seo;
use Idm\Bundle\Seo\Form\OpenGraph\ArticleType;
use Idm\Bundle\Seo\Form\OpenGraph\BookType;
use Idm\Bundle\Seo\Form\OpenGraph\Music\AlbumType;
use Idm\Bundle\Seo\Form\OpenGraph\Music\PlaylistType;
use Idm\Bundle\Seo\Form\OpenGraph\Music\RadioStationType;
use Idm\Bundle\Seo\Form\OpenGraph\Music\SongType;
use Idm\Bundle\Seo\Form\OpenGraph\ProfileType;
use Idm\Bundle\Seo\Form\OpenGraph\Video\EpisodeType;
use Idm\Bundle\Seo\Form\OpenGraph\Video\MovieType;
use Idm\Bundle\Seo\Form\OpenGraph\Video\OtherType;
use Idm\Bundle\Seo\Form\OpenGraph\Video\TvShowType;
use Override;

final class OpenGraphTypeDataConfigurator implements FieldConfiguratorInterface
{
	#[Override]
	public function supports (FieldDto $field, EntityDto $entityDto): bool
	{
		return OpenGraphTypeDataField::class === $field->getFieldFqcn();
	}

	/**
	 * @inheritDoc
	 */
	#[Override]
	public function configure (FieldDto $field, EntityDto $entityDto, AdminContext $context): void
	{
		/** @var Seo $seo */
		$seo = $context->getEntity()->getInstance()->getSeo();
		$formType = $this->getOpenGraphTypeData($seo->getOg()->type);

		$field->setFormType($formType);
	}

	private function getOpenGraphTypeData (string $type): ?string
	{
		return match ($type) {
			'article'             => ArticleType::class,
			'book'                => BookType::class,
			'profile'             => ProfileType::class,
			// Music
			'music.song'          => SongType::class,
			'music.album'         => AlbumType::class,
			'music.playlist'      => PlaylistType::class,
			'music.radio_station' => RadioStationType::class,
			// Video
			'video.movie'         => MovieType::class,
			'video.episode'       => EpisodeType::class,
			'video.tv_show'       => TvShowType::class,
			'video.other'         => OtherType::class,
		};
	}
}
