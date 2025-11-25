<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 25/11/2025, 18:04
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    ArticleType.php
 * @date    14/11/2025
 * @time    16:03
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Form\OpenGraph;

use Idm\Bundle\Seo\Form\DataMapper\OpenGraphTypeMapper;
use Idm\Bundle\Seo\Form\Type\ArrayFieldType;
use Idm\Bundle\Seo\Form\Type\DateTimeFieldType;
use Idm\Bundle\Seo\Form\Type\TextFieldType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * article:published_time - datetime - When the article was first published.
 * article:modified_time - datetime - When the article was last changed.
 * article:expiration_time - datetime - When the article is out of date after.
 * article:author - profile array - Writers of the article.
 * article:section - string - A high-level section name. E.g. Technology
 * article:tag - string array - Tag words associated with this article.
 */
final class ArticleType extends AbstractType
{
	public function configureOptions (OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'translation_domain' => 'IdmSeoBundle',
			'required'           => true,
			'attr'               => ['class' => 'row'],
		]);
	}

	public function buildForm (FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('section', TextFieldType::class, [
				'label'              => 'entity.og.article.section.label',
				'column_css_classes' => 'col-sm-12 col-md-6',
				'help'               => 'entity.og.article.section.help',
			])
			->add('expirationTime', DateTimeFieldType::class, [
				'label'              => 'entity.og.article.expiration_time.label',
				'column_css_classes' => 'col-sm-12 col-md-6',
				'help'               => 'entity.og.article.expiration_time.help',
				'required'           => false,
			])
			->add('publishedTime', DateTimeFieldType::class, [
				'label'              => 'entity.og.article.published_time.label',
				'column_css_classes' => 'col-sm-12 col-md-6',
				'help'               => 'entity.og.article.published_time.help',
			])
			->add('modifiedTime', DateTimeFieldType::class, [
				'label'              => 'entity.og.article.modified_time.label',
				'column_css_classes' => 'col-sm-12 col-md-6',
				'help'               => 'entity.og.article.modified_time.help',
			])
			->add('author', ArrayFieldType::class, [
				'label'              => 'entity.og.article.author.label',
				'column_css_classes' => 'col-sm-12 col-md-6',
				'help'               => 'entity.og.article.author.help',
			])
			->add('tag', ArrayFieldType::class, [
				'label'              => 'entity.og.article.tag.label',
				'column_css_classes' => 'col-sm-12 col-md-6',
				'help'               => 'entity.og.article.tag.help',
			])
			->add('type', HiddenType::class, ['data' => 'article',])
		;

		$builder->setDataMapper(new OpenGraphTypeMapper('article'));
	}
}
