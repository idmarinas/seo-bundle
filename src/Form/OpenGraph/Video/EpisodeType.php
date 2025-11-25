<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 25/11/2025, 18:33
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    EpisodeType.php
 * @date    19/11/2025
 * @time    18:43
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Form\OpenGraph\Video;

use Idm\Bundle\Seo\Form\DataMapper\OpenGraphTypeMapper;
use Idm\Bundle\Seo\Form\Type\TextFieldType;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * video:actor - Identical to video.movie
 * video:actor:role
 * video:director
 * video:writer
 * video:duration
 * video:release_date
 * video:tag
 * video:series - video.tv_show - Which series this episode belongs to.
 */
final class EpisodeType extends AbstractType
{
	/**
	 * @inheritDoc
	 */
	#[Override]
	public function getParent (): string
	{
		return MovieType::class;
	}

	/**
	 * @inheritDoc
	 */
	#[Override]
	public function buildForm (FormBuilderInterface $builder, array $options): void
	{
		$field = $builder->get('releaseDate');
		$options = $field->getOptions();
		$options['column_css_classes'] = 'col-sm-12 col-md-4';
		$options['priority'] = 3;
		$builder->add('releaseDate', $field->getType()->getInnerType()::class, $options);

		$field = $builder->get('duration');
		$options = $field->getOptions();
		$options['column_css_classes'] = 'col-sm-12 col-md-4';
		$options['priority'] = 2;
		$builder
			->add('duration', $field->getType()->getInnerType()::class, $options)
			->add('series', TextFieldType::class, [
				'label'              => 'entity.og.video.episode.series.label',
				'help'               => 'entity.og.video.episode.series.help',
				'column_css_classes' => 'col-sm-12 col-md-4',
				'priority'           => 1,
			])
		;

		$builder->get('type')->setData('video.episode');
		$builder->setDataMapper(new OpenGraphTypeMapper('video.episode'));
	}
}
