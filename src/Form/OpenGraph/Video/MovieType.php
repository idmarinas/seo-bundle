<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 25/11/2025, 18:27
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    MovieType.php
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
use Idm\Bundle\Seo\Form\OpenGraph\Video\StructuredProperty\ActorType;
use Idm\Bundle\Seo\Form\Type\ArrayFieldType;
use Idm\Bundle\Seo\Form\Type\DateTimeFieldType;
use Idm\Bundle\Seo\Form\Type\IntegerFieldType;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

/**
 * video:actor - profile array - Actors in the movie.
 * video:actor:role - string - The role they played.
 * video:director - profile array - Directors of the movie.
 * video:writer - profile array - Writers of the movie.
 * video:duration - integer >=1 - The movie's length in seconds.
 * video:release_date - datetime - The date the movie was released.
 * video:tag - string array - Tag words associated with this movie.
 */
final class MovieType extends AbstractType
{
	/**
	 * @inheritDoc
	 */
	#[Override]
	public function configureOptions (OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'translation_domain' => 'IdmSeoBundle',
			'required'           => true,
			'attr'               => ['class' => 'row'],
		]);
	}

	/**
	 * @inheritDoc
	 */
	#[Override]
	public function buildForm (FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('releaseDate', DateTimeFieldType::class, [
				'label'              => 'entity.og.video.movie.release_date.label',
				'help'               => 'entity.og.video.movie.release_date.help',
				'column_css_classes' => 'col-sm-12 col-md-6',
			])
			->add('duration', IntegerFieldType::class, [
				'label'              => 'entity.og.video.movie.duration.label',
				'help'               => 'entity.og.video.movie.duration.help',
				'required'           => false,
				'column_css_classes' => 'col-sm-12 col-md-6',
				'attr'               => ['min' => 1, 'step' => 1],
				'constraints'        => [new GreaterThanOrEqual(1),],
			])
			->add('director', ArrayFieldType::class, [
				'label'              => 'entity.og.video.movie.director.label',
				'help'               => 'entity.og.video.movie.director.help',
				'column_css_classes' => 'col-sm-12 col-md-6',
			])
			->add('writer', ArrayFieldType::class, [
				'label'              => 'entity.og.video.movie.writer.label',
				'help'               => 'entity.og.video.movie.writer.help',
				'column_css_classes' => 'col-sm-12 col-md-6',
			])
			->add('tag', ArrayFieldType::class, [
				'label'              => 'entity.og.video.movie.tag.label',
				'help'               => 'entity.og.video.movie.tag.help',
				'column_css_classes' => 'col-sm-12 col-md-4',
			])
			->add('actor', ArrayFieldType::class, [
					'label'              => 'entity.og.video.movie.actor.label',
					'help'               => 'entity.og.video.movie.actor.help',
					'column_css_classes' => 'col-sm-12 col-md-8',
					'entry_type'         => ActorType::class,
				]
			)
		;

		$builder->add('type', HiddenType::class, ['data' => 'video.movie']);

		$builder->setDataMapper(new OpenGraphTypeMapper('video.movie'));
	}
}
